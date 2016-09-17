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
  
  
  public function streamerDoesNotExistInDb($streamerName) {
    try {
      $results = $this->dbh->prepare("SELECT name FROM streamers WHERE name=?");
      $results->bindValue(1, $streamerName, PDO::PARAM_STR);
      $results->execute();
      
      $this->streamerData = $results;
      
      if ($results->rowCount() > 0) {
        return FALSE;
      }
      else {
        return TRUE;
      }
    }
    catch(Exception $exception) {
//       echo $exception->getMessage();
      return TRUE;
    }
  }
  
  
  public function addEmailToDatabase($email) { // TO-DO: USE SELECT STATEMENT TO VERIFY EMAIL WAS ADDED
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
  
  
  public function emailDoesNotExistInDB($email) {
    try {
      $results = $this->dbh->prepare("SELECT email FROM email WHERE email=?");
      $results->bindValue(1, $email, PDO::PARAM_STR);
      $results->execute();
      
      if ($results->rowCount() >0) {
        return FALSE;
      }
      else {
        return TRUE;
      }
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
      echo $exception;
      return FALSE;
    }
  }
  
  
  public function streamerHasDataInDb($streamerName) {
    try {
      $results = $this->dbh->prepare("SELECT * FROM stats WHERE channel_name=? ORDER BY stream_id, db_entry_timestamp");
      $results->bindValue(1, $streamerName, PDO::PARAM_STR);
      $results->execute();
      
      if ($results->rowCount() > 0) {
        $this->streamerData = $results;
        
        return TRUE;
      }
      else {
        return FALSE;
      }
    }
    catch(Exception $exception) {
//       echo $exception-getMessage();
      return FALSE;
    }
  }
  
  
  
  public function getStreamerData() {
    return $this->streamerData;
  }
  
  
  
}












