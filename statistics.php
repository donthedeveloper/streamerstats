<?php

ini_set('display_errors', 1);
error_reporting(E_ERROR);

include('resources/config.php');
include('classes/Database.php');
include('classes/DataSplit.php');
include('classes/Calculations.php');

$db = new Database($dbConfig);
$data = new DataSplit();
$calculations = new Calculations();

if ($_POST['streamer_name']) {
  $streamerName = $_POST['streamer_name'];
}
// remove after test
$streamerName = 'solaarnoble';

// Check if streamer exists in database
if ( $db->streamerExistsInDb($streamerName) ) {
  // Check if streamer has data in database
  if ($db->streamerHasDataInDb($streamerName) ) {
    
    // Get streamer data from database
    $pdo = $db->getStreamerData();
    $data->loopThroughPdo($pdo);
    
    // Transfer pdo data to practical arrays for calculations
    $dataSortedByStream = $data->getSeparateStreamsArray();
    
    $calculations->startCalculations($dataSortedByStream);
    
    print_r( $calculations->getRenderArray() );
    
  }
  else {
//     echo "Streamer Data: " . $streamerData;
//     $streamerData = $data->pdoToArrays($streamerData);
    
    // If streamer has data, return it

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
      <h2 class="streamer_header">DonTheDeveloper</h2>
      
      <div class="columns_container clearfix">
        <div class="column column--left">
          <span class="column__number statistics__stat--highlight">7</span>
          <hr class="column__hr">
          Streams Recorded
        </div>
        <div class="column column--right">
          <span class="column__number statistics__stat--highlight">33%</span>
          <hr class="column__hr">
          Growth
        </div>
      </div>
      
      <figure class="chart_container">
        <figcaption class="chart_container__caption"><span class="hidden">Average concurrent viewers in </span>Your last 7 streams</figcaption>
        <ul class="chart">  
          <li class="chart__element chart__element--one"><span class="date">7/15</span><span class="hidden">: 40</span></li>
          <li class="chart__element chart__element--two"><span class="date">7/17</span><span class="hidden">: 54</span></li>
          <li class="chart__element chart__element--three"><span class="date">7/18</span><span class="hidden">: 62</span></li>
          <li class="chart__element chart__element--four"><span class="date">7/19</span><span class="hidden">: 56</span></li>
          <li class="chart__element chart__element--five"><span class="date">7/21</span><span class="hidden">: 66</span></li>
          <li class="chart__element chart__element--highlight chart__element--six"><span class="date">7/25</span><span class="hidden">: 70</span></li>
          <li class="chart__element chart__element--seven"><span class="date">7/28</span><span class="hidden">: 74</span></li>
        </ul>
      </figure>
      
      <ul>
        <li class="statistics__stat">Your most popular day of the week is <span class="statistics__stat--highlight">Saturday</span>.</li>
        <li class="statistics__stat">Your most popular game is <span class="statistics__stat--highlight">Creative</span>.</li>
      </ul>
 
      
    </header>
  </body>
</html>