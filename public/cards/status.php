<?php

require_once __DIR__ . '/../../app/bootstrap.php';
require_once __DIR__ . '/../../app/Middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../app/Models/Card.php';

AuthMiddleware::handle();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    exit('Method Not Allowed');
}

$token = $_POST['csrf_token'] ?? '';

if (!is_string($token) || !Security::verifyCsrfToken($token)) {
    http_response_code(403);
    exit('Invalid request.');
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1],
]);

$newStatus = match ($_POST['action'] ?? '') {
    'block'   => 'BLOCKED',
    'unblock' => 'ACTIVE',
    default   => null,
};

if (!is_int($id) || $newStatus === null) {
    http_response_code(400);
    exit('Bad request.');
}

$requiredPermission = $newStatus === 'BLOCKED'
    ? Permission::BlockCard
    : Permission::UnblockCard;

if (!Auth::can($requiredPermission)) {
    error_log(sprintf(
        'Access denied: user=%s tried %s on card_id=%d',
        $_SESSION['username'] ?? '?',
        $requiredPermission->name,
        $id
    ));

    http_response_code(403);
    exit('You are not allowed to do this.');
}

$cardModel = new Card();

if ($cardModel->findById($id) === null) {
    http_response_code(404);
    exit('Card not found.');
}

$updated = $cardModel->updateStatus($id, $newStatus, $_SESSION['username']);

error_log(sprintf(
    'Card status change: card_id=%d new_status=%s by=%s result=%s',
    $id,
    $newStatus,
    $_SESSION['username'],
    $updated ? 'ok' : 'rejected'
));

if ($updated) {
    Session::flash('success', $newStatus === 'BLOCKED'
        ? 'Card has been blocked.'
        : 'Card has been unblocked.');
} else {
    Session::flash('error', 'The status could not be changed. Someone may have changed it already; check the current status below.');
}

header('Location: ' . url('cards/show.php?id=' . $id));
exit;