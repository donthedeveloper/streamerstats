<?php

// ini_set('display_errors', 'On');

include('resources/config.php');
include('classes/Database.php');
include('classes/DataSplit.php');
include('classes/Calculations.php');

$db = new Database($dbConfig);
$data = new DataSplit();

if ($_POST['streamer_name']) {
  $streamerName = $_POST['streamer_name'];
}
// remove after test
$streamerName = 'donthedeveloper';

// Check if streamer exists in database
if ($db->streamerExistsinDB($streamerName)) {
  // Check if streamer has data
  $streamerData = $db->streamerGetData($streamerName);
  if (!$streamerData) {
    // If streamer DOES NOT have data, return error message (true if streamer was JUST entered or streamer has not been online since entered)
  }
  else {
    // If streamer has data, return it
    $data->pdoToArrays($streamerData);
//     foreach($data->getMultipleStreamsArray() as $array) {
//       print_r($array);
//       echo "<br /><br />";
//     }
//     echo count($data->getMultipleStreamsArray());
  }
}
// If streamer DOES NOT exist in database
  // Check if streamer exists on Twitch
    // If streamer exists on Twitch, add them to database and return message (to wait a few minutes)
    // If streamer DOES NOT EXIST on Twitch, return error message

function getJSON($streamerName) {
  // create new cURL resource
  $curl = curl_init();
  
  // set parameters
  curl_setopt($curl, CURLOPT_URL, "https://api.twitch.tv/kraken/streams/" . $streamerName);
  curl_setopt($curl, CURLOPT_HEADER, 0);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
  
  // grab url and pass it to the browser
  $json = curl_exec($curl);
  $json = json_decode($json);
  
  // close cURL resource ( free up resources )
  curl_close($curl);
  
  return $json;
}

?>

<!DOCTYPE>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" type="text/css" href="https://necolas.github.io/normalize.css/3.0.2/normalize.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Lato:400,700' rel='stylesheet' type='text/css'>
  </head>
  <body>
    <header>
      <h1 class="logo">Streamer<span class="statistics__stat--highlight">Stats</span></h1>
      
      <p class="slogan">We are driven to help you get your Twitch partnership.</p>
      
<!--       <ul class="statistics">
        <p class="statistics__intro">Did you know,</p>
        <li class="statistics__stat">You have about <span class="statistics__stat--highlight">5 minutes</span> to capture a new user's attention.</li>
        <li class="statistics__stat">Streamers usually see the most viewers around <span class="statistics__stat--highlight">3 hours</span> into the stream.</li>
      </ul> -->
      
<!--       <p class="statistics__stat">We have recorded <span class="statistics__stat--highlight">7689</span> streams.</p> -->
      <p class="statistics__stat">Built by streamers, this web app lets growing streamers, like you, know when and why you are capturing your viewers' attention.</p>
      
      <p class="signup__intro">Give it a try!</p>
      
      <form class="signup">
        <input class="signup__input signup__input--streamer" class="streamer_name" type="text" placeholder="DonTheDeveloper">
        <input class="signup__input signup__input--submit" class="submit"  type="button" value="LOOKUP">
      </form>
    </header>
    
<!--     <iframe src="https://www.twitch.tv/donthedeveloper/chat?popout=" frameborder="0" scrolling="no" height="500" width="350"></iframe> -->
  </body>
</html>













