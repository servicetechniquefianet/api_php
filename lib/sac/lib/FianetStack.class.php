<?php

/**
 * This class implements an element <stack>
 */
class FianetStack extends DOMDocument {
  
  /**
   * @var FianetXMLElement
   */
  public $root;

  public function __construct() {
    parent::__construct('1.0', 'UTF-8');
    $this->root = $this->appendChild(new FianetXMLElement('stack'));
  }
  
  /**
   * append a child <control> to the current root <stack> element
   * 
   * @param FianetControl $control
   */
  public function addControl(FianetControl $control){
    $control_node = $this->importNode($control->getRootElement(), true);
    return $this->root->appendChild($control_node);
  }
}