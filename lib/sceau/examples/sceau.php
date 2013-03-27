<?php

require_once '../../includes/includes.inc.php';
//loads Sceau params
$sceau = new Sceau();

/* * *********************** Use case description ***********************
 * billing customer only (no delivery customer)
 * address without flat information
 * no <siteconso> element
 * delivery in a drop off point
 * many products, at least one of them in multiple copies
 * Kwixo payment in once with debit after receipt
 * ******************************************************************** */
//<control>
$control = new FianetControl();
//<utilisateur>
$control->createCustomer('', 1, 'DUPONT', 'Michel', 'recetteScore0@fia-net.com');

/* * ****** <infocommande> ************** */
$control->createOrderDetails(generateRandomRefId(), $sceau->getSiteid(), 165, 'EUR', $_SERVER['REMOTE_ADDR'], date('Y-m-d H:i:s'));

/* * ****** <paiement> ************** */
$control->createPayment(1);


echo "<textarea cols='100' rows='25'>";
echo $control->saveXML();
echo "</textarea>";


/** Sending to Fia-Net * */
$result = $sceau->sendSendrating($control);

//converts the string response into a XMLElement if the string respects the XML format
if (isXMLstring($result)) {
  $resxml = new FianetSendratingResponse($result);

  echo '<hr />';

  echo "<ul>";
  echo "<li>";
  if ($resxml->isValid())
    echo "Stream has correctly been sent.";
  else
    echo "An error occured.";
  echo "</li>";
  echo "<li>";
  echo 'Result detail: ' . $resxml->getDetail();
  echo "</li>";
  echo "</ul>";

  echo 'Full response:<br />';
  echo "<textarea cols='100' rows='15'>";
  echo $resxml->saveXML();
  echo "</textarea>";
} else {
  echo 'Fia-Net did not send an XML response as waited: ';
  echo "<textarea cols='100' rows='25'>";
  echo $result;
  echo "</textarea>";
}