<?php
/**
 * Created by PhpStorm.
 * User: w14027233
 * Date: 09/02/2018
 * Time: 14:28
 */

require_once('./classes/pdoDB.class.php');
require_once('./classes/recordSet.class.php');

// pulling data from the request stream

$action     = isset($_REQUEST['action'])    ? $_REQUEST['action']   : null;
$id         = isset($_REQUEST['id'])        ? $_REQUEST['id']       : null;
$genre      = isset($_REQUEST['genre'])     ? $_REQUEST['genre']    : null;
$search     = isset($_REQUEST['search'])    ? $_REQUEST['search']   : null;
$username   = isset($_REQUEST['username'])  ? $_REQUEST['username'] : null;
$password   = isset($_REQUEST['username'])  ? $_REQUEST['username'] : null;

if (empty($action)) {
    if ((($_SERVER['REQUEST_METHOD'] == 'POST') ||
            ($_SERVER['REQUEST_METHOD'] == 'PUT') ||
            ($_SERVER['REQUEST_METHOD'] == 'DELETE')) &&
        (strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false)) {

        $input = json_decode(file_get_contents('php://input'), true);

        $action     = isset($input['action'])   ? $input['action']   : null;
        $data       = isset($input['data'])     ? $input['data']     : null;
        $genre      = isset($input['genre'])    ? $input['genre']    : null;
        $search     = isset($input['search'])   ? $input['search']   : null;
        $username   = isset($input['username']) ? $input['username'] : null;
        $password   = isset($input['password']) ? $input['password'] : null;          
    }
}

$db = pdoDB::getConnection(); // connect to db

//set the header to json because everything is returned in that format
header("Content-Type: application/json");

// take the appropriate action based on the action in the request
switch ($action) {
    //query into the database that lists all albums or list albums matching search terms
    case 'listAlbums':

    //check to see search terms have been set and add SQL to WHERE clause

    if (!empty($genre) && !empty($search)) {
        $clause = "a.name LIKE '%$search%'
                AND a.genre_id = $genre";

    } else if (!empty($genre) && empty($search)) {
        $clause = "a.genre_id = $genre";

    } else if (empty($genre) && !empty($search)) {
        $clause = "a.name LIKE '%$search%'";

    } else {
            $clause = 1;
    }

        $sqlAlbums = "SELECT a.album_id, a.name AS album_name, a.album_rating, a.artwork, a.compilation,
                       a.composer, a.disc_count, a.genre_id, a.disc_number, a.sort_album, a.year, g.genre_id,
                        g.name AS genre_name, art.name AS artist_name, t.total_time
                       FROM i_album AS a
                       LEFT JOIN i_genre AS g ON a.genre_id = g.genre_id

                       LEFT JOIN i_album_track AS alt ON a.album_id = alt.album_id
                       
                       LEFT JOIN i_track AS t ON alt.track_id = t.track_id 
                       
                       LEFT JOIN i_artist AS art ON t.artist_id = art.artist_id
                       WHERE $clause
                       GROUP BY album_name
                       ORDER BY album_name";

        $rs         = new JSONRecordSet();
        $retval     = $rs->getRecordSet($sqlAlbums, 'ResultSet');
        echo $retval;
        break;
    //List to show all tracks related to the album id passed in the request stream
    case 'listTracks':
        $id                = $db->quote($id);
        $sqlAlbumTracks = "SELECT t.track_id, t.comments,t.artist_id, t.composer, t.kind,
                              t.location, t.name AS track_name, t.play_count, t.rating, t.size, t.total_time,
                              art.artist_id, art.name AS artist_name, a.track_number
                              FROM i_track AS t
                              INNER JOIN i_album_track AS a
                              ON t.track_id = a.track_id
                              INNER JOIN i_artist AS art
                              ON art.artist_id = t.artist_id
                              WHERE album_id = $id
                              ORDER BY track_number";

        $rs                = new JSONRecordSet();
        $retval            = $rs->getRecordSet($sqlAlbumTracks);
        echo $retval;
        break;
    //a query to get all genres for the select genre search filter
    case 'listGenres':
        $sqlGenres = "SELECT g.genre_id, g.name
                        FROM i_genre AS g
                        ORDER BY g.name";

        $rs         = new JSONRecordSet();
        $retval     = $rs->getRecordSet($sqlGenres, 'ResultSet');
        echo $retval;
        break;
    //query to list all notes linked to the album id from the request stream
    case 'listNotes':
        $id                = $db->quote($id);
        $sqlNotes = "SELECT *
                        FROM i_notes
                        WHERE album_id = $id";

        $rs                = new JSONRecordSet();
        $retval            = $rs->getRecordSet($sqlNotes);
        echo $retval;
        break;
    //query for user login --- not complete
    case 'loginAdmin':

        
        $sqlNotes = "SELECT *
                        FROM i_notes
                        WHERE album_id = $id";

        $rs                = new JSONRecordSet();
        $retval            = $rs->getRecordSet($sqlNotes);
        echo $retval;
        break;
    //query for user logout --- not complete
    case 'logoutAdmin':
        $sqlNotes = "SELECT *
                        FROM i_notes
                        WHERE album_id = $id";

        $rs                = new JSONRecordSet();
        $retval            = $rs->getRecordSet($sqlNotes);
        echo $retval;
        break;
    default:
        echo '{"status":"error", "message":{"text": "default no action taken"}}';
        break;
}
