<?php

require_once __DIR__ . '/../app/bootstrap.php';      // public/*.php

Session::start();

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

header('Location: login.php');
exit;