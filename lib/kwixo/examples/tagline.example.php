<?php

// relever le point de départ
$timestart = microtime(true);

require_once '../../includes/includes.inc.php';

//loads Kwixo params
$kwixo = new Kwixo();

//defines a refID and an transactionID
$refID = 'YLAJe6U8UZ';
$transactionID = 'S90000063329';

//gets last payment tag
$res = $kwixo->getTagline($refID, $transactionID);

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


//Fin du code PHP
  $timeend = microtime(true);
  $time = $timeend - $timestart;

//Afficher le temps d'éxecution
  $page_load_time = number_format($time, 3);
  echo "Debut du script: " . date("H:i:s", $timestart);
  echo "<br>Fin du script: " . date("H:i:s", $timeend);
  echo "<br>Script execute en " . $page_load_time . " sec";
  exit;
}

echo "<h2>Global information</h2>";
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
  echo "<h2>Credit information</h2>";
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


//Fin du code PHP
$timeend = microtime(true);
$time = $timeend - $timestart;

//Afficher le temps d'éxecution
$page_load_time = number_format($time, 3);
echo "Debut du script: " . date("H:i:s", $timestart);
echo "<br>Fin du script: " . date("H:i:s", $timeend);
echo "<br>Script execute en " . $page_load_time . " sec";