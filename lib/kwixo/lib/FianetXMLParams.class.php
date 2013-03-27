<?php

class FianetXMLParams extends DOMDocument {
  protected $root;
  
  public function __construct() {
    parent::__construct('1.0', 'UTF-8');
    $this->root = $this->appendChild(new FianetXMLElement('ParamCBack'));
  }
  
  /**
   * adds a param that will be returned with other Kwixo params during Kwixo calls (on URLSys and URLCall)
   * 
   * @param string $name param name
   * @param string $value param value
   * @return FianetXMLElement
   */
  public function addParam($name, $value){
    $obj = $this->root->appendChild(new FianetXMLElement('obj'));
    $obj->appendChild(new FianetXMLElement('name', $name));
    $obj->appendChild(new FianetXMLElement('value', $value));
    return $obj;
  }
}