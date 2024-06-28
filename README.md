# Dynamic Form Creation and Submission System

This repository contains a dynamic form creation and submission system built with Core PHP, jQuery, and Docker. It allows users to create, manage, and submit forms with client-side and server-side validation, store submissions in a database, and send email notifications for specified fields.

## Features

- **Dynamic Form Creation**: Create forms with various field types (input, textarea, select, radio, checkbox) via API calls.
- **Field Validation**: Define validation rules for each field.
- **Email Notifications**: Specify fields for email notifications upon form submission.
- **Submission Handling**: Client-side and server-side validation, store submissions in the database, and prevent bots using Google reCAPTCHA.
- **Form Management**: List and display submitted entries.

## Project Structure

project/
├── api/
│ ├── create-form.php
│ ├── get-forms.php
│ ├── get-submissions.php
│ └── submit-form.php
├── classes/
│ ├── Database.php
│ ├── FormManager.php
│ ├── SubmissionManager.php
│ └── Validator.php
├── js/
│ └── form.js
├── templates/
│ └── form.html
├── docker-compose.yml
├── Dockerfile
└── README.md

## Setup Instructions

### Prerequisites

- Docker
- Docker Compose

### Step 1: Clone the Repository

```bash
git clone https://github.com/raanawaleed/dynamic_form_creator.git
cd dynamic-form
Step 2: Build and Run Docker Containers
bash
Copy code
docker-compose up --build
This will build the Docker containers and start the application.

Step 3: Access the Application
Open your browser and navigate to http://localhost:8080.

API Endpoints
Create Form
Endpoint: POST /api/create-form.php

Payload: JSON object with form fields, validation rules, and email settings.

Example:

json
Copy code
{
    "title": "Contact Form",
    "fields": [
        {"type": "input", "name": "name", "label": "Name", "required": true},
        {"type": "textarea", "name": "message", "label": "Message", "required": true},
        {"type": "email", "name": "email", "label": "Email", "required": true}
    ],
    "email_fields": ["name", "email", "message"]
}
Get Forms
Endpoint: GET /api/get-forms.php

Get Submissions
Endpoint: GET /api/get-submissions.php?form_id={form_id}

Submit Form
Endpoint: POST /api/submit-form.php

Payload: Form data including form_id and g-recaptcha-response.

Environment Variables
RECAPTCHA_SECRET_KEY: Your Google reCAPTCHA secret key - my personal account key is removed.
Code Highlights
Database Connection (classes/Database.php)
Handles database connection using PDO.

Form Management (classes/FormManager.php)
Creates, retrieves, and manages forms.

Submission Management (classes/SubmissionManager.php)
Handles form submissions, validation, and email notifications.

Client-Side Script (js/form.js)
Handles dynamic form rendering, client-side validation, and AJAX form submission.

Docker Configuration (Dockerfile and docker-compose.yml)
Sets up the Docker environment for the application.
