<?php

ini_set('display_errors', 1);
error_reporting(E_ERROR);

include('resources/config.php');
include('classes/Database.php');
include('classes/DataSplit.php');
include('classes/Calculations.php');
include('classes/ValidateInput.php');
include('classes/Json.php');

$db = new Database($dbConfig);
$data = new DataSplit();
$calculations = new Calculations();
$validateInput = new ValidateInput();
$json = new Json();

// OUTPUT
$form .= "<form class='signup' method='get' action=''>";
$form .= "<input class='signup__input signup__input--streamer' name='streamer' type='text' placeholder='Enter your streamer name'>";
$form .= "<input class='signup__input signup__input--submit statistics_background' type='submit' value='OK'>";
$form .= "</form>";

$intro .= "<p class='success'>We use your streaming data to build <i>personalized</i> strategies that help <i>you</i> grow <i>your</i> stream.</p>";

$centerContent .= "<div class='header__container absolute_center'>";
$endCenterContent .= "</div>";

$backgroundImage .= "<div class='container2 up-to-tablet--hidden'>";
$backgroundImage .= "<img class='smartphone-img' src='img/smartphone.png'>";
$backgroundImage .= "</div>";

function buildStatisticsOutput($numberOfStreams) {
  $statistics .= "<div class='header__container statistics__container'>";
  $statistics .= "  <h2 class='streamer_header'>" . $_GET['streamer'] . "</h2>";
  $statistics .= "  <div class='clearfix'>";
  $statistics .= "    <div class='column column--left'>";
  $statistics .= "      <span class='column__number statistics_highlight'>" . $numberOfStreams . "</span>";
  $statistics .= "      <hr class='column__hr'>";
  $statistics .= "      Streams Recorded";
  $statistics .= "    </div>";
  $statistics .= "    <div class='column column--right'>";
  $statistics .= "      <span class='column__number statistics_highlight'>n/a</span>";
  $statistics .= "      <hr class='column__hr'>";
  $statistics .= "      Growth";
  $statistics .= "    </div>";
  $statistics .= "  </div>";
  $statistics .= "</div>";
  
  $statistics .= "<ul class='chart__navigation statistics__container'>";
  $statistics .= "  <li><button id='avg-concurrent-viewers' class='selected' type='button'>Concurrent Viewers</button></li>";
  $statistics .= "  <li><button id='followers-gained' type='button'>Followers Gained</button></li>";
  $statistics .= "  <li><button id='views-gained' type='button'>Views Gained</button></li>";
  $statistics .= "</ul>";
  $statistics .= "<div id='columnchart_values' class='chart statistics__container'></div>";
  
  return $statistics;
}
// /OUTPUT

if ( $_GET['streamer'] ) {
  // check if input IS NOT EMPTY
  $requiredArray = array('streamer' => TRUE);
  $inputArray = array('streamer' => $_GET['streamer']);
  $validatedEmptyInput = $validateInput->inputIsNotEmpty($inputArray, $requiredArray);
  
  if ( $validatedEmptyInput['streamer'] ) {
    // check if streamer input IS ALPHANUMERIC
    if ( $validateInput->isAlphaNumeric($_GET['streamer']) ) {
      // check if streamer DOES NOT EXIST in database
      if ( $db->streamerDoesNotExistInDb($_GET['streamer']) ) {
        // Check if streamer exists on twitch - get streamer data from twitch api (data placed in private property)
        $json->curlStreamerData($twitchApiUrl, $_GET['streamer'], $twitchClientID);
        if ( $json->streamerExistsOnTwitch() ) {
          // add them to database
          if ( $db->addStreamerToDatabase($_GET['streamer']) ) {
            $success = "That Twitch streamer has been added to our database! Please check back when we've recorded at least 1 full stream for this user.";
            $output .= $centerContent;
            $output .= "<p class='success'>$success</p>";
            $output .= $form;
            $output .= $endCenterContent;
            $output .= $backgroundImage;
          }
          else {
            $error = "Database Error: We were unable to add the streamer name to the database.";
            $output .= "<p class='error'>$error</p>";
            $output .= $form;
            $output .= $endCenterContent;
            $output .= $backgroundImage;
          }          
        }
        else {
          $error = "That streamer doesn't exist on Twitch yet. Please enter an active Twitch username.";
          $output .= $centerContent;
          $output .= "<p class='error'>$error</p>";
          $output .= $form;
          $output .= $endCenterContent;
          $output .= $backgroundImage;
        }
      }
      else {
        // check if streamer has data
        if ( $db->streamerHasDataInDb($_GET['streamer']) ) {
          // Get streamer data from database
          $pdo = $db->getStreamerData();
          $data->loopThroughPdo($pdo);

          // Transfer pdo data to practical arrays for calculations
          $dataSortedByStream = $data->getSeparateStreamsArray();

          $calculations->startCalculations($dataSortedByStream);

          $renderArray = $calculations->getRenderArray();
//           $renderArray = $renderArray['per_stream'];
//           print_r($renderArray);
          
          // build output
          $output .= buildStatisticsOutput($renderArray['number_of_streams_recorded']);
        }
        else {
          $error = "This user was added recently. Please check back when we've recorded at least 1 full stream for this user.";
          $output .= $centerContent;
          $output .= "<p class='success'>$error</p>";
          $output .= $form;
          $output .= $backgroundImage;
          $output .= $endCenterContent;
        }
      }
    }
    else {
      $error = "Please enter a valid Twitch name.";
      $output .= $centerContent;
      $output .= "<p class='error'>$error</p>";
      $output .= $form;
      $output .= $endCenterContent;
      $output .= $backgroundImage;
    }
  }
  else {
    $error = "Please fill out the streamer name.";
    $output .= $centerContent;
    $output .= "<p class='error'>$error</p>";
    $output .= $form;
    $output .= $endCenterContent;
    $output .= $backgroundImage;
  }
}
else {
  $output .= $centerContent;
  $output .= $intro;
  $output .= $form;
  $output .= $endCenterContent;
  $output .= $backgroundImage;
}

?>

<!DOCTYPE>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" type="text/css" href="https://necolas.github.io/normalize.css/3.0.2/normalize.css">
    <link rel="stylesheet" type="text/css" href="css/landing.css">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Lato:400,700' rel='stylesheet' type='text/css'>
  </head>
  
  <body>
    <header>
      <a class="logo" href="index.php"><h1 class="header__logo">Streamer<span class="statistics_highlight">Stats</span></h1></a>
      <?php echo $output; ?>
    </header>
    <script type="text/javascript">
      var calculationsToRender = <?php echo json_encode($renderArray['per_stream']) ?>;
    </script>

<!--     <div class="social_container clearfix">
      <p class="social_slogan up-to-tablet--hidden">Built By Streamers, For Streamers.</p>
      <ul class="social_nav">
        <li class="social_nav__element"><a href="http://www.twitter.com/streamerstatstv" target="_blank"><img class="social__icon" src="http://image.flaticon.com/icons/svg/145/145812.svg"></a></li>
        <li class="social_nav__element"><a href="https://www.facebook.com/streamerstatstv/" target="_blank"><img class="social__icon" src="http://image.flaticon.com/icons/svg/145/145802.svg"></a></li>
        <li class="social_nav__element"><a href="mailto:don@streamerstats.tv"><img class="social__icon" src="http://image.flaticon.com/icons/svg/9/9556.svg"></a></li>
      </ul>
    </div>     -->
    
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="js/google-chart.js"></script>
    <script type="text/javascript" src="js/scripts.js"></script>
    
  </body>
</html>



