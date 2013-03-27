<?php

/**
 * This class implement an element <result> got in the response of the webservice get_validstack.cgi called with ref list in param
 */
class FianetGetValidstackByRefsResponseResult extends FianetGetEvaluationsResponseResult {

  /**
   * returns the value of the attribute <i>refid</i> of the current root element (<result>), which is the reference of the order given through the XML stream sent to Certissim
   * 
   * @return string
   */
  public function getRefid() {
    return $this->root->getAttribute('refid');
  }

  /**
   * returns an array containing all the elements <transaction> child of current root element as FianetGetValidstackByRefsResponseResultTransaction objects
   * 
   * @return \FianetGetValidstackByRefsResponseResultTransaction
   */
  public function getTransactions() {
    $transactions = array();
    foreach ($this->getElementsByTagName('transaction') as $transaction)
      $transactions[] = new FianetGetValidstackByRefsResponseResultTransaction($transaction->C14N());

    return $transactions;
  }

}