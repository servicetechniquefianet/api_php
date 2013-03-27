<?php

/**
 * Implements the tag <answer> from the response from Remote Control
 *
 * @author ESPIAU Nicolas
 */
class FianetRemoteControlResponse extends FianetDOMDocument {
  
  public function getNbAcks(){
    return $this->root->getAttribute('nb');
  }

  /**
   * returns an array of objects FianetRemoteControlResponseAck
   * 
   * @return \FianetRemoteControlResponseAck
   */
  public function getAcks() {
    $acks = array();

    foreach ($this->getElementsByTagName('ack') as $ack) {
      $acks[] = new FianetRemoteControlResponseAck($ack->C14N());
    }

    return $acks;
  }

}