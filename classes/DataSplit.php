<?php

class DataSplit {
  
//   private $singleStreamArray;
//   private $multipleStreamsArray;
//   private $sortedByWeekDayArray;
  private $separateStreamsArray;
  
  public function __construct() {
//     $this->singleStreamArray = array();
//     $this->multipleStreamsArray = array();
//     $this->sortedByWeekDayArray = array();
    $this->separateStreamsArray = array();
  }
  
  
//   public function getMultipleStreamsArray() {
//     return $this->multipleStreamsArray;
//   }
  
  public function getSeparateStreamsArray() {
    return $this->separateStreamsArray;
  }
  
  
//   public function countNumberOfStreams($pdo) {
//     $rowCount = $pdo->rowCount();
    
    
// //     echo "Number of streams: $rowCount<br />";
//   }
  
  public function loopThroughPdo($pdo) { 
    $rowCount = $pdo->rowCount();
    
//     echo "Number of rows: $rowCount";
    
    for ($i = 1; $i <= $rowCount; $i++) {
      $row = $pdo->fetch(PDO::FETCH_ASSOC);
      
      $streamStartedAt = date("g", strtotime($row['stream_created_at']) );
      $enteredAt = date("g", strtotime($row['db_entry_timestamp']) );
//       echo "Stream started at: $streamStartedAt<br />";
//       echo "Stream entered at: $enteredAt<br />";
//       echo "<br /><br />";
      
      // Separate by stream
      $this->buildSeparateStreamsArray($i, $row, $rowCount);
      
//       //separate by weekday
//       $this->addToWeekDayArray($i, $row, $rowCount);
    }
    
//     echo "<br />Number of streams: " . count($this->separateStreamsArray);
  }
  
  
//   private function addToWeekDayArray($i, $row, $rowCount) {
//     $currentTimeStamp = strtotime($row['timestamp']);
//     $dayOfWeek = date('l', $currentTimeStamp);
    
//     switch ($dayOfWeek) {
//       case "Monday":
//         $this->sortedByWeekDayArray['Monday'][] = $row;
//         break;
//       case "Tuesday":
//         $this->sortedByWeekDayArray['Tuesday'][] = $row;
//         break;
//       case "Wednesday":
//         $this->sortedByWeekDayArray['Wednesday'][] = $row;
//         break;
//       case "Thursday":
//         $this->sortedByWeekDayArray['Thursday'][] = $row;
//         break;
//       case "Friday":
//         $this->sortedByWeekDayArray['Friday'][] = $row;
//         break;
//       case "Saturday":
//         $this->sortedByWeekDayArray['Saturday'][] = $row;
//         break;
//       case "Sunday":
//         $this->sortedByWeekDayArray['Sunday'][] = $row;
//         break;     
//     }
    
//   }
  
  
  private function buildSeparateStreamsArray($i, $row, $rowCount) {
//     $lastArrayKey = $i - 2;
    
//     $lastMultidimensionalArrayKey = count($this->separateStreamsArray) - 1;
//     $currentMultidimensionalArrayKey = count($this->separateStreamsArray);
    
    if ($i === 1) {
      // first entry
//       enter into stream array
      $this->separateStreamsArray[0][] = $row;
    }
    elseif ($i === $rowCount) {
      //last entry
//       $this->separateStreamsArray[0] = $row;
    }
    else {
      // middle entry - figure out if its same or new stream
//       print_r($this->separateStreamsArray[$lastArrayKey]);
      //look for change in streams by stream_id
      $this->checkForDifferentStreamId($i, $lastArrayKey, $lastMultidimensionalArrayKey, $currentMultidimensionalArrayKey, $row);
    }
  }
  
  private function checkForDifferentStreamId($i, $lastArrayKey, $lastMultidimensionalArrayKey, $currentMultidimensionalArrayKey, $row) {
//     $lastArrayKey = $i - 2;
    
    
    $lastMultidimensionalArrayKey = count($this->separateStreamsArray) - 1;
    
    if ($lastArrayKey == 0) {
      $currentMultidimensionalArrayKey = count($this->separateStreamsArray);
    }
    else {
      $currentMultidimensionalArrayKey = $lastMultidimensionalArrayKey;
    }
    
    $lastArrayKey = count($this->separateStreamsArray[$lastMultidimensionalArrayKey]) - 1;
    
//     print_r($this->separateStreamsArray);
    
//     echo "Last Array Key: $lastArrayKey<br />";
//     echo "Last Multidimensional Array Key: $lastMultidimensionalArrayKey<br />";
//     echo "Current Multidimensional Array Key: $currentMultidimensionalArrayKey<br />";
//     echo "Current Stream ID: " . $row['stream_id'] . "<br />";
//     echo "Last Stream ID: " . $this->separateStreamsArray[$lastMultidimensionalArrayKey][$lastArrayKey]['stream_id'] . "<br />";
    
    // if it IS DATA FROM THE SAME STREAM
    if ($row['stream_id'] === $this->separateStreamsArray[$lastMultidimensionalArrayKey][$lastArrayKey]['stream_id']) { // this is wrong
      $this->separateStreamsArray[$lastMultidimensionalArrayKey][] = $row;
    }
    else {
//       echo "<br /><br />";
      $this->separateStreamsArray[$currentMultidimensionalArrayKey][] = $row;
      $lastArrayKey = 0;
    }
    
//     echo "<br />Last stream ID: " . $this->separateStreamsArray[$lastMultidimensionalArrayKey][$lastArrayKey]['stream_id'];
//     echo "<br />This stream ID: " . $row['stream_id'];
  }
  
  // time difference of 30 minutes between entries indicuates new stream
//   private function calculateTimeDifference($i, $row) {
//     $currentTimeStamp = strtotime($row['timestamp']);
    
//     // if there is an entry in singlestreamarray
//     if ($this->singleStreamArray) {
//       $lastTimeStamp = strtotime($this->singleStreamArray[count($this->singleStreamArray) - 1]['timestamp']);
//       $timeDifference = $currentTimeStamp - $lastTimeStamp;
//     }
//     else {
//       $lastTimeStamp = null;
//       $timeDifference = null;
//     }
    
//     // return ($timeDifference >= 3000);
//     if ($timeDifference >= 3000) {
//       return true;
//     }
//     else {
//       return false;
//     }
//   }
  
  
//   private function addToMultipleStreamsArray() {
//     $this->multipleStreamsArray[] = $this->singleStreamArray;
//     // clear singlestreamsarray
//     $this->singleStreamArray = array();
//   }
  
}

















