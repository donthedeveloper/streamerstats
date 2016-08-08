<?php

ini_set('display_errors', 'On');

include('resources/config.php');
include('classes/ValidateInput.php');
include('classes/Database.php');
$validateInput = new ValidateInput();
$db = new Database($dbConfig);

if ($_POST['submit_email']) {

  $requiredArray = array('email' => true);
  $inputArray = array('email' => $_POST['email']);
  
  // RUN VALIDATION METHODS
  $validatedEmptyInput = $validateInput->validateEmptyInput($inputArray, $requiredArray);
  
  if ($validatedEmptyInput['email']) {
    
    if ($validatedEmptyInput) {
//       SUBMIT EMAIL TO DATABASE
      if ( $db->addEmailToDatabase($_POST['email']) ) {
        $error = "Your email has been added successfully.";
      }
      else {
        $error = "Sorry, we had a database error.";
      }
      
    }
    else {
      $error = "Please enter a valid email address.";
    }
    
  }
  else {
    $error = "Please fill out your email.";
  }  
  
}


if ( $_POST['streamer_name'] ) {
  $streamerName = ctype_alnum( $_POST['streamer_name'] );
  
  // if streamer name is valid
  if ($streamerName) {
    
    $streamerName = $_POST['streamer_name'];
    
    // if streamer name already exists in database
    if ( $db->streamerGetData($streamerName) ) {
      $error = "The streamer name $streamerName already exists in our database.";
    }
    else {
      // create new cURL resource
      $curl = curl_init();

      // set parameters
      curl_setopt($curl, CURLOPT_URL, "https://api.twitch.tv/kraken/streams/$streamerName");
      curl_setopt($curl, CURLOPT_HEADER, "Client-ID: $twitchClientID");
//       curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

      // grab url and pass it to the browser
      $json = curl_exec($curl);
      $json = json_decode($json);

      // close cURL resource (free up resources)
      curl_close($curl);

      if (property_exists($json, "stream")) {
        
        if ( $db->addStreamerToDatabase($streamerName) ) {
          echo $error = "Streamer name $streamerName was added.";
        }
        else {
          echo $error = "Database Error: We were unable to add the streamer name to the database.";
        }

      }
      // if streamer doesn't exist
      elseif ( property_exists($json, "error") ) {
        echo $error = "Streamer Doesn't exist.";
      } 
    }

  }

}

?>

<!DOCTYPE>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" type="text/css" href="https://necolas.github.io/normalize.css/3.0.2/normalize.css">
    <link rel="stylesheet" type="text/css" href="css/mobile-landing.css">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Lato:400,700' rel='stylesheet' type='text/css'>
  </head>
  
  <body>
    <header>
      <h1 class="header__logo">Streamer<span class="statistics_highlight">Stats</span></h1>
      <div class="header__container absolute_center">
        <p>Thank you so much for being a part of this.</p>
        <p>Did you know that we can start recording your stream data before we even launch?</p>
        <form class="signup" method="post" action="">
            <input class="signup__input signup__input--streamer" name="email" type="text" placeholder="Enter your email to be notified">
            <input class="signup__input signup__input--submit statistics_background" name="submit_email" type="submit" value="OK">
        </form>
        
        <form class="signup" method="post" action="">
            <input class="signup__input signup__input--streamer" name="streamer_name" type="text" placeholder="Streamer Name">
            <input class="signup__input signup__input--submit statistics_background" name="submit_streamer" type="submit" value="OK">
        </form>
        <p>We would absolutely love your feedback. Would you be willing to give it?</p>
        <form class="signup">
<!--             <input class="signup__input signup__input--streamer" name="email" type="text" placeholder="Streamer Name"> -->
            <input class="signup__input signup__input--submit statistics_background" name="submit" type="submit" value="OK">
        </form>
      </div>
      
      <div class="container2 up-to-tablet--hidden">
        <img class="smartphone-img" src="img/smartphone.png">
      </div>
    </header>
    
  </body>
</html>
