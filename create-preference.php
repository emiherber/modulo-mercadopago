<?php
// Paso opcional para cargar el autoload de Composer si ya lo tienes
require_once 'vendor/autoload.php';

$aux_server = isset($_SERVER["HTTPS"]) ? 'https' : 'http';
$aux_server = $aux_server . "://" . $_SERVER["HTTP_HOST"];
$url = "$aux_server/modulo-mercadopago/procesar-pago.php";

MercadoPago\SDK::setAccessToken('APP_USR-8429650309440343-020521-e892626ecdb711bc161072710442b7ba-1303671046');

$preference = new MercadoPago\Preference();
$preference->notification_url = $url . "?numeroCupon=" . '000000' . $_GET['numeroCupon'];
$preference->external_reference = '000000' . $_GET['numeroCupon']. ';';
$preference->back_urls = [
  "success" => "$aux_server/modulo-mercadopago/",
];
$preference->auto_return = "approved";

$cupones = [];

$item = new MercadoPago\Item();
$item->title = 'Cupon Nº ' . $_GET['numeroCupon'];
$item->quantity = 1;
$item->unit_price = 250;

$cupones[] = $item;

for ($i = 1; $i < 4; $i++) {
  $item = new MercadoPago\Item();
  $item->title = 'Cupon Nº ' . $i;
  $item->quantity = 1;
  $item->unit_price = 250;

  $cupones[] = $item;

  $preference->external_reference .= "000000$i;";
}

$preference->items = $cupones;
$preference->save();

$response = ['preferenceId' => $preference->id, 'initPoint' => $preference->init_point];
echo json_encode($response);
