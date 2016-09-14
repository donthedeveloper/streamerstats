<?php

// ini_set('display_errors', 'On');

require_once('resources/config.php');
require_once('classes/ValidateInput.php');
require_once('classes/Database.php');
require_once('classes/Json.php');

$validateInput = new ValidateInput();
$db = new Database($dbConfig);
$json = new Json();

$error = null;
$success = null;
$duplicate = null;

if ($_POST['streamer']) {
  
  $requiredArray = array('streamer' => TRUE);
  $inputArray = array('streamer' => $_POST['streamer']);
  
  // RUN VALIDATION METHODS //
  
  // check if input is empty
  $validatedEmptyInput = $validateInput->emailIsNotEmpty($inputArray, $requiredArray);
  if ( $validatedEmptyInput['streamer'] ) {
  
    // check if streamer input is alphanumeric
    if ( $validateInput->isAlphaNumeric($_POST['streamer']) ) {
      
      // check if streamer already exists in database
      if ( $db->streamerDoesNotExistInDb($_POST['streamer']) ) {
        
        // get streamer data from twitch api (data placed in private property)
        $json->curlStreamerData($twitchApiUrl, $_POST['streamer'], $twitchClientID);
        // check if streamer exists on twitch
        if ( $json->streamerExistsOnTwitch() ) {
          
          // check if streamer exists in database
          if ( $db->streamerDoesNotExistInDb($_POST['streamer']) ) {
            
            // add them to database
            if ( $db->addStreamerToDatabase($_POST['streamer']) ) {
              $success = TRUE;
            }
            else {
              $error = "Database Error: We were unable to add the streamer name to the database.";
            }
            
          }
          else {
            $duplicate = TRUE;
          }
          
        }
        else {
          $error ="This streamer does not exist on Twitch.";
        }
        
      }
      else {
        $error = "That streamer name is already in our database. We've already started gathering data.";
      }
      
    }
    else {
      $error = "Streamer names must only consist of letters and numbers";
    }
    
  }
  else {
    $error = "Please fill out your streamer name.";
  }
  
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
      <div class="header__container absolute_center">

<?php
        
// BUILD CTA CONTENT
$intro .= "<p class='success'>Enter your streamer name to have us start gathering data from your stream immediately. The more data we gather from your stream, the more we can help you improve.</p>";
        
$errorMessage .= "<p class='error'>$error</p>";
        
$successMessage .= "<h2 class='header__intro'>Thank you!</h2>";   
$successMessage .= "<h2 class='header__date'>Your streamer name has been added.</h2>";
$successMessage .= "<div class='social_cta'>";
$successMessage .= "<ul class='social_cta__nav'>";
$successMessage .= "<li class='social_cta__element'><a rel='canonical' class='share share--twitter clearfix' href='https://twitter.com/intent/tweet?text=Hey%20there!%20Here%20is%20a%20new%20resource%20for%20Twitch%20streamers.%20http://streamerstats.tv' target='_blank'><img class='share' src='http://image.flaticon.com/icons/svg/145/145812.svg'><span class='share'>Tweet</span></a></li>";
$successMessage .= "<li class='social_cta__element'><a class='share share--facebook clearfix' href='https://www.facebook.com/sharer/sharer.php?u=streamerstats.tv' target='_blank'><img class='share' src='http://image.flaticon.com/icons/svg/145/145802.svg'><span class='share'>Share</span></a></li>";
$successMessage .= "</ul>";
$successMessage .= "</div>";
// $successMessage .= "<p class='success'>As an added bonus, did you know that you can <a class='statistics_highlight' href='stats.php'>enter your streamer name</a>? We will start gathering data from your stream immediately. The more data we gather from your stream, the more we can help you improve.</p>";
        
$duplicateMessage .= "<p class='success'>We already have your streamer name. We've already started recording data from your live streams. Check back on launch day!</p>";       
        
$form .= "<form class='signup' method='post' action=''>";
$form .= "<input class='signup__input signup__input--streamer' name='streamer' type='text' placeholder='Enter your streamer name'>";
$form .= "<input class='signup__input signup__input--submit statistics_background' name='submit_streamer' type='submit' value='OK'>";
$form .= "</form>";
        
if (!$_POST['submit_streamer']) {
  $output .= $intro;
}
else {
  
  if ($success) {
    $output .= $successMessage;
  }
  elseif ($error) {
    $output .= $errorMessage;
  }
  elseif ($duplicate) {
    $output .= $duplicateMessage;
  }
  
}
        
if (!$success && !$duplicate) {
  $output .= $form;
}
        
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
    
  </body>
</html>




















