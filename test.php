<?php

// ini_set('display_errors', 'On');

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
    $multipleStreamsArray = $data->getMultipleStreamsArray();
    $sortedByWeekDayArray = $data->getSortedByWeekDayArray();
    
    $calculations->startCalculations($multipleStreamsArray, $sortedByWeekDayArray);
    
    // testing   
//     print_r( $calculations->getCalculationsArray() );
    
//     echo "<br /><br />";
    
//     print_r($streamerSplitData);
  }
}