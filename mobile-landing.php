<?php

// ini_set('display_errors', 'On');

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
  print_r($validatedEmptyInput);
  
  if ($validatedEmptyInput) {
    
    $validatedEmailInput = $validateInput->validateEmail('email');
    
    
    if ($validatedEmailInput) {
//       SUBMIT EMAIL TO DATABASE
      if ( $db->addEmailToDatabase($_POST['email']) ) {
        echo $success = "Your email has been added successfully.";
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
      curl_setopt($curl, CURLOPT_HEADER, 0);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

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
<?php   
        
if ( (!$_POST['submit_email']) || ($error && $_POST['submit_email']) ) {  
        
?>        
        <h2 class="header__intro">It's <i>almost</i> here.</h2>
        <h2 class="header__date">Launching on or before <span class="statistics_highlight">September 1st, 2016.</span></h2>
       
        <?php if ($error) { echo "<p class='error'>$error</p>"; } ?>
        
        <form class="signup" method="post" action="">
            <input class="signup__input signup__input--streamer" name="email" type="text" placeholder="Enter your email to be notified">
            <input class="signup__input signup__input--submit statistics_background" name="submit_email" type="submit" value="OK">
        </form>
      </div>
      
      <div class="container2 up-to-tablet--hidden">
        <img class="smartphone-img" src="img/smartphone.png">
<?php
        
}
else {

?>  
        <?php if ($error) { echo "<p class='error'>$error</p>"; } ?>
        <?php if ($message) { echo "<p class='message'>$message</p>"; } ?>
        
        <form class="signup" method="post" action="">
            <input class="signup__input signup__input--streamer" name="streamer_name" type="text" placeholder="Streamer Name">
            <input class="signup__input signup__input--submit statistics_background" name="submit_streamer" type="submit" value="OK">
        </form>
<?php        
        
}
        
?>
      </div>
      
      
      
    </header>
    
<?php
    
if ( !$_POST['submit_email'] ) {    
    
?>
    
    <ul class="features">
      <li class="features__element features__element--one"><p class="features__paragraph"><i class=""></i>Personalized<span class="hidden-on-mobile">, executable</span> strategies<span class="hidden-on-mobile"></span></p></li>
      <li class="features__element features__element--two"><p class="features__paragraph">Simple and intuitive <span class="hidden-on-mobile"> results on the surface</span></p></li>
  <li class="features__element features__element--three"><p class="features__paragraph">Complex <span class="hidden-on-mobile"> statistical</span> algorithms <span class="hidden-on-mobile">underneath</span></p></li>
      <li class="features__element features__element--four"><p class="features__paragraph">Clean design<span class="hidden-on-mobile"> and easy-to-use dashboard</span></p></li>
      <li class="features__element features__element--five"><p class="features__paragraph">Always being updated<span class="hidden-on-mobile"> with new features</span></p></li>
      <li class="features__element features__element--six"><p class="features__paragraph">Get to know your viewers<span class="hidden-on-mobile"> better than</span></p></li>
    </ul>
    
    <footer>
      <div class="footer__container absolute_center">
        <h1 class="footer__logo">Streamer<span class="statistics_highlight">Stats</span></h1>

        <p class="footer__summary">We use your streaming data to build <i>personalized</i> strategies that help <i>you</i> grow <i>your</i> stream.</p>

        <form class="signup">
          <input class="signup__input signup__input--streamer" class="streamer_name" type="text" placeholder="Enter your email to be notified">
          <input class="signup__input signup__input--submit statistics_background" class="submit"  type="button" value="OK">
        </form>
        
      </div>
      
      <div class="social_container clearfix">
        <p class="social_slogan up-to-tablet--hidden">Built By Streamers, For Streamers.</p>
        <ul class="social_nav">
          <li class="social_nav__element"><i><img class="social__icon" src="http://image.flaticon.com/icons/svg/145/145812.svg"></i></li>
          <li class="social_nav__element"><i><img class="social__icon" src="http://image.flaticon.com/icons/svg/145/145802.svg"></i></li>
          <li class="social_nav__element"><i><img class="social__icon" src="http://image.flaticon.com/icons/svg/9/9556.svg"></i></li>
        </ul>
      </div>
    </footer>
    
<?php
    
}  
    
?>
    
  </body>
</html>
















