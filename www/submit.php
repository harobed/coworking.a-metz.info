<?php
require __DIR__ . '/vendor/autoload.php';
use PayPal\Auth\OAuthTokenCredential;

$mail = $_POST['mail'];
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];

$adhesion = $_POST['adhesion'] == 'on';
$unit_ticket = intval($_POST['unit_ticket']);
$ticket10 = intval($_POST['ticket10']);
$abonnement1mois = intval($_POST['abonnement1mois']);
$donation = intval($_POST['donation']);


define("PP_CONFIG_PATH", __DIR__ . '/../');

function getBaseUrl() {
    $protocol = 'http';
    if ($_SERVER['SERVER_PORT'] == 443 || (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on')) {
        $protocol .= 's';
        $protocol_port = $_SERVER['SERVER_PORT'];
    } else {
        $protocol_port = 80;
    }

    $host = $_SERVER['HTTP_HOST'];
    $port = $_SERVER['SERVER_PORT'];
    $request = $_SERVER['PHP_SELF'];
    return dirname($protocol . '://' . $host . ($port == $protocol_port ? '' : ':' . $port) . $request);
}

use PayPal\Api\Address;
use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\FundingInstrument;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Rest\ApiContext;
session_start();

$payer = new Payer();
$payer->setPaymentMethod("paypal");

$amount = new Amount();
$amount->setCurrency("EUR");
$total = 0;
if ($adhesion) {
    $total += 10;
}

$total += ($unit_ticket * 6) + ($ticket10 * 40) + ($abonnement1mois * 60) + $donation;

$amount->setTotal($total);

$tmp = Array();

if ($adhesion) {
    $item = new Item();
    $item->setQuantity("1");
    $item->setName("Adhesion de $firstname $lastname");
    $item->setPrice('10');
    $item->setCurrency('EUR');
    array_push($tmp, $item);
}

if ($unit_ticket > 0) {
    $item = new Item();
    $item->setQuantity(strval($unit_ticket));
    $item->setName("Ticket à l'unité pour $firstname $lastname");
    $item->setPrice('6');
    $item->setCurrency('EUR');
    array_push($tmp, $item);
}

if ($ticket10 > 0) {
    $item = new Item();
    $item->setQuantity(strval($ticket10));
    $item->setName("Carnet de 10 tickets pour $firstname $lastname");
    $item->setPrice('40');
    $item->setCurrency('EUR');
    array_push($tmp, $item);
}

if ($abonnement1mois > 0) {
    $item = new Item();
    $item->setQuantity(strval($abonnement1mois));
    $item->setName("Abonnement d'un mois pour $firstname $lastname");
    $item->setPrice('60');
    $item->setCurrency('EUR');
    array_push($tmp, $item);
}

if ($donation) {
    $item = new Item();
    $item->setQuantity("1");
    $item->setName("Don de $firstname $lastname");
    $item->setPrice(strval($donation));
    $item->setCurrency('EUR');
    array_push($tmp, $item);
}

$item_list = new ItemList();
//$item_list->setItems(Array($item));
$item_list->setItems($tmp);


$transaction = new Transaction();
$transaction->setAmount($amount);
$transaction->setDescription("Commande en provenance du site Coworking");
$transaction->setItemList($item_list);

$baseUrl = getBaseUrl();
$redirectUrls = new RedirectUrls();
$redirectUrls->setReturnUrl("$baseUrl/ExecutePayment.php?success=true");
$redirectUrls->setCancelUrl("$baseUrl/ExecutePayment.php?success=false");

$payment = new Payment();
$payment->setIntent("sale");
$payment->setPayer($payer);
$payment->setRedirectUrls($redirectUrls);
$payment->setTransactions(array($transaction));

$apiContext = new ApiContext($cred, 'Request' . time());

try {
    $payment->create($apiContext);
} catch (\PPConnectionException $ex) {
    echo "Exception: " . $ex->getMessage() . PHP_EOL;
    var_dump($ex->getData());
    exit(1);
}

foreach($payment->getLinks() as $link) {
    if($link->getRel() == 'approval_url') {
        $redirectUrl = $link->getHref();
    }
}

$_SESSION['paymentId'] = $payment->getId();
if(isset($redirectUrl)) {
    header("Location: $redirectUrl");
    exit;
}
?>
