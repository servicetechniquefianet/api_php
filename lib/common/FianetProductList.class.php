<?php

/**
 * Class for the <list> elements
 * 
 * @author ESPIAU Nicolas <nicolas.espiau at fia-net.com>
 */
class FianetProductList extends FianetXMLElement {

  public function __construct() {
    parent::__construct("list");
  }

  /**
   * adds a product into the list and increases the attribute nbproduit of the current object
   * 
   * @param FianetXMLElement $produit
   * @param array $attrs
   * @return FianetXMLElement
   */
  public function createProduct($label, $ref, $type, $price, $nb) {
    $product = $this->createChild('produit', $label, array('ref' => $ref, 'type' => $type, 'prixunit' => $price, 'nb' => $nb));
    $nbproducts = (int)$this->getAttribute('nbproduit');
    $this->setAttribute('nbproduit', $nbproducts + $nb);

    return $product;
  }

}