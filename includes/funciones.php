<?php 

require 'app.php';

function incluirTemplate( string $nombre, bool $headerIndex = false ) {
    include TEMPLATES_URL . "/$nombre.php";
}

?>