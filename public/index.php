<?php
    ob_start();
    session_start();
    
    require_once '../config/routing.php';

    $path = $_SERVER['REQUEST_URI'];
    
     if ( $pos = strpos($path, '?') ) {
        $path = substr($path, 0, $pos);
    }
 
    if ( strlen($path) > 1 && $path[0] === '/' ) {
        $path = substr($path, 1);
    }

    if ( !isset($routing[ $path ]) ) {
        echo 'Strona o podanym adresie nie została odnaleziona.';
        die;
    }

    $routingData = $routing[$path];

    if ( isset($routingData['before_header']) ) {
        require_once '../'.$routingData['before_header'];
    }
?>


<html>
    <head>
        <title><?php echo $routingData['title']; ?></title>
        <link rel="shortcut icon" type="image/png" href="/img/event_logo.png"/>
        <link rel="stylesheet" type="text/css" href="style.css">
        <link href="/fontawesome/css/all.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    </head>
    <body>
        <?php if ( !isset($routingData['navbar']) || $routingData['navbar'] === true ) 
        {
            include_once '../template/basic/header.php';
        } 
        ?>

        <main>
            <?php include_once '../template/'.$routingData['view']; ?>
        </main>

    </body>
</html>
