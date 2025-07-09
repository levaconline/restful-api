<?php

/**
 * Root class. All begins and all ends here.
 * @author Aleksandar Todorovic
 * @version 1.0
 * @package core
 * @since 1.0
 * @todo Add more methods and properties as needed.
 * 
 */
class Root
{
    // IMPORTANT! Change location (out of http access)
    private string $envFilePath = __DIR__ . '/../../.env';

    // Parsed data from .env file
    protected $envData = [];

    // Will be set automatically by index file.
    public $interfaceLanguage = '';
    public $controller = '';
    public $action = '';
    public $views = '';
    public $view = '';
    public $layout = '';

    // Will be set by scripts (run time)
    public $data = []; // Data for pass to View.
    public $errors = [];
    public $alerts = [];
    public $messages = [];
    protected $loggedin = false; // User is logged or not?
    protected $startTime = 0;  // For benchmark.
    protected $ip = '';
    protected $proxyUser = false; //Basic try to check is proxy or not.
    protected $userId = 0;

    // Part to be read from config file. Can be added anything if you know how.
    public $projectName = "";
    protected $controllersDir = '/controllers';
    protected $modelsDir = '/models';
    protected $viewsDir = '/views';
    protected $storageDir = '/storage';
    protected $galleryDir = "/storage/gallery";
    protected $jsDir = "/app/helpers/js/";
    protected $cssDir = "/app/helpers/css/";
    protected $logsDir = __DIR__ . "/../misc/logs";


    public function __construct()
    {
        // For benchmark.
        $this->startTime = microtime(true);

        // Get custom env data. >> We will use custom istead some of well tested library (because it is our code and our responsibility)
        $this->parseCustomEnv();
        $this->projectName = $this->envData['APP_NAME'];

        // Logged user or not?
        $this->userId = (isset($_SESSION['usid']) && (int)$_SESSION['usid'] > 0) ? (int)$_SESSION['usid'] : 0;

        // IP
        $this->getIP();

        $this->loggedin = $this->userId > 0 ? true : false;
    }


    /**
     * Get user ID. (Getter)
     * @return int
     */
    public function getUid()
    {
        return $this->userId;
    }

    /**
     * Get current IP address.
     * @return string
     */
    public function currentIP()
    {
        return $this->ip;
    }

    /**
     * Write string to destination file. (if file does not exist it will be created)
     * @param string $to
     * @param string $line = ''
     * @return bool
     */
    // Write string to destination file. (if file does not exist it will be created)
    protected function writeLine(string $to, string $line = ''): bool
    {
        if (!file_exists($to)) {
            try {
                $this->createDirsStructure($to); // If file dose not exist, create it.
                $result = file_put_contents($to, $line . "\n", FILE_APPEND | LOCK_EX);
                if ($result === false) {
                    throw new Exception("Failed to write to file: $to");
                }
            } catch (Exception $e) {
                $this->logMe(__CLASS__ . '.log', $e->getMessage());
                return false;
            }
        }
        /*
        try {
            $f = fopen($to, 'a');
            if ($f === false) {
                throw new Exception("Failed to open file: $to");
            }
            if (flock($f, LOCK_EX)) {
                fwrite($f, $line . "\n");
                flock($f, LOCK_UN);
            } else {
                throw new Exception("Failed to lock file: $to");
            }
        } catch (Exception $e) {
            $this->logMe(__CLASS__ . '.log', $e->getMessage());
        } finally {
            if (isset($f) && is_resource($f)) {
                fclose($f);
            }
        }
        return true;
        */

        /*
        $f = fopen($to, 'a');
        fwrite($f, $line . "\n");
        fclose($f);
        */
    }

    /**
     * Write string to destination file. (if file does not exist it will be created)
     * @param string $to
     * @param array $data
     * @return bool
     */
    // Write string to destination file. (if file does not exist it will be created)
    protected function writeSLine(string $to, string $line = ''): bool
    {
        if (!$this->pathExists($to)) {
            return false;
        }

        $f = fopen($to, 'a');
        fwrite($f, $line);
        fclose($f);

        return true;
    }

    /**
     * Write CSV line to destination file.
     * 
     * @param string $to
     * @param array $data
     * @return bool
     */
    protected function writeCSVLine(string $to, array $data): bool
    {
        if (!$this->pathExists($to)) {
            return false;
        }

        $f = fopen($to, 'a');
        fputcsv($f, $data, ';', '"');
        fclose($f);

        return true;
    }

    /**
     * Create complete path with dirs and subdirs (if not exist).
     * Take care about permissions - defauld is 777 but it is is  dangerous 
     * so, change permissions to fit to exactely ehat you need.
     * 
     * @param string $path
     * 
     * @return bool 
     */
    private function createDirsStructure(string $path = ''): bool
    {
        if (trim($path) == '') {
            return false;
        }

        $dirs = pathinfo($path);

        if (!file_exists($dirs['dirname'])) {
            if (!mkdir($dirs['dirname'], 0750, true)) {
                throw new Exception("Failed to create directory: {$dirs['dirname']}");
            }
        }

        if (isset($dirs['basename'])) {
            $this->resetLogFile($path);
        }

        // If path not created, throe exception.
        if (!file_exists($path)) {
            // If server is setup as need, ti will vrite own error log with following message
            throw new Exception("Can\'t create path: {$path}. Possible permissions issue.");
            return false;
        } else {
            return true;
        }
    }

    /**
     * When you need UTF-8 BOM file
     * UTF-8 BOM is not recommended but in some cases (rare languages chars it can help)
     * @param string $to (file path)
     * 
     * @return void
     */
    protected function bomFile(string $to): void
    {
        $f = @fopen($to, 'w');
        @fputs($f, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));
        @fclose($f);
    }

    /**
     * Truncate file
     * 
     * @param string $filePath
     * 
     * @return void
     */
    protected function resetLogFile(string $filePath): void
    {
        $f = @fopen($filePath, "w");
        @fwrite($f, '');
        @fclose($f);
    }

    /**
     * Write line to log file
     * 
     * @param string $filename
     * @param string $line
     * 
     * @return void
     */
    protected function logMe(string $filename, string $line = ''): void
    {
        $this->writeLine($this->logsDir . "/" . $filename, $line);
    }

    /**
     * Put execTime to data array
     * @return float
     */
    public function execTime(): float
    {
        return round((microtime(true) - $this->startTime), 5);
    }

    /**
     * Try to find user's IP (not trustfull) 
     * Don't trust proxy or other sources.
     * @return void
     */
    protected function getIP(): void
    {
        $ip = '';
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            //ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            //ip pass from proxy
            $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
            $this->proxyUser = true;
        } else (!empty($_SERVER['REMOTE_ADDR'])) {
            // This means: use spinner for this visitor 
            // to show him false info and track activities.
            // Dont block this visitor. Log every his move or request
            // to be able to stay up to date with hackers methodologies (something new may be find).
            // NOTE: Hack back is prohibitted by low from well known reason.

            $ip = $_SERVER['REMOTE_ADDR'];
        }

        $ip = trim($ip);

        // Is valid IP?
        $this->ip = filter_var($ip, FILTER_VALIDATE_IP) ? $ip : '';
    }

    /**
     * Check path existance. If path does not exist, 
     * try to create it.
     * 
     * @param string $path
     * 
     * @return bool
     */
    private function pathExists(string $path): bool
    {
        if (!file_exists($path)) {
            try {
                $this->createDirsStructure($path);
            } catch (Exception $e) {
                $this->logMe(__CLASS__ . '.log', $e->getMessage());
                return false;
            }
        }

        return true;
    }

    /**
     * Usually dedicated for forms security.
     * 
     * @return void
     */
    protected function generateToken(): void
    {
        $this->data['token'] = $_SESSION['token'] = bin2hex(random_bytes(35));
    }

    /**
     * Check token 
     * If tokens not fit, die or use speial spinner to track and log any visitors move.
     * Since this is demo, here is used option die();  to loverage code source.
     * If token is valid, continue with script.
     * 
     * @return bool
     */
    protected function isValidToken($token = null): bool
    {
        if (!$token || !hash_equals($_SESSION['token'], $token)) {
            $this->logMe(__CLASS__  . '.log', "Tokens not fit: " . $this->data['token'] . " <-> " . $_SESSION['token'] . "IP: " . $this->ip);
            header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');

            // if header fail, die.
            die();
        }
        return true;
    }

    /**
     * Parse .env file and store data in $envData array.
     * @return bool
     */
    protected function parseCustomEnv(): bool
    {
        if (!file_exists($this->envFilePath)) {
            $this->logMe(__CLASS__ . '.log', "No .env file found: " . $this->envFilePath);
            return false;
        }

        $env = file_get_contents($this->envFilePath);
        $env = explode("\n", $env);

        $envData = [];
        $validNamePattern = '/^[A-Z_]+$/'; // Modify pattern according your needs for valid names.

        foreach ($env as $line) {
            $line = trim($line);
            if (empty($line) || strpos($line, '#') === 0) {
                continue;
            }

            $line = explode("=", $line, 2);
            $name = trim($line[0]);
            $value = trim($line[1] ?? '');

            if (preg_match($validNamePattern, $name)) {
                $envData[$name] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            }
        }

        $this->envData = $envData;
        return true;
    }
}
