<?php
/**
 * Created by PhpStorm.
 * User: w14027233
 * Date: 09/02/2018
 * Time: 14:28
 */

require_once('./classes/pdoDB.class.php');
require_once('./classes/recordSet.class.php');


$action  = isset($_REQUEST['action'])  ? $_REQUEST['action']  : null;
$subject = isset($_REQUEST['subject']) ? $_REQUEST['subject'] : null;
$id      = isset($_REQUEST['id'])      ? $_REQUEST['id']      : null;

if (empty($action)) {
    if ((($_SERVER['REQUEST_METHOD'] == 'POST') ||
            ($_SERVER['REQUEST_METHOD'] == 'PUT') ||
            ($_SERVER['REQUEST_METHOD'] == 'DELETE')) &&
        (strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false)) {

        $input = json_decode(file_get_contents('php://input'), true);

        $action = isset($input['action']) ? $input['action'] : null;
        $subject = isset($input['subject']) ? $input['subject'] : null;
        $data = isset($input['data']) ? $input['data'] : null;
    }
}

// concat action and subject with uppercase first letter of subject
$route = $action . ucfirst($subject); // eg list course becomes listCourse

$db = pdoDB::getConnection(); // connect to db

//set the header to json because everything is returned in that format
header("Content-Type: application/json");

// take the appropriate action based on the action and subject
switch ($route) {
    case 'listTracks':
        $id                = $db->quote($id);
        $sqlAlbumTracks = "SELECT t.track_id, t.comments,t.artist_id, t.composer, t.kind,
                              t.location, t.name AS track_name, t.play_count, t.rating, t.size, t.total_time,
                              art.artist_id, art.name AS artist_name
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
    case 'listAlbums':
        $sqlAlbums = "SELECT a.album_id, a.name AS album_name, a.album_rating, a.artwork, a.compilation,
                       a.composer, a.disc_count, a.disc_number, a.sort_album, a.year, g.name AS genre_name
                       FROM i_album AS a
                       INNER JOIN i_genre AS g 
                       ON a.genre_id = g.genre_id
                       ORDER BY album_name";

        $rs         = new JSONRecordSet();
        $retval     = $rs->getRecordSet($sqlAlbums, 'ResultSet');
        echo $retval;
        break;
    default:
        echo '{"status":"error", "message":{"text": "default no action taken"}}';
        break;
}
