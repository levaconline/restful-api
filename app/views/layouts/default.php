<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo $class->projectName; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <div class="container-fluid">
        <header>
            <h3 class="text-center"><?php echo $class->projectName; ?></h3>
        </header>
        <?php
        include __DIR__ . "/elements/errors.elem";
        include __DIR__ . "/elements/warnings.elem";
        include __DIR__ . "/elements/info.elem";
        require __DIR__ . "/elements/menu_default.elem";
        ?>
        <section>
            <?php
            require __DIR__ . "/../../.." . $class->views . "/" . $class->controller . "/" . $class->action . ".php";
            ?>
        </section>
        <div>&nbsp;</div>
        <div>
            Exec. time: <?php echo $class->execTime(); ?>s
        </div>
        <div>&nbsp;</div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>