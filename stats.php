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

if ($_POST['submit_streamer']) {

  $requiredArray = array('streamer' => TRUE);
  $inputArray = array('streamer' => $_POST['streamer']);
  
  // RUN VALIDATION METHODS //
  
  // check if input is empty
  if ( $validateInput->validateEmptyInput($inputArray, $requiredArray) ) {
    
    // check if input is alphanumeric (twitch name requirement)
    if ( $validateInput->isAlphaNumeric($_POST['streamer']) ) {
      
      if ( $db->streamerExistsInDB($_POST['streamer']) ) {
        $error = "That streamer name is already in our database. We've already started gathering data.";
      }
      else {
        
        // get streamer data from twitch api (data placed in private property)
        $json->curlStreamerData($twitchApiUrl, $_POST['streamer']); // TO-DO: build a check to check success of curl request
        
        // check if streamer exists
        if ($json->streamerExistsOnTwitch() ) {
          // add them to database
          
          if ( $db->addStreamerToDatabase($_POST['streamer']) ) {
            echo "we did it boys";
            $success = TRUE;
          }
          else {
            echo $error = "Database Error: We were unable to add the streamer name to the database.";
          }
          
        }
        else {
          $error = "This streamer does NOT exist.";
        }
        
      }
      
    }
    else {
      $error = "Streamer names must only consist of letters and numbers.";
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
    <link rel="stylesheet" type="text/css" href="css/mobile-landing.css">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Lato:400,700' rel='stylesheet' type='text/css'>
  </head>
  
  <body>
    <header>
      <h1 class="header__logo">Streamer<span class="statistics_highlight">Stats</span></h1> 
      <div class="header__container absolute_center">

<?php

// BUILD CTA CONTENT
$form .= "<form class='signup' method='post' action=''>";
$form .= "<input class='signup__input signup__input--streamer' name='streamer' type='text' placeholder='Enter your streamer name'>";
$form .= "<input class='signup__input signup__input--submit statistics_background' name='submit_streamer' type='submit' value='OK'>";
$form .= "</form>";
        
echo $output = $form;
// END CTA CONTENT
        
?>

      </div>
      
      <div class="container2 up-to-tablet--hidden">
        <img class="smartphone-img" src="img/smartphone.png">
      </div>
    </header>
    
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

        <form class="signup" action="#">
<!--           <input class="signup__input signup__input--streamer" class="streamer_name" type="text" placeholder="Enter your email to be notified"> -->
          <a class="signup__input signup__input--submit statistics_background" href="#">Notify Me</a>
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
    
  </body>
</html>