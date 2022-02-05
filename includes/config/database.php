<?php 

function conectarBBDD() : mysqli {

    $db = mysqli_connect('localhost', 'root', '', 'bienesraices');

    if(!$db) {
        echo 'Error';
        exit;
    }

    return $db;

}
