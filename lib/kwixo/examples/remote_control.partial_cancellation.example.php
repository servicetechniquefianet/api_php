<?php

require_once '../../includes/includes.inc.php';
/* * ***********************************************
 * Order confirmation through Remote Control
 * *********************************************** */
//loads Kwixo params
$kwixo = new Kwixo();

//defines a refID and an transactionID
$refID = 'T5bO4SbEJM';
$transactionID = 'S90000032088';

//gets last payment tag
$res = $kwixo->getTagline($refID, $transactionID);

//builds a TaglineResponse object if $res is a valid XML string
if (!isXMLstring($res))
  die('Error');


echo "<h2>Before RemoteControl</h2>";

//builds a TaglineResponse object if $res is a valid XML string
if (isXMLstring($res))
  $dom_tag = new FianetTaglineResponse($res);
else
  die('Error');

//stream display
echo "<textarea cols='100' rows='15'>" . $dom_tag->saveXML() . "</textarea><hr />";
//response display
if ($dom_tag->hasError()) {
  echo "Tag unreachable for transaction " . $dom_tag->getTransactionID() . " : error " . $dom_tag->getTagValue() . " -> <strong>" . $dom_tag->getError() . "</strong>";

  echo "<hr />";
}

echo "<h3>Global information</h3>";
echo "<ul>";
echo "<li>";
echo "Transaction Kwixo n°" . $dom_tag->getTransactionID();
echo "</li>";
echo "<li>";
echo "Amount : " . $dom_tag->getAmount() / 100 . "€";
echo "</li>";
echo "<li>";
echo ($dom_tag->isCredit() ? "Payment in installments" : "Payment in once" );
echo "</li>";
echo "<li>";
echo "Score : " . $dom_tag->getScore();
echo "</li>";
echo "<li>";
echo "Tag : " . $dom_tag->getTagValue();
echo "</li>";
echo "</ul>";

//if this transaction has been paid with Kwixo in installments: displays informations about installments
if ($dom_tag->isCredit()) {
  echo "<h3>Credit information</h3>";
  echo "<ul>";
  echo "<li>";
  echo "Request status: " . $dom_tag->getCreditInformation();
  echo "</li>";
  echo "<li>";
  echo $dom_tag->getInstallmentCount() . " installments";
  echo "</li>";
  echo "<li>";
  echo "Installment amount with insurance: " . $dom_tag->getInstallmentAmountWithInsurance() / 100 . "€";
  echo "</li>";
  echo "<li>";
  echo "Installment amount without insurance: " . $dom_tag->getInstallmentAmountWithoutInsurance() / 100 . "€";
  echo "</li>";
  echo "<li>";
  echo "Balance to pay: " . $dom_tag->getBalance() / 100 . "€";
  echo "</li>";
  echo "</ul>";
}

echo "<hr />";

echo "<h2>Remote Control</h2>";

//order validation
$result = $kwixo->sendRemoteControl(Kwixo::REMOTE_CTRL_ACTION_PARTIAL_CANCEL, $refID, $transactionID, '50.00');
//reads the response
if (isXMLstring($result))
  $answer = new FianetRemoteControlResponse($result);
else
  die('RC error');

//stream display
echo "Remote Control response: <br /><textarea cols='100' rows='15'>" . $answer->saveXML() . "</textarea><hr />";

//response display
echo $answer->getNbAcks() . " results :<br />";

//reads each ack (theorically Remote Control can't return more than one <ack>)
foreach ($answer->getAcks() as $ack) {
  echo "<textarea cols='100' rows='15'>" . $ack->saveXML() . "</textarea><br />";

  //security check
  if ($ack->checksumIsValid())
  //error check
    if ($ack->hasError())
    //error display
      echo "An error has been encountered: " . $ack->getErrorCode() . " - " . $ack->getError() . "<br />";
    else
    //result display
      echo "Transaction " . $ack->getTransactionID() . "  : treatment" . $ack->getValue() . "<br />";
  else
    echo "Invalid checksum.<br />";
}

echo "<hr />";

echo "<h2>After RemoteControl</h2>";

//gets last payment tag
$res = $kwixo->getTagline($refID, $transactionID);

//builds a TaglineResponse object if $res is a valid XML string
if (!isXMLstring($res))
  die('Error');

//builds a TaglineResponse object if $res is a valid XML string
if (isXMLstring($res))
  $dom_tag = new FianetTaglineResponse($res);
else
  die('Error');

//stream display
echo "<textarea cols='100' rows='15'>" . $dom_tag->saveXML() . "</textarea><hr />";
//response display
if ($dom_tag->hasError()) {
  echo "Tag unreachable for transaction " . $dom_tag->getTransactionID() . " : error " . $dom_tag->getTagValue() . " -> <strong>" . $dom_tag->getError() . "</strong>";

  echo "<hr />";
}

echo "<h3>Global information</h3>";
echo "<ul>";
echo "<li>";
echo "Transaction Kwixo n°" . $dom_tag->getTransactionID();
echo "</li>";
echo "<li>";
echo "Amount : " . $dom_tag->getAmount() / 100 . "€";
echo "</li>";
echo "<li>";
echo ($dom_tag->isCredit() ? "Payment in installments" : "Payment in once" );
echo "</li>";
echo "<li>";
echo "Score : " . $dom_tag->getScore();
echo "</li>";
echo "<li>";
echo "Tag : " . $dom_tag->getTagValue();
echo "</li>";
echo "</ul>";

//if this transaction has been paid with Kwixo in installments: displays informations about installments
if ($dom_tag->isCredit()) {
  echo "<h3>Credit information</h3>";
  echo "<ul>";
  echo "<li>";
  echo "Request status: " . $dom_tag->getCreditInformation();
  echo "</li>";
  echo "<li>";
  echo $dom_tag->getInstallmentCount() . " installments";
  echo "</li>";
  echo "<li>";
  echo "Installment amount with insurance: " . $dom_tag->getInstallmentAmountWithInsurance() / 100 . "€";
  echo "</li>";
  echo "<li>";
  echo "Installment amount without insurance: " . $dom_tag->getInstallmentAmountWithoutInsurance() / 100 . "€";
  echo "</li>";
  echo "<li>";
  echo "Balance to pay: " . $dom_tag->getBalance() / 100 . "€";
  echo "</li>";
  echo "</ul>";
}


