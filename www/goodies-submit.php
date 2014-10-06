<?php
require __DIR__ . '/vendor/autoload.php';
use PayPal\Auth\OAuthTokenCredential;

$mail = $_POST['mail'];
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];

$size = $_POST['size'];
$quantite = intval($_POST['quantite']);
$sexe = $_POST['sexe'];

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
$payer->setPayment_method("paypal");

$amount = new Amount();
$amount->setCurrency("EUR");
$total = $quantite * 15;

$amount->setTotal($total);

$tmp = Array();

$item = new Item();
$item->setQuantity($quantite);
$item->setName("T-Shirt pour $firstname $lastname, taille $size $sexe");
$item->setPrice('15');
$item->setCurrency('EUR');
array_push($tmp, $item);

$item_list = new ItemList();
//$item_list->setItems(Array($item));
$item_list->setItems($tmp);


$transaction = new Transaction();
$transaction->setAmount($amount);
$transaction->setDescription("Commande en provenance du site Coworking");
$transaction->setItem_list($item_list);

$baseUrl = getBaseUrl();
$redirectUrls = new RedirectUrls();
$redirectUrls->setReturn_url("$baseUrl/ExecutePayment.php?success=true");
$redirectUrls->setCancel_url("$baseUrl/ExecutePayment.php?success=false");

$payment = new Payment();
$payment->setIntent("sale");
$payment->setPayer($payer);
$payment->setRedirect_urls($redirectUrls);
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
