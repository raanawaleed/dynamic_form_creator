<?php
require_once 'Database.php';

class FormManager
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function createForm($name, $fields, $emailFields)
    {
        $query = 'INSERT INTO forms (name, fields, email_fields) VALUES (:name, :fields, :email_fields)';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':fields', json_encode($fields));
        $stmt->bindParam(':email_fields', json_encode($emailFields));

        if ($stmt->execute()) {
            return ['status' => 'success', 'message' => 'Form created successfully.'];
        } else {
            return ['status' => 'error', 'message' => 'Failed to create form.'];
        }
    }

    public function listForms()
    {
        $query = 'SELECT * FROM forms';
        $stmt = $this->conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getForm($id)
    {
        $query = 'SELECT * FROM forms WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function saveSubmission($data){
        
    }
}
