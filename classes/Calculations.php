<?php

// FOR SATURDAY
// CREATE TWO MAIN METHODS
  // 1. Loop through datasortedbystream
  // 2. Loop through datasortedbyweekday

// EXECUTE MORE SPECIFIC CALCULATIONS (in the same loop) THAT TAKE ADVANTAGE OF THOSE LOOPS
// (DONT repeatedly loop through each array for each calculation)

class Calculations {
  
  private $calculationsArray;
  private $dataSortedByStream;
  private $dataSortedByWeekDay;
  
  // include badges/achievements for streamers that participated on alpha/beta
  
  public function __construct() {
    $this->calculationsArray = array();
  }
  
  public function getCalculationsArray() {
    return $this->calculationsArray;
  }
  
  public function startCalculations($dataSortedByStream, $dataSortedByWeekDay) {
    $this->dataSortedByStream = $dataSortedByStream;
    $this->dataSortedByWeekDay = $dataSortedByWeekDay;
    
    $this->countStreams();
    $this->avgConcurrentViewersPerWeekDay();
  }
  
  // number of streams recorded
  private function countStreams() {
    $this->calculationsArray['streamsRecorded'] = count($this->dataSortedByStream);
  }
  
  private function avgConcurrentViewersPerWeekDay() {
  // avg concurrent viewers per week day
    
    foreach($this->dataSortedByWeekDay as $weekDay => $rows) {
      $rowCount = count($rows);
      
      for ($i = 0; $i < $rowCount; $i++) {
        if ($i === 0) {
          $avgConcurrentViewers = $rows[$i]['viewers'];
        }
        elseif ($i > 0 && $i < $rowCount) {
          $avgConcurrentViewers += $rows[$i]['viewers'];
        }
      }
      
      $this->calculationsArray['concurrentViewersPerWeek'][$weekDay] = floor($avgConcurrentViewers / $rowCount);
    }
    
    print_r($this->calculationsArray['concurrentViewersPerWeek']);
    
  }
  
  // (*) week day with highest average concurrent viewers
  
  // game with highest concurrent viewers
  
  // when did streamer have the most viewers
  
  // avg new followers
  
  // * avg concurrent viewers per time streaming per stream (know exponential gains/losses based on how long you stream)
  
  // * is it worth it streaming 20 hours vs 40 hours
  
  // unique visitors
  
  
  
  
  // bonus features
  
  // *  how active is chat
  
  // * avg lifespan of viewers (know how long streamer has to capture viewers attention)
  //   http://tmi.twitch.tv/group/user/donthedeveloper/chatters
}