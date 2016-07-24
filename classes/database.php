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
      echo "Could not pull data";
    }
  }
  
  
  
}












