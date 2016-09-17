<?php

class Json {

  private $json;
  
  
  private function decodeJson($curl) {
    $this->json = json_decode($curl);
  }
  
  
  //make a request for streamer data through twitch api
  public function curlStreamerData($url, $streamer, $id) {
    
    // create new cURL resource
    $curl = curl_init();
    
    // set parameters
    curl_setopt($curl, CURLOPT_URL, $url . $streamer);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Client-ID: ' . $id));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    
    // grab url and pass it to the browser
    $json = curl_exec($curl);
    
    $this->decodeJson($json);
    
    // close cURL resource
    curl_close($curl);
    
  }
  
  // check if streamer exists on twitch
  public function streamerExistsOnTwitch() {
    
    // if streamer exists on twitch
    if ( property_exists($this->json, "stream") ) {
      return TRUE;
    }
    elseif ( property_exists($this->json, "error") ) {
      return FALSE;
    }
    
  }
  
}