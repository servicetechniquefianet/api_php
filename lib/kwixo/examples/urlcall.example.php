<?php
extract($_POST);

require_once '../../includes/includes.inc.php';

$kwixo = new Kwixo();

$md5 = new KwixoMD5();
$secure_hash = $md5->hash($kwixo->getAuthKey() . $RefID . $TransactionID);

if ($secure_hash != $HControl)
  die("Securit key is not valid.");

switch ($Tag) {
  case 0:
    echo "You cancelled your payment. No debit will occur. You can choose another payment method or continue shopping.";
    //show links to cart and/or to payment methods page
    break;
  case 1:
    echo "Your payment has correctly been registered. Your order has been validated, you will receive a confirmation mail soon.";
    //show link to order recap, or to homepage
    break;
  case 2:
    echo "Your payment has been refused. No debit will occur. You can choose another payment method or continue shopping.";
    //show links to cart and/or to payment methods page
    break;

  default:
    echo "It seems the system encountered a problem. We strongly advise you to contact Kwixo to know the status of your payment. Please note this reference you will have to give to the support agent: TransactionID='$TransactionID'.";
    break;
}