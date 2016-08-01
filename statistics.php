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
    $streamerData = $data->pdoToArrays($streamerData);
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
      
<!--       Idea one - generate chart__element:after height % with php - inline -->
<!--       Idea two - turn this into a definition list? -->
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
      
<!--       <p class="signup__intro--two">We already have dozens of recommendations for you. Signup and unlock everything!</p> -->
<!--       <p class="signup__intro">Signup and unlock everything!</p> -->
  
<!--       <form class="signup">
        <input class="signup__input signup__input--streamer" class="streamer_name" type="text" placeholder="Email">
        <input class="signup__input signup__input--submit" class="submit"  type="button" value="Sign Up">
      </form> -->
      
<!--       <table class="chart">
        <caption class="chart__caption">Your last 7 streams</caption>
        <tr>
          <td>25</td>
          <td>27</td>
          <td>32</td>
          <td>28</td>
          <td>33</td>
          <td>35</td>
          <td>37</td>
        </tr>
        <tr>
          <th>7/15</th>
          <th>7/17</th>
          <th>7/18</th>
          <th>7/19</th>
          <th>7/21</th>
          <th>7/25</th>
          <th>7/28</th>
        </tr>
      </table> -->
      
<!--       <p class="slogan">We are driven to help you get your Twitch partnership.</p> -->
      
      <ul class="statistics alternate">
<!--         <p class="statistics__intro">Your most popular,</p> -->
        <li class="statistics__stat">Your most popular day of the week is <span class="statistics__stat--highlight">Saturday</span>.</li>
        <li class="statistics__stat">Your most popular game is <span class="statistics__stat--highlight">Creative</span>.</li>
      </ul>
      
<!--       <ul class="statistics">
        <p class="statistics__intro">Each stream,</p>
        <li class="statistics__stat">You gain about <span class="statistics__stat--highlight">12</span> followers each stream.</li>
        <li class="statistics__stat">You have about <span class="statistics__stat--highlight">5 minutes</span> to capture a new user's attention.</li>
        <li class="statistics__stat">You see the most viewers around <span class="statistics__stat--highlight">3 hours</span> into your stream.</li>
        <li class="statistics__stat">We have recorded <span class="statistics__stat--highlight">34</span> of your streams.</li>
        <li class="statistics__stat">You have an average of <span class="statistics__stat--highlight">100</span> visitors each stream.</li>
        <li class="statistics__stat">The most viewers you ever had was on <span class="statistics__stat--highlight">6</span> people that visit each stream.</li>
      </ul> -->
      
<!--       CTA Message with highlighted statistics(not actually calculated) "Just participating in the beta unlocks these bonus statistics" -->
<!--       Bonus section will be replaced with actualy statistics) if streamer is participating in beta -->
      
<!--       <p class="signup__intro">Give it a try!</p>
      
      <form class="signup">
        <input class="signup__input signup__input--streamer" class="streamer_name" type="text" placeholder="DonTheDeveloper">
        <input class="signup__input signup__input--submit" class="submit"  type="button" value="LOOKUP">
      </form> -->
      
    </header>
    
<!--     <div>
      <ul class="statistics">
        <p class="statistics__intro">Did you know,</p>
        <li class="statistics__stat">You have about <span class="statistics__stat--highlight">5 minutes</span> to capture a new user's attention.</li>
        <li class="statistics__stat">Streamers usually see the most viewers around <span class="statistics__stat--highlight">3 hours</span> into the stream.</li>
      </ul>
    </div> -->
    
<!--     <iframe src="https://www.twitch.tv/donthedeveloper/chat?popout=" frameborder="0" scrolling="no" height="500" width="350"></iframe> -->
  </body>
</html>