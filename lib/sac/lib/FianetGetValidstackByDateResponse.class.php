<?php

/**
 * Implements the response of the webservice get_validstack.cgi called with a date in param
 */
class FianetGetValidstackByDateResponse extends FianetGetEvaluationsResponse {
  /**
   * returns a collection of objects FianetGetValidstackByDateResponseResult
   * 
   * @return \FianetGetValidstackByRefsResponseResult
   */
  public function getResults() {
    $results = array();
    $result_nodes = $this->getElementsByTagName('result');
    foreach ($result_nodes as $node) {
      $results[] = new FianetGetValidstackByDateResponseResult($node->C14N());
    }
    return $results;
  }

}