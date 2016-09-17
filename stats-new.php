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

function buildStatisticsOutput($numberOfStreams) {
  $statistics .= "<div class='header__container'>";
  $statistics .= "<h2 class='streamer_header'>" . $_GET['streamer'] . "</h2>";
  $statistics .= "<div class='clearfix'>";
  $statistics .= "  <div class='column column--left'>";
  $statistics .= "    <span class='column__number statistics_highlight'>" . $numberOfStreams . "</span>";
  $statistics .= "    <hr class='column__hr'>";
  $statistics .= "    Streams Recorded";
  $statistics .= "  </div>";
  $statistics .= "  <div class='column column--right'>";
  $statistics .= "    <span class='column__number statistics_highlight'>n/a</span>";
  $statistics .= "    <hr class='column__hr'>";
  $statistics .= "    Growth";
  $statistics .= "  </div>";
  $statistics .= "</div>";
  
  $statistics .= "<div id='columnchart_values'></div>";
  
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
          }
          else {
            $error = "Database Error: We were unable to add the streamer name to the database.";
            $output .= "<p class='error'>$error</p>";
            $output .= $form;
          }          
        }
        else {
          $error = "That streamer doesn't exist on Twitch yet. Please enter an active Twitch username.";
          $output .= $centerContent;
          $output .= "<p class='error'>$error</p>";
          $output .= $form;
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
        }
      }
    }
    else {
      $error = "Please enter a valid Twitch name.";
      $output .= $centerContent;
      $output .= "<p class='error'>$error</p>";
      $output .= $form;
    }
  }
  else {
    $error = "Please fill out the streamer name.";
    $output .= $centerContent;
    $output .= "<p class='error'>$error</p>";
    $output .= $form;
  }
}
else {
  $output .= $centerContent;
  $output .= $intro;
  $output .= $form;
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
      
<!--       <div class="header__container absolute_center"> -->

<?php
        
// BUILD CTA CONTENT       
// $errorMessage .= "<p class='error'>$error</p>";
        
// $successMessage .= "<h2 class='header__intro'>Thank you!</h2>";   
// $successMessage .= "<h2 class='header__date'>Your streamer name has been added.</h2>";
// $successMessage .= "<div class='social_cta'>";
// $successMessage .= "<ul class='social_cta__nav'>";
// $successMessage .= "<li class='social_cta__element'><a rel='canonical' class='share share--twitter clearfix' href='https://twitter.com/intent/tweet?text=Hey%20there!%20Here%20is%20a%20new%20resource%20for%20Twitch%20streamers.%20http://streamerstats.tv' target='_blank'><img class='share' src='http://image.flaticon.com/icons/svg/145/145812.svg'><span class='share'>Tweet</span></a></li>";
// $successMessage .= "<li class='social_cta__element'><a class='share share--facebook clearfix' href='https://www.facebook.com/sharer/sharer.php?u=streamerstats.tv' target='_blank'><img class='share' src='http://image.flaticon.com/icons/svg/145/145802.svg'><span class='share'>Share</span></a></li>";
// $successMessage .= "</ul>";
// $successMessage .= "</div>";
        
echo $output;
        
// END CTA CONTENT

?>
<!--               <div class="social_cta">
        <ul class="social_cta__nav">
          <li class="social_cta__element"><a rel="canonical" class="share share--twitter clearfix" href="https://twitter.com/intent/tweet?text=Hey%20there!%20Here's%20a%20new%20resource%20for%20Twitch%20streamers.%20http://streamerstats.tv" target="_blank"><img class="share" src="http://image.flaticon.com/icons/svg/145/145812.svg"><span class="share">Tweet</span></a></li> -->
<!--       <li class="social_cta__element"><a class="share share--facebook clearfix" href="https://www.facebook.com/sharer/sharer.php?u=streamerstats.tv" target="_blank"><img class="share" src="http://image.flaticon.com/icons/svg/145/145802.svg"><span class="share">Share</span></a></li> -->
<!--         </ul>
      </div> -->
        
      </div>
      
      <div class="container2 up-to-tablet--hidden">
        <img class="smartphone-img" src="img/smartphone.png">
      </div>
    </header>
    <script type="text/javascript">
      var calculationsToRender = <?php echo json_encode($renderArray['per_stream']) ?>;
    </script>
    
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="js/google-chart.js"></script>
    
  </body>
</html>



