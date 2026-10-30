<?php

require_once __DIR__ . '/../app/bootstrap.php';      // public/*.php

Session::start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method Not Allowed');
}

$token = $_POST['csrf_token'] ?? '';

if (
    !is_string($token)
    || !Security::verifyCsrfToken($token)
) {
    http_response_code(403);
    exit('Invalid request.');
}

Session::destroy();

header('Location: login.php');
exit;
