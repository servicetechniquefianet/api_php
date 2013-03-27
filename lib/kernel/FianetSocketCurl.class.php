<?php

/**
 * Connection class using cURL
 *
 * @author ESPIAU Nicolas <nicolas.espiau at fia-net.com>
 */
class FianetSocket extends Mother {

  const TIMEOUT = 5;

  protected $url;
  protected $method = 'GET';
  protected $data;
  protected $response = '';
  protected $errno;
  protected $errstr;
  protected $connection;

  /**
   * initializes object
   *
   * @param string $url URL to reach
   * @param string $method HTTP metho
   * @param array $data data query
   */
  public function __construct($url, $method = 'GET', array $data = null) {
    $this->setUrl($url);
    $this->setMethod(strtoupper($method));

    if (!is_null($data))
      $this->setData(http_build_query($data));
  }

  /**
   * * sends the request to host and returns the response
   * 
   * @return type
   */
  function send() {
    $this->setConnection(curl_init($this->getUrl()));
    if ($this->getConnection() === false) {
      $error = curl_error($this->getConnection());
      FianetLogger::insertLog(__METHOD__ . ' : ' . __LINE__, "Erreur de connexion à " . $this->getUrl() . " : " . $error);
    } else {
      curl_setopt($this->getConnection(), CURLOPT_TIMEOUT, self::TIMEOUT);
      if ($this->getMethod() == 'POST') {
        curl_setopt($this->getConnection(), CURLOPT_POST, 1);
        curl_setopt($this->getConnection(), CURLOPT_POSTFIELDS, $this->getData());
      } else {
        curl_setopt($this->getConnection(), CURLOPT_URL, $this->getUrl() . '?' . $this->getData());
      }
      curl_setopt($this->getConnection(), CURLOPT_RETURNTRANSFER, 1);

      $res = curl_exec($this->getConnection());
      if ($res === false) {
        $error = curl_error($this->getConnection());
        FianetLogger::insertLog(__METHOD__ . ' : ' . __LINE__, "Erreur d'execution : " . $error);
      }
    }
    curl_close($this->getConnection());
    return $res;
  }

}