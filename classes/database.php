<?php

class Database {
  private $dbHost;
  private $dbName;
  private $dbUser;
  private $dbPass;
  
  private $dbh;
  
  private $streamerData;
  
  
  public function __construct($dbConfig) {
    $this->dbHost = $dbConfig['dbhost'];
    $this->dbName = $dbConfig['dbname'];
    $this->dbUser = $dbConfig['dbuser'];
    $this->dbPass = $dbConfig['dbpass'];
    
    try {
      $this->dbh = new PDO('mysql:host=' . $this->dbHost . ';dbname=' . $this->dbName, $this->dbUser, $this->dbPass);
      $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(Exception $exception) {
//       echo $exception->getMessage();
      echo "error connecting";
      die(); // CHANGE LATER
    }
  }
  
  
  public function streamerExistsInDB($streamerName) {
    try {
      $results = $this->dbh->prepare("SELECT name FROM streamers WHERE name=?");
      $results->bindValue(1, $streamerName, PDO::PARAM_STR);
      $results->execute();
      
      $this->streamerData = $results;
      
      if ($results->rowCount() > 0) {
        return true;
      }
      else {
        return false;
      }
    }
    catch(Exception $exception) {
//       echo $exception->getMessage();
      return FALSE;
    }
  }
  
  
  public function addEmailToDatabase($email) {
    try {
      $results = $this->dbh->prepare("INSERT INTO email (email) VALUES (?)");
      $results->bindValue(1, $email, PDO::PARAM_STR);
      $results->execute();
      
      return TRUE;
    }
    catch (Exception $exception) {
      return FALSE;
    }
  }
  
  
  public function addStreamerToDatabase($streamerName) {
    try {
      $results = $this->dbh->prepare("INSERT INTO streamers (name) VALUES (?)");
      $results->bindValue(1, $streamerName, PDO::PARAM_STR);
      $results->execute();
      
      return TRUE;
    }
    catch (Exception $exception) {
      return FALSE;
    }
  }
  
  
  public function streamerGetData($streamerName) {
    try {
      $results = $this->dbh->prepare("SELECT * FROM statistics WHERE streamer=? ORDER BY timestamp");
      $results->bindValue(1, $streamerName, PDO::PARAM_STR);
      $results->execute();
      
      if ($results->rowCount() > 0) {
        // return data for manipulation
        return TRUE;
      }
      else {
        return FALSE;
      }
    }
    catch(Exception $exception) {
//       echo $exception-getMessage();
      echo "Could not verify whether streamer has data in database.";
    }
  }
  
  
  
}












