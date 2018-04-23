<?php

// require the connection class
require_once('../../classes/pdoDB.class.php');
require_once('../../classes/Webpage.class.php');

try {
    $page = new Webpage( "Test page", array('resources/css/app.css') );

    $db = pdoDB::getConnection();


} catch (Exception $e) {
    echo "<p>Oops something went wrong . $e->getMessage() . </p>";
}