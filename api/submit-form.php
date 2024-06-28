<?php
require_once '../classes/SubmissionManager.php';

header('Content-Type: application/json');

// Verify reCAPTCHA
$recaptchaSecret = 'SECRET_KEY'; //My personal account key is removed 


$formId = $_POST['form_id'] ?? null;
$formData = $_POST;
$captchaResponse = $_POST['g-recaptcha-response'] ?? '';

if (!$formId) {
    echo json_encode(['status' => 'error', 'message' => 'Form ID is required.']);
    exit;
}

// Validate reCAPTCHA

$recaptchaUrl = 'https://www.google.com/recaptcha/api/siteverify';
$recaptchaData = [
    'secret' => $recaptchaSecret,
    'response' => $recaptchaResponse
];
$recaptchaOptions = [
    'http' => [
        'method' => 'POST',
        'content' => http_build_query($recaptchaData)
    ]
];
$recaptchaContext = stream_context_create($recaptchaOptions);
$recaptchaResult = file_get_contents($recaptchaUrl, false, $recaptchaContext);
$recaptchaResultJson = json_decode($recaptchaResult);

if (!$recaptchaResultJson->success) {
    echo json_encode(['status' => 'error', 'message' => 'reCAPTCHA verification failed.']);
    exit;
}

$submissionManager  = new SubmissionManager();
$result = $submissionManager->submitForm($form_id, $formData);

echo json_encode($result);
