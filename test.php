<?php

// require the connection class
require_once('./server/classes/pdoDB.class.php');
require_once('./server/classes/Webpage.class.php');

try {
    $page = new Webpage( "Test page", array('./resources/css/app.css'));

    $db = pdoDB::getConnection();

    $page->addToBody("  
    
    <h1>Testing page</h1>

    <br/>
    <br/>
    <br/>
    <br/>
    <br/>
    
    <a href='../cm0665-assignment/index.html'>Return to CD store homepage</a>

    <p>Show all albums link</p>
    <a href='http://localhost/cm0665-assignment/server/index.php?action=listAlbums'>
    http://localhost/cm0665-assignment/server/index.php?action=listAlbums</a>

    <p>Show all genres link</p>
    <a href='http://localhost/cm0665-assignment/server/index.php?action=listGenres'>
    http://localhost/cm0665-assignment/server/index.php?action=listGenres</a> 

    <p>Show albums by selected genre</p>
    <a href='http://localhost/cm0665-assignment/server/index.php?action=listAlbums&genre=2'>
    http://localhost/cm0665-assignment/server/index.php?action=listAlbums&genre=2</a>

    <p>Show albums or artists by just search input</p>
    <a href='http://localhost/cm0665-assignment/server/index.php?action=listAlbums&search=something'>
    http://localhost/cm0665-assignment/server/index.php?action=listAlbums&search=something</a>

    <p>Show albums by search and genre</p>
    <a href='http://localhost/cm0665-assignment/server/index.php?action=listAlbums&genre=2&search=hech'>
    http://localhost/cm0665-assignment/server/index.php?action=listAlbums&genre=2&search=hech</a>
    
    <p>Show tracks by album ID link</p>
    <a href='http://localhost/cm0665-assignment/server/index.php?action=listTracks&id=1'> 
    http://localhost/cm0665-assignment/server/index.php?action=listTracks&id=1</a>

    <p>Show all notes link</p>
    <a href='http://localhost/cm0665-assignment/server/index.php?action=listNotes&id=1'>
    http://localhost/cm0665-assignment/server/index.php?action=listNotes&id=1</a> 
        
    ");

    echo $page->getPage();

} catch (Exception $e) {
    echo "<p>Oops something went wrong . $e->getMessage() . </p>";
}

//genre link
//http://localhost/cm0665-assignment/server/index.php?action=listGenres 

//track link
//http://localhost/cm0665-assignment/server/index.php?action=listTracks&id=1 

//album link
//http://localhost/cm0665-assignment/server/index.php?action=listAlbums 

//notes link
//http://localhost/cm0665-assignment/server/index.php?action=listNotes$id=1 

//search just text input
//http://localhost/cm0665-assignment/server/index.php?action=listAlbums&search=something

//filter with genre
//http://localhost/cm0665-assignment/server/index.php?action=listAlbums&genre=2

//filter and genre
//http://localhost/cm0665-assignment/server/index.php?action=listAlbums&genre=2&search=hech