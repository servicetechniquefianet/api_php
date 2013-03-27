<?php
require_once '../../includes/includes.inc.php';
//loads Kwixo params
$kwixo = new Kwixo();

/************************* Use case description ***********************
 * billing customer only (no delivery customer)
 * address without flat information
 * no <siteconso> element
 * delivery in a drop off point
 * many products, at least one of them in multiple copies
 * Kwixo payment in once with debit after receipt
 **********************************************************************/
//<control>
$control = new FianetKwixoControl();

//<utilisateur type='facturation' qualite='2'>
$control->createInvoiceCustomer('Monsieur', 'DUPONT', 'Michel', 'recetteScore0@fia-net.com', 'SARL DMICHEL', '0621085793', '0101010203');
//<adresse type='facturation' format='1'>
$control->createInvoiceAddress('13 rue de la gare', '75001', 'Paris', 'France', 'Bat. B');

/******** <infocommande> ***************/
$order_details = $control->createOrderDetails(generateRandomRefId(), $kwixo->getSiteid(), 165, 'EUR', $_SERVER['REMOTE_ADDR'], date('Y-m-d H:i:s'));
//<transport>
$carrier = $order_details->createCarrier('Kiala', 2, 2);
//<pointrelais>
$drop_off_point = $carrier->createDropOffPoint('Kiala les petits pains', 'KIALA115542');
//<adresse>
$drop_off_point->createAddress('1 rue de la gare', '75009', 'Paris', 'France', 'lot. des petit pins');

/******** <list> ***************/
$product_list = $order_details->createProductList();
$product_list->createProduct('libellé du produit 1', 'refprod1', 13, 30, 2);
$product_list->createProduct('libellé du produit 2', 'refprod2', 13, 35, 3);

/******************** <wallet> **************************/
//<wallet version=''>
$date_order = date('Y-m-d H:i:s');
$wallet = $control->createWallet($date_order, $kwixo->generateDatelivr($date_order, 3));
$wallet->addCrypt($kwixo->generateCrypt($control), $kwixo->getCryptversion());

/**************** <options-paiement> **********************/
$control->createPaymentOptions('comptant', 0);

/* * * redirection form generation * */
//detects platform type
$mobile_detect = new MobileDetect();
$mobile = $mobile_detect->isMobile();

//checks availability
$kwixo_available = $kwixo->cashAvailable($mobile);

//if you chose Kwixo credit, do:
//$kwixo_available = $kwixo->creditAvailable($mobile);

$xml_params = new FianetXMLParams();
$xml_params->addParam('param1', 'val1');
$xml_params->addParam('param2', 'val2');

if ($kwixo_available)
  echo $kwixo->getTransactionForm($control, $xml_params, null, $_SERVER['HTTP_REFERER'].'urlcall.example.php', $mobile, Form::SUBMIT_STANDARD);
else 
  die ('Kwixo is not available.');

/**
 * If you want to generate a form that is submitted automatically:
  $form = $kwixo->getTransactionForm($control, null, null, null, $mobile, Form::SUBMIT_AUTO);
 */