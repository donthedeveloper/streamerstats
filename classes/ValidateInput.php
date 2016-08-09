<?php

class ValidateInput {

  
  // returns array of passed(true) & failed(false) inputs
  public function validateEmptyInput($inputArray, $requiredArray) {
    
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

  
  public function validateEmail($inputName) {
    
    $validatedEmail = filter_input(INPUT_POST, $inputName, FILTER_VALIDATE_EMAIL);

    if ($validatedEmail === FALSE) {
      return FALSE;
    }
    else {
      return TRUE;
    }
    
    
  }
  
  
  public function isAlphaNumeric($inputName) {
    
    $validatedInput = ctype_alnum($inputName);
    
    if ($validatedInput) {
      return TRUE;
    }
    else {
      return FALSE;
    }
    
  }
  
}