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
$id      = isset($_REQUEST['id'])      ? $_REQUEST['id']      : null;
$genre   = isset($_REQUEST['genre'])   ? $_REQUEST['genre']   : null;
$search   = isset($_REQUEST['search'])   ? $_REQUEST['search']   : null;

if (empty($action)) {
    if ((($_SERVER['REQUEST_METHOD'] == 'POST') ||
            ($_SERVER['REQUEST_METHOD'] == 'PUT') ||
            ($_SERVER['REQUEST_METHOD'] == 'DELETE')) &&
        (strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false)) {

        $input = json_decode(file_get_contents('php://input'), true);

        $action     = isset($input['action'])   ? $input['action']  : null;
        $data       = isset($input['data'])     ? $input['data']    : null;
        $genre      = isset($input['genre'])    ? $input['genre']   : null;
        $search     = isset($input['search'])   ? $input['search']  : null;        
    }
}

$db = pdoDB::getConnection(); // connect to db

//set the header to json because everything is returned in that format
header("Content-Type: application/json");

// take the appropriate action based on the action
switch ($action) {
    case 'listAlbums':

        if ((empty($genre)) && (empty($search))) {
            $clause = 1;
        } elseif ((!empty($genre)) && (empty($search))) {
            $clause = "a.genre_id = $genre";
        } elseif ((empty($genre)) && (!empty($search))) {
            $clause = "album_name LIKE '%$search%'";
        } elseif ((!empty($genre)) && (!empty($search))) {
            $clause = "album_name LIKE '%$search%'
                    AND a.genre_id = $genre";
        } else {
            $clause = 1;
        }

        $sqlAlbums = "SELECT a.album_id, a.name AS album_name, a.album_rating, a.artwork, a.compilation,
                       a.composer, a.disc_count, a.genre_id, a.disc_number, a.sort_album, a.year, g.genre_id,
                        g.name AS genre_name
                       FROM i_album AS a
                       INNER JOIN i_genre AS g
                       ON a.genre_id = g.genre_id
                       WHERE $clause
                       ORDER BY album_name";

        $rs         = new JSONRecordSet();
        $retval     = $rs->getRecordSet($sqlAlbums, 'ResultSet');
        echo $retval;
        break;
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
    case 'listGenres':
        $sqlGenres = "SELECT g.genre_id, g.name
                        FROM i_genre AS g
                        ORDER BY g.name";

        $rs         = new JSONRecordSet();
        $retval     = $rs->getRecordSet($sqlGenres, 'ResultSet');
        echo $retval;
        break;
    case 'listNotes':
        $id                = $db->quote($id);
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
