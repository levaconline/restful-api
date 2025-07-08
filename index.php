<?php
session_start();

// Is logged? Who is logged?
$userId = 0;
if (!empty($_SESSION["usid"])) {
    $userId = (int)$_SESSION["usid"];
}

// Paths
// Change path according to place of app dir you had choosen. ('/app' is default and should be changed in real life.)
$appPath = '/app';

// IMPORTANT! Put functions.php out of http. (change location to avoid huge vulnerability)
// Security guard.
$functionsPath = __DIR__ . "/functions.php";

if (!file_exists($functionsPath)) {
    die("Bad luck");
}

// Include few important functions.
//require $functionsPath;
//customsControl();


// Autoload all from Library (Basic autoloader for dempo - no need composer here)
spl_autoload_register(function ($className) {

    // Put dir on hidden location and change following apth acordingly if you wush.
    $librarryDir = __DIR__ . '/app/library/';
    $file = $librarryDir . str_replace('\\', '/', $className) . '.php';

    // if the file exists, require it
    if (file_exists($file)) {
        require_once $file;
    } else {
        throw new Exception("The file $file does not exist.");
    }
});


// Define few locations
$controllersDir = __DIR__ . $appPath  . '/controllers';
$modelsDir = __DIR__ . $appPath  . '/models';
$coreDir = __DIR__ . $appPath  . '/core';
$viewsDir = $appPath  . '/views';
$layoutsDir = __DIR__ . $appPath  . '/views/layouts';

// Allow acccess without user loged in to following pages:
$withoutLogedIn = ['login', 'register', 'resetpassword', 'forgotpassword'];

// Set defaults
$layout = "default";
$lanuage = "en";

// Analyze params (from url) and resolve classes and methods
$appContoller = (string)filter_input(INPUT_GET, 'c');
$appAction = (string)filter_input(INPUT_GET, 'a');
$lang = (string)filter_input(INPUT_GET, 'l');

if (!empty($appContoller)) {
    $appContoller = $appContoller;
    if (!empty($appAction)) {
        $appAction = $appAction;
    } else {
        $appAction = "index";
    }
} else {
    // Default is: homepage.
    $appContoller = 'Home';
    $appAction = 'index';
}

// If user not logged only login  / register is available.
if ($userId < 1) {
    if ($appContoller == 'Administrator' && in_array($appAction, $withoutLogedIn)) {
        // TODO: Special tretment.
    } else {
        $layout = "notlogged";
        $appContoller = 'Administrator';
        $appAction = 'login';
    }
}

// For not logged users use special layout.
if ($appContoller == 'Administrator' && $appAction == 'login') {
    $layout = "notlogged";
}

// Determine language
if (!empty($lang)) {
    if ($lang == 'sr_lat') {
        $lanuage = 'sr_lat';
    } elseif ($lang == 'sr_cyr') {
        $lanuage = 'sr_cyr';
    } else {
        $lanuage = 'en';
    }
}

// For include by default (no need autoload/composer)
// Modes must have same name as controller wits added "s" at the end of controller name.
// Don't change following. It is expected structure.
$controller = $controllersDir . "/" . $appContoller . ".php";
$model = $modelsDir . "/" . $appContoller . "s.php";
$lib = $coreDir . "/lib.php";
$db = $coreDir . "/db.php";
$root = $coreDir . "/root.php";


if (file_exists($controller) && file_exists($model)) {

    // Load necessary things from app directory...	
    require $root;
    require $db;
    require $lib;
    require $model;
    require $controller;

    // Prepare things
    $className = $appContoller;
    $class = new $className();

    if (!method_exists($class, $appAction)) {
        die('Bad url!');
    }

    // Set things
    $class->interfaceLanguage = $lanuage;  // User's language
    $class->controller = $appContoller;  // Controller name
    $class->action = $appAction;  // Controller targer public method
    $class->views = $viewsDir;  // Views location
    $class->view = $appAction;  // Action name
    $class->layout = $layout;  // Layout
    $ip = $class->currentIP();


    // Optional: send data to email ot log them for your testing/debugging. (Debug purpose.)
    //$class->sendSpecMail($appContoller . "::" . $appAction . "\nUID: " . $userId . "\nIP: " . $ip);

    // Here we go... 
    $data = call_user_func(array($class, $appAction));
    if ($class->layout) {
        if (file_exists($layoutsDir . "/" . $class->layout . ".php")) {
            require $layoutsDir . "/" . $class->layout . ".php";
        } else {
            die('Layout does not exist.');
        }
    }
} else {
    die('Bad url.');
}
