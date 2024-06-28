<?php
require_once '../classes/FormManager.php';

header('Content-Type: application/json');

$formManager = new FormManager();
$forms = $formManager->listForms();
echo json_encode($forms);
