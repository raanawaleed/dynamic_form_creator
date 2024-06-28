<?php
require_once '../classes/FormManager.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['form_name'], $data['fields'], $data['email_fields'])) {
    $formManager = new FormManager();
    $response = $formManager->createForm($data['form_name'], $data['fields'], $data['email_fields']);
    echo json_encode($response);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid payload.']);
}
