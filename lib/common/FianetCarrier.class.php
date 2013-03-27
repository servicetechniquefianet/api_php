<?php

/**
 * Class for the <transport> elements
 * 
 * @author ESPIAU Nicolas <nicolas.espiau at fia-net.com>
 */
class FianetCarrier extends FianetXMLElement {

  const SPEED_STANDARD = 2;
  const SPEED_EXPRESS = 1;
  const TYPE_WITHDRAWAL_AT_MERCHANT = 1;
  const TYPE_DROP_OFF_POINT = 2;
  const TYPE_WITHDRAWAL_AT_AGENCY = 3;
  const TYPE_CARRIER = 4;
  const TYPE_DOWNLOAD = 5;

  public function __construct() {
    parent::__construct('transport');
  }

  /**
   * creates an object FianetDropOffPoint representing the element <pointrelais>, adds it to the current object, then returns it
   * 
   * @param string $name
   * @param string $id
   * @param FianetXMLElement $address
   * @return FianetDropOffPoint
   */
  public function createDropOffPoint($name = null, $id = null, FianetXMLElement $address = null) {
    $drop_off_point = $this->addChild(new FianetDropOffPoint());
    if (!is_null($name))
      $drop_off_point->createChild('nom', $name);
    if (!is_null($id))
      $drop_off_point->createChild('identifiant', $id);
    if (!is_null($address))
      $drop_off_point->addChild($address);

    return $drop_off_point;
  }

}