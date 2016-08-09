<?php

class Json {
  
  private $json;

  
  private function decodeJson($curl) {
    $this->json = json_decode($curl);
  }
  
  
  // make a request for streamer data through twitch api
  public function curlStreamerData($url, $streamerName) {
    
    // create new cURL resource
    $curl = curl_init();

    // set parameters
    curl_setopt($curl, CURLOPT_URL, $url . $streamerName);
    curl_setopt($curl, CURLOPT_HEADER, 'Client-ID: ' . $twitchClientID);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

    // grab url and pass it to the browser
    $json = curl_exec($curl);
    
    $this->decodeJson($json);

    // close cURL resource (free up resources)
    curl_close($curl);
  }
  
  
  // check if streamer exists on twitch
  public function streamerExistsOnTwitch() {
    
//     print_r($this->json);
    
    // if streamer exists on twitch
    if ( property_exists($this->json, "stream") ) {
      return TRUE;
    }
    // if streamer doesn't exist
    elseif ( property_exists($this->json, "error") ) {
      return FALSE;
    }
    
  }
  

}

?>