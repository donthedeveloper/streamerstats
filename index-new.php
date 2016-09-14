<?php

// ini_set('display_errors', 'On');

require_once('resources/config.php');
require_once('classes/Database.php');
require_once('classes/ValidateInput.php');

$db = new Database($dbConfig);
$validateInput = new ValidateInput();

$error = null;
$success = null;
$duplicate = null;

if ($_POST['submit_email']) {
  
  $requiredArray = array('email' => TRUE);
  $inputArray = array('email' => $_POST['email']);
  
  // RUN VALIDATION METHODS //
  
  // check if input is empty
  $validatedEmptyInput = $validateInput->emailIsNotEmpty($inputArray, $requiredArray);
  if ( $validatedEmptyInput['email'] ) {
    
    // check if email is valid
    if ( $validateInput->emailIsValid('email') ) {
      
      // check if email exists
      if ( $db->emailDoesNotExistInDB($_POST['email']) ) {
        
        // add email to database
        if ( $db->addEmailToDatabase($_POST['email']) ) {
          $success = TRUE;
        }
        else {
          $error = "Sorry, we had a database error.";
        }
        
      }
      else {
        $duplicate = TRUE;
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
$intro .= "<h2 class='header__intro'>It's <i>almost</i> here.</h2>";
$intro .= "<h2 class='header__date'>Launching on or before <span class='statistics_highlight'>September 1st, 2016.</span></h2>";
        
$errorMessage .= "<p class='error'>$error</p>";
        
$successMessage .= "<h2 class='header__intro'>Thank you!</h2>";   
$successMessage .= "<h2 class='header__date'>Your email has been added.</h2>";
$successMessage .= "<p class='success'>As an added bonus, did you know that you can <a class='statistics_highlight' href='stats.php'>enter your streamer name</a>? We will start gathering data from your stream immediately. The more data we gather from your stream, the more we can help you improve.</p>";
        
$duplicateMessage .= "<p class='success'>We already have your email. Don't forget to <a class='statistics_highlight' href='stats.php'>enter your streamer name</a>. We will start gathering data from your stream immediately. The more data we gather from your stream, the more we can help you improve.</p>";
        
$form .= "<form class='signup' method='post' action=''>";
$form .= "<input class='signup__input signup__input--streamer' name='email' type='text' placeholder='Enter your email to be notified'>";
$form .= "<input class='signup__input signup__input--submit statistics_background' name='submit_email' type='submit' value='OK'>";
$form .= "</form>";

if (!$_POST['submit_email']) {
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
        <a class="logo" href="index.php"><h1 class="footer__logo">Streamer<span class="statistics_highlight">Stats</span></h1></a>

        <p class="footer__summary">We use your streaming data to build <i>personalized</i> strategies that help <i>you</i> grow <i>your</i> stream.</p>

        <form class="signup" action="#">
<!--           <input class="signup__input signup__input--streamer" class="streamer_name" type="text" placeholder="Enter your email to be notified"> -->
          <a class="signup__input signup__input--cta statistics_background" href="#">Notify Me</a>
        </form>
        
      </div>
      
      <div class="social_container clearfix">
        <p class="social_slogan up-to-tablet--hidden">Built By Streamers, For Streamers.</p>
        <ul class="social_nav">
          <li class="social_nav__element"><a href="http://www.twitter.com/streamerstatstv" target="_blank"><img class="social__icon" src="http://image.flaticon.com/icons/svg/145/145812.svg"></a></li>
      <li class="social_nav__element"><a href="https://www.facebook.com/streamerstatstv/" target="_blank"><img class="social__icon" src="http://image.flaticon.com/icons/svg/145/145802.svg"></a></li>
      <li class="social_nav__element"><a href="mailto:don@streamerstats.tv"><img class="social__icon" src="http://image.flaticon.com/icons/svg/9/9556.svg"></a></li>
        </ul>
      </div>
    </footer>
    
  </body>
</html>