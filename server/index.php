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
        $sqlAlbumTracks = "SELECT track_id, artist_id, comments, composer, kind,
                              location, name, play_count, rating, size, total_time
                              FROM i_track AS t
                              INNER JOIN i_album_track AS at
                              ON t.track_id = at.track_id
                              WHERE album_id = $id";

        $rs                = new JSONRecordSet();
        $retval            = $rs->getRecordSet($sqlAlbumTracks);
        echo $retval;
        break;
    case 'listAlbums':
        $sqlAlbums = "SELECT album_id, name, album_rating, artwork, compilation,
                       composer, disc_count, disc_number, sort_album, year, genre_id
                       FROM i_album
                       ORDER BY name";

        $rs         = new JSONRecordSet();
        $retval     = $rs->getRecordSet($sqlAlbums, 'ResultSet');
        echo $retval;
        break;
    case 'listgenre':
        $sqlAlbums = "SELECT genre_id, name
                       FROM i_genre
                       ORDER BY name";

        $rs         = new JSONRecordSet();
        $retval     = $rs->getRecordSet($sqlAlbums, 'ResultSet');
        echo $retval;
        break;
    default:
        echo '{"status":"error", "message":{"text": "default no action taken"}}';
        break;
}
