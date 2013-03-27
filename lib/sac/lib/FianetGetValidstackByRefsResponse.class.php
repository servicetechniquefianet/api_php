<?php

/**
 * Implements the response of the webservice get_validstack.cgi called with ref list in param
 */
class FianetGetValidstackByRefsResponse extends FianetGetEvaluationsResponse {
  /**
   * returns a collection of objects FianetGetValidstackByRefsResponseResult
   * 
   * @return \FianetGetValidstackByRefsResponseResult
   */
  public function getResults() {
    $results = array();
    $result_nodes = $this->getElementsByTagName('result');
    foreach ($result_nodes as $node) {
      $results[] = new FianetGetValidstackByRefsResponseResult($node->C14N());
    }
    return $results;
  }

}