<?php
require_once 'Database.php';
require_once 'Validator.php';
require_once 'FormManager.php';

class SubmissionManager
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function submitForm($formId, $data)
    {
        $formManager = new FormManager();
        $form = $formManager->getForm($formId);

        if (!$form) {
            return ['status' => 'error', 'message' => 'Form not found.'];
        }

        $validator = new Validator();
        $validationResult = $validator->validate($data, json_decode($form['fields'], true));

        if ($validationResult['status'] === 'error') {
            return $validationResult;
        }

        $query = 'INSERT INTO submissions (form_id, data) VALUES (:form_id, :data)';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':form_id', $formId);
        $stmt->bindParam(':data', json_encode($data));

        if ($stmt->execute()) {
            // Send email if necessary
            $emailFields = json_decode($form['email_fields'], true);
            $emailData = [];
            foreach ($emailFields as $field) {
                if (isset($data[$field])) {
                    $emailData[$field] = $data[$field];
                }
            }
            $emailSent = $this->sendSubmissionEmail($emailData);

            if ($emailSent) {
                return ['status' => 'success', 'message' => 'Form submitted successfully.'];
            } else {
                return ['status' => 'warning', 'message' => 'Form submitted, but email could not be sent.'];
            }
        } else {
            return ['status' => 'error', 'message' => 'Failed to submit form.'];
        }
    }

    private function sendSubmissionEmail($data)
    {
        // Send formatted HTML email
        $to = 'raanawaleed@example.com';
        $subject = 'New Form Submission';
        $message = '<html><body>';
        $message .= '<h1>New Form Submission</h1>';
        
        foreach ($data as $key => $value) {
            $message .= "<p>{$key}: " . htmlspecialchars($value) . "</p>";
        }
        
        $message .= '</body></html>';

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: webmaster@example.com' . "\r\n";

        // Send email
        return mail($to, $subject, $message, $headers);
    }
}
