<?php
class Validator
{
    public function validate($data, $fields)
    {
        $errors = [];

        foreach ($fields as $field) {
            if (isset($data[$field['name']])) {
                if ($field['type'] === 'input' && empty($data[$field['name']])) {
                    $errors[] = $field['name'] . ' is required.';
                }
            } else {
                $errors[] = $field['name'] . ' is missing.';
            }
        }

        if (!empty($errors)) {
            return ['status' => 'error', 'message' => implode(', ', $errors)];
        }

        return ['status' => 'success'];
    }
}
