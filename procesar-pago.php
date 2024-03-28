<?php

if (!isset($_GET['data_id']) || $_GET['type'] != 'payment') {
  exit;
}

$secret = '66eeeaa7f1b622f74236eeb79b4220da3e87397badebbd5b358dd8b26aec1acd';

$input = file_get_contents("php://input");
$data = json_decode($input);

$file = fopen("dataNotificacion.lmdsi", "a");
fputs($file, "Fecha y hora:" . date('d/m/Y H:i:s') . "\r\n");
fputs($file, print_r(json_decode($input), true) . "\r\n");
fputs($file, print_r($_GET, true) . "\r\n");


$xSignature = $_SERVER['HTTP_X_SIGNATURE'];
$xRequestId = $_SERVER['HTTP_X_REQUEST_ID'];
$dataID = isset($_GET['data_id']) ? $_GET['data_id'] : '';
$parts = explode(',', $xSignature);

$ts = str_replace('ts=', '', $parts[0]);
$hash = str_replace('v1=', '', $parts[1]);

//validamos el origen de la notificacion
//id:[data.id_url];request-id:[x-request-id_header];ts:[ts_header];
$manifest = "id:$dataID;request-id:$xRequestId;ts:$ts;";

$sha = hash_hmac('sha256', $manifest, $secret);

if ($hash === $sha) {
  fputs($file, "HMAC verification passed \r\n");
} else {
  fputs($file, "HMAC verification failed \r\n");
}

fputs($file, "manifest " . $manifest . "\r\n");
fputs($file, "hash = sha " . $hash . " = " . $sha . "\r\n");
fclose($file);

