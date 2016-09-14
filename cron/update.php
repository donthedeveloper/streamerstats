<?php

// ini_set('display_errors', 1);
// error_reporting(E_ERROR);


// DATABASE FUNCTIONS
function dbConnect() { // Connnect To Database
  require("../resources/config.php");
  
  try {
    $dbconnect = new PDO('mysql:host=' . $dbConfig["dbhost"] . ';dbname=' . $dbConfig["dbname"], $dbConfig["dbuser"], $dbConfig["dbpass"]);
    $dbconnect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbconnect;
  } catch (Exception $exception) {
//     echo $exception;
    die();
  }
}


function getStreamerNamesFromDb($dbconnect) { // Get List of Streamers From Database
  try {
    $getStatistics = $dbconnect->query("SELECT name FROM streamers");
    $getStatistics->execute();

    while ($row = $getStatistics->fetch(PDO::FETCH_ASSOC)) {
      $streamerNamesArray[] = $row['name'];      
    }
    
    return $streamerNamesArray;
  } catch (Exception $exception) {
//     echo $exception;
  }  
}


function insertStreamerData($json, $dbconnect) {
  try {
    $insertStatistic = $dbconnect->prepare("INSERT INTO stats (
        stream_id, 
        stream_game, 
        stream_viewers, 
        stream_created_at, 
        stream_video_height, 
        stream_average_fps, 
        stream_delay, 
        stream_is_playlist, 
        preview_small, 
        preview_medium, 
        preview_large, 
        preview_template, 
        channel_mature, 
        channel_status, 
        channel_broadcaster_language, 
        channel_display_name, 
        channel_game, 
        channel_language, 
        channel_id, 
        channel_name, 
        channel_created_at, 
        channel_updated_at, 
        channel_delay, 
        channel_logo, 
        channel_banner, 
        channel_video_banner, 
        channel_background, 
        channel_profile_banner, 
        channel_profile_banner_background_color, 
        channel_partner, 
        channel_views, 
        channel_followers         
      ) VALUES (
        :stream_id, 
        :stream_game, 
        :stream_viewers, 
        :stream_created_at, 
        :stream_video_height, 
        :stream_average_fps, 
        :stream_delay, 
        :stream_is_playlist, 
        :preview_small, 
        :preview_medium, 
        :preview_large, 
        :preview_template, 
        :channel_mature, 
        :channel_status, 
        :channel_broadcaster_language, 
        :channel_display_name, 
        :channel_game, 
        :channel_language, 
        :channel_id, 
        :channel_name, 
        :channel_created_at, 
        :channel_updated_at, 
        :channel_delay, 
        :channel_logo, 
        :channel_banner, 
        :channel_video_banner, 
        :channel_background, 
        :channel_profile_banner, 
        :channel_profile_banner_background_color, 
        :channel_partner, 
        :channel_views, 
        :channel_followers  
      )");
    $insertStatistic->bindValue(':stream_id', $json->_id, PDO::PARAM_INT);
    $insertStatistic->bindValue(':stream_game', $json->game, PDO::PARAM_STR);
    $insertStatistic->bindValue(':stream_viewers', $json->viewers, PDO::PARAM_INT);
    $insertStatistic->bindValue(':stream_created_at', $json->created_at, PDO::PARAM_STR);
    $insertStatistic->bindValue(':stream_video_height', $json->video_height, PDO::PARAM_STR);
    $insertStatistic->bindValue(':stream_average_fps', $json->average_fps, PDO::PARAM_INT);
    $insertStatistic->bindValue(':stream_delay', $json->delay, PDO::PARAM_INT);
    $insertStatistic->bindValue(':stream_is_playlist', $json->is_playlist, PDO::PARAM_BOOL);
    $insertStatistic->bindValue(':preview_small', $json->preview->small, PDO::PARAM_STR);
    $insertStatistic->bindValue(':preview_medium', $json->preview->medium, PDO::PARAM_STR);
    $insertStatistic->bindValue(':preview_large', $json->preview->large, PDO::PARAM_STR);
    $insertStatistic->bindValue(':preview_template', $json->preview->template, PDO::PARAM_STR);
    $insertStatistic->bindValue(':channel_mature', $json->channel->mature, PDO::PARAM_BOOL);
    $insertStatistic->bindValue(':channel_status', $json->channel->status, PDO::PARAM_STR);
    $insertStatistic->bindValue(':channel_broadcaster_language', $json->channel->broadcaster_language, PDO::PARAM_STR);
    $insertStatistic->bindValue(':channel_display_name', $json->channel->display_name, PDO::PARAM_STR);
    $insertStatistic->bindValue(':channel_game', $json->channel->game, PDO::PARAM_STR);
    $insertStatistic->bindValue(':channel_language', $json->channel->language, PDO::PARAM_STR);
    $insertStatistic->bindValue(':channel_id', $json->channel->_id, PDO::PARAM_INT);
    $insertStatistic->bindValue(':channel_name', $json->channel->name, PDO::PARAM_STR);
    $insertStatistic->bindValue(':channel_created_at', $json->channel->created_at, PDO::PARAM_STR);
    $insertStatistic->bindValue(':channel_updated_at', $json->channel->updated_at, PDO::PARAM_STR);
    $insertStatistic->bindValue(':channel_delay', $json->channel->delay, PDO::PARAM_STR);
    $insertStatistic->bindValue(':channel_logo', $json->channel->logo, PDO::PARAM_STR);
    $insertStatistic->bindValue(':channel_banner', $json->channel->banner, PDO::PARAM_STR);
    $insertStatistic->bindValue(':channel_video_banner', $json->channel->video_banner, PDO::PARAM_STR);
    $insertStatistic->bindValue(':channel_background', $json->channel->background, PDO::PARAM_STR);
    $insertStatistic->bindValue(':channel_profile_banner', $json->channel->profile_banner, PDO::PARAM_STR);
    $insertStatistic->bindValue(':channel_profile_banner_background_color', $json->channel->profile_banner_background_color, PDO::PARAM_STR);
    $insertStatistic->bindValue(':channel_partner', $json->channel->partner, PDO::PARAM_BOOL);
    $insertStatistic->bindValue(':channel_views', $json->channel->views, PDO::PARAM_INT);
    $insertStatistic->bindValue(':channel_followers', $json->channel->followers, PDO::PARAM_STR);

    $insertStatistic->execute();
  } catch(Exception $exception) {
//     echo $exception;
    die();
  }
}
// /DATABASE FUNCTIONS



// JSON FUNCTIONS
function getJSON($streamerNamesArray) {
  // build CURLOPT_URL
  $url = "https://api.twitch.tv/kraken/streams?channel=";
  foreach($streamerNamesArray as $value) {
    $url .= "$value,";
  }
  
  // create new cURL resource
  $curl = curl_init();

  // set parameters
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_HEADER, "Client-ID: 996904fd6jhi3xd7a1q0gwm13px9mpk");
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

  // grab url and pass it to the browser
  $json = curl_exec($curl);
//   $json = json_decode($json);

  // close cURL resource (free up resources)
  curl_close($curl);
  
  return $json;
}


function filterJSON($krakenJson, $dbconnect) {
  $krakenObject = json_decode($krakenJson);

  foreach ($krakenObject->streams as $streamerKrakenArray) {
  //   echo "<br /><br />";
  //   print_r($streamerKrakenArray->channel->name);

    insertStreamerData($streamerKrakenArray, $dbconnect);
  }
}
// /JSON FUNCTIONS



$dbconnect = dbConnect();
$streamerNamesArray = getStreamerNamesFromDb($dbconnect);
$krakenJson = getJSON($streamerNamesArray);
filterJSON($krakenJson, $dbconnect);














