<?php

// FOR SATURDAY
// CREATE TWO MAIN METHODS
  // 1. Loop through datasortedbystream
  // 2. Loop through datasortedbyweekday

// EXECUTE MORE SPECIFIC CALCULATIONS (in the same loop) THAT TAKE ADVANTAGE OF THOSE LOOPS
// (DONT repeatedly loop through each array for each calculation)

class Calculations {
  
//   private $calculationsArray;
  private $dataSortedByStreamArray;
  
  private $numberOfStreamsRecorded;
  private $mostPopularWeekday;
  
  private $avgConcurrentViewersPerWeekDay;
  private $avgConcurrentViewersPerStream;
  private $followersGainedPerStream;
  private $viewsGainedPerStream;
  private $calculationsToRender;
//   private $dataSortedByWeekDay;
  
  // include badges/achievements for streamers that participated on alpha/beta
  
  public function __construct() {
//     $this->calculationsArray = array();
//     $this->storeDataSortedByStream();
  }
  
  private function storeDataSortedByStream($dataSortedByStream) {
    $this->dataSortedByStreamArray = $dataSortedByStream;
  }
  
//   public function getCalculationsArray() {
//     return $this->calculationsArray;
//   }
  
  public function startCalculations($dataSortedByStream) {
    $this->storeDataSortedByStream($dataSortedByStream);
    $this->countStreams();
    // Calculations: 
      // (1) # of streams recorded
    $this->loopThroughStreams();
    // Calculations:
      // (1) most popular weekday
      // (2) average concurrent viewers per stream
      // (3) followers per stream
    // views per stream
    
    $this->buildRenderArray();
  }
  
  
  public function getRenderArray() {
    return $this->calculationsToRender;
  }
  
  
  private function buildRenderArray() {
    $this->calculationsToRender['number_of_streams_recorded'] = $this->numberOfStreamsRecorded;
    
    $this->calculationsToRender['most_popular_weekday'] = $this->mostPopularWeekday;
    
    foreach ($this->avgConcurrentViewersPerStream as $stream => $concurrentViewers) {
      $this->calculationsToRender['per_stream'][$stream]['avg_concurrent_viewers'] = $concurrentViewers;
    }
    
    foreach ($this->viewsGainedPerStream as $stream => $views) {
      $this->calculationsToRender['per_stream'][$stream]['views_gained'] = $views;
    }
    
    foreach ($this->followersGainedPerStream as $stream => $followers) {
      $this->calculationsToRender['per_stream'][$stream]['followers_gained'] = $followers;
    }
    
    echo "<br />";
//     print_r($this->calculationsToRender);
  }
  
  
  private function addUpConcurrentViewersPerStream($row) {
    $streamId = $row['stream_id'];
    $concurrentViewers = $concurrentViewers = $row['stream_viewers'];
    $this->avgConcurrentViewersPerStream[$streamId][] = $concurrentViewers;
  }
  
  
  private function calculateFollowersPerStream($streamArray) {
    $streamId = $streamArray[0]['stream_id'];
    
    $followersArrayKey = 'channel_followers';
    $lastKeyOfStreamArray = count($streamArray) - 1;
    
    $followerCountStart = $streamArray[0][$followersArrayKey];
    $followerCountEnd = $streamArray[$lastKeyOfStreamArray][$followersArrayKey];
    $followersGained = $followerCountEnd - $followerCountStart;
    $this->followersGainedPerStream[$streamId] = $followersGained;
  }
  
  
  private function calculateViewsPerStream($streamArray) {
    $streamId = $streamArray[0]['stream_id'];
    
    $viewsArrayKey = 'channel_views';
    $lastKeyOfStreamArray = count($streamArray) - 1;
    
    $viewsCountStart = $streamArray[0][$viewsArrayKey];
    $viewsCountEnd = $streamArray[$lastKeyOfStreamArray][$viewsArrayKey];
    $viewsGained = $viewsCountEnd - $viewsCountStart;
    $this->viewsGainedPerStream[$streamId] = $viewsGained;
  }
  
  
  private function getAvgConcurrentViewersPerStream() {
    foreach ($this->avgConcurrentViewersPerStream as $stream => $concurrentViewers) {
      // sum up the values and divide by the array count to get the averages
      $avgConcurrentViewers = floor( array_sum($concurrentViewers) / count($concurrentViewers) );
      $this->avgConcurrentViewersPerStream[$stream] = $avgConcurrentViewers;
    }
    
//     echo "Average Concurrent Viewers Per Stream: ";
//     print_r($this->avgConcurrentViewersPerStream);
  }
  
  // number of streams recorded
  private function countStreams() {
//     $this->calculationsArray['streamsRecorded'] = count($this->dataSortedByStream);
    $count = count( $this->dataSortedByStreamArray );
    $this->numberOfStreamsRecorded = $count;
//     echo "Number of streams: $count<br />";
  }
  
  private function loopThroughStreams() {
//     print_r($this->dataSortedByStreamArray);
    foreach ($this->dataSortedByStreamArray as $streamArray) {
//       echo "<h3>New Stream</h3>";
      
      foreach ($streamArray as $row) {
//         echo "<br /><strong>New Row</strong>";
        foreach($row as $key => $value) {
          if ($key == 'db_entry_timestamp' || $key == 'channel_created_at' || $key == 'channel_updated_at' || $key == 'stream_created_at') {
//             $date = strtotime($value);
//             $value = strtotime($value);
//             $value = date('l dS \o\f F Y h:i:s A e', $value);
            
            // DO OTHER CALCULATIONS
//             $this->getAverageConcurrentViewersPerWeekDay($key, $value);
          }
//           echo "<br />$key: $value";
        }
        
        $this->addUpConcurrentViewersPerWeekDay($row);
        $this->addUpConcurrentViewersPerStream($row);
        
      }
      
      $this->calculateFollowersPerStream($streamArray);
      $this->calculateViewsPerStream($streamArray);
    }
    
//     print_r($this->avgConcurrentViewersPerWeekDay);
    $this->getAvgConcurrentViewersPerWeekDay();
    $this->getAvgConcurrentViewersPerStream();
    
    
  }
  
  
  private function getAvgConcurrentViewersPerWeekDay() {
    
//     print_r($this->avgConcurrentViewersPerWeekDay);
    foreach ($this->avgConcurrentViewersPerWeekDay as $weekday => $concurrentViewers) {

      // sum up the values and divide by the array count to get the averages
      $avgConcurrentViewers = floor( array_sum($concurrentViewers) / count($concurrentViewers) );
      $this->avgConcurrentViewersPerWeekDay[$weekday] = $avgConcurrentViewers;
     
    }
    
    $popularWeekdays = array_keys( $this->avgConcurrentViewersPerWeekDay, max($this->avgConcurrentViewersPerWeekDay) );
    
    $this->mostPopularWeekday = $popularWeekdays[0];
    
//     echo "Most popular weekday: $popularWeekdays[0]<br />";
  }
  
  
  private function addUpConcurrentViewersPerWeekDay($row) {
    
    $currentTimeStamp = strtotime( $row['db_entry_timestamp'] );
    $dayOfWeek = date('l', $currentTimeStamp);
    $concurrentViewers = $row['stream_viewers'];


    switch ($dayOfWeek) {
      case "Monday":
        $this->avgConcurrentViewersPerWeekDay['Monday'][] = $concurrentViewers;
        break;
      case "Tuesday":
        $this->avgConcurrentViewersPerWeekDay['Tuesday'][] = $concurrentViewers;
        break;
      case "Wednesday":
        $this->avgConcurrentViewersPerWeekDay['Wednesday'][] = $concurrentViewers;
        break;
      case "Thursday":
        $this->avgConcurrentViewersPerWeekDay['Thursday'][] = $concurrentViewers;
        break;
      case "Friday":
        $this->avgConcurrentViewersPerWeekDay['Friday'][] = $concurrentViewers;
        break;
      case "Saturday":
        $this->avgConcurrentViewersPerWeekDay['Saturday'][] = $concurrentViewers;
        break;
      case "Sunday":
        $this->avgConcurrentViewersPerWeekDay['Sunday'][] = $concurrentViewers;
        break;     
    }
    
//     $this->avgConcurrentViewersPerWeekDay;
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