<?php

class ValidateInput {

  
  // returns array of passed(true) & failed(false) inputs
  public function emailIsNotEmpty($inputArray, $requiredArray) {
    
    $validatedInputArray = array();
    
    foreach($inputArray as $inputName => $inputValue) {
      // if the current input being looped through is required
      if ($requiredArray[$inputName]) {
        if ( empty($inputValue) ) {
          $validatedInputArray[$inputName] = FALSE;
        }
        else {
          $validatedInputArray[$inputName] = TRUE;
        }
      }
    }
    
    return $validatedInputArray;
    
  }

  
  public function emailIsValid($inputName) {
    
    $validatedEmail = filter_input(INPUT_POST, $inputName, FILTER_VALIDATE_EMAIL);

    if ($validatedEmail) {
      return TRUE;
    }
    else {
      return FALSE;
    }
    
    
  }

  
  public function isAlphaNumeric($input) {
  
    $validatedInput = ctype_alnum($input);

    if ($validatedInput) {
      return TRUE;
    }
    else {
      return FALSE;
    }
  
  }
  
}
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  