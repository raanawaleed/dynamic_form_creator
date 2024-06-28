<?php
require_once '../classes/FormManager.php';

header('Content-Type: application/json');

$form_id = $_GET['id'];

$formManager = new FormManager();
$form = $formManager->getForm($form_id);
echo json_encode($form);
