<?php

class DataSplit {
  
  private $singleStreamArray;
  private $multipleStreamsArray;
  private $sortedByWeekDayArray;
  
  public function __construct() {
    $this->singleStreamArray = array();
    $this->multipleStreamsArray = array();
    $this->sortedByWeekDayArray = array();
  }
  
  
  public function getMultipleStreamsArray() {
    return $this->multipleStreamsArray;
  }
  
  public function getSortedByWeekDayArray() {
    return $this->sortedByWeekDayArray;
  }
  
  
  public function pdoToArrays($pdo) { 
    $rowCount = $pdo->rowCount();
    
    for ($i = 0; $i <= $rowCount; $i++) {
      $row = $pdo->fetch(PDO::FETCH_ASSOC);
      //separate by stream
      $this->addToStreamArrays($i, $row, $rowCount);
      //separate by weekday
      $this->addToWeekDayArray($i, $row, $rowCount);
    }
  }
  
  
  private function addToWeekDayArray($i, $row, $rowCount) {
    $currentTimeStamp = strtotime($row['timestamp']);
    $dayOfWeek = date('l', $currentTimeStamp);
    
    switch ($dayOfWeek) {
      case "Monday":
        $this->sortedByWeekDayArray['Monday'][] = $row;
        break;
      case "Tuesday":
        $this->sortedByWeekDayArray['Tuesday'][] = $row;
        break;
      case "Wednesday":
        $this->sortedByWeekDayArray['Wednesday'][] = $row;
        break;
      case "Thursday":
        $this->sortedByWeekDayArray['Thursday'][] = $row;
        break;
      case "Friday":
        $this->sortedByWeekDayArray['Friday'][] = $row;
        break;
      case "Saturday":
        $this->sortedByWeekDayArray['Saturday'][] = $row;
        break;
      case "Sunday":
        $this->sortedByWeekDayArray['Sunday'][] = $row;
        break;     
    }
    
  }
  
  
  private function addToStreamArrays($i, $row, $rowCount) {
    if ($i === 0) {
      // first entry
      $this->singleStreamArray[] = $row;
    }
    elseif ($i === $rowCount) {
      //last entry
      $this->singleStreamArray[] = $row;
      
      // push to multiple streams array
      $this->addToMultipleStreamsArray();
    }
    else {
      // middle entry - figure out if its same or new stream
      if ($this->calculateTimeDifference($i, $row)) {
        // there is a big enough time difference - this is a new stream (push to multiple streams array)
        $this->addToMultipleStreamsArray();
      }
      else {
        // there is no significant time different - this data is from the same stream
        $this->singleStreamArray[] = $row;
      }
    }
  }
  
  // time difference of 30 minutes between entries indicuates new stream
  private function calculateTimeDifference($i, $row) {
    $currentTimeStamp = strtotime($row['timestamp']);
    
    // if there is an entry in singlestreamarray
    if ($this->singleStreamArray) {
      $lastTimeStamp = strtotime($this->singleStreamArray[count($this->singleStreamArray) - 1]['timestamp']);
      $timeDifference = $currentTimeStamp - $lastTimeStamp;
    }
    else {
      $lastTimeStamp = null;
      $timeDifference = null;
    }
    
//     echo "$i. Current Timestamp: $currentTimeStamp / Last Timestamp: $lastTimeStamp / Time Difference: $timeDifference<br /><br />";
    
    // return ($timeDifference >= 3000);
    if ($timeDifference >= 3000) {
      return true;
    }
    else {
      return false;
    }
  }
  
  
  private function addToMultipleStreamsArray() {
    $this->multipleStreamsArray[] = $this->singleStreamArray;
    // clear singlestreamsarray
    $this->singleStreamArray = array();
  }
  
}

















