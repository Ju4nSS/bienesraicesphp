<?php 

require 'app.php';

function incluirTemplate( $nombre ) {
    $index = true;
    include TEMPLATES_URL . "/$nombre.php";
}

?>