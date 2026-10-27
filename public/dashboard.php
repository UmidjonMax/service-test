<?php

require_once __DIR__ . '/../app/Helpers/Security.php';
require_once __DIR__ . '/../app/Middleware/AuthMiddleware.php';
require_once __DIR__ . '/../app/Models/Card.php';
require_once __DIR__ . '/../app/Models/Transaction.php';

AuthMiddleware::handle();

$pageTitle = 'Dashboard';

$cardModel = new Card();
$transactionModel = new Transaction();

$active8600Cards = $cardModel->countActiveByPrefix('8600');
$active5614Cards = $cardModel->countActiveByPrefix('5614');

$successLastMinute =
    $transactionModel->countLastMinuteByStatus('SUCCESS');

$failedLastMinute =
    $transactionModel->countLastMinuteByStatus('FAILED');

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        <?= Security::escape($pageTitle) ?>
        - Card System
    </title>

    <link
        rel="stylesheet"
        href="css/app.css"
    >

</head>

<body>

<div class="app">

    <?php require_once __DIR__ .
        '/../views/layouts/sidebar.php'; ?>

    <div class="main">

        <?php require_once __DIR__ .
            '/../views/layouts/header.php'; ?>

        <main class="content">

            <div class="page-heading">

                <div>

                    <h1>Dashboard</h1>

                    <p>
                        Overview of your card system
                    </p>

                </div>

            </div>


            <!-- Statistics -->

            <div class="stats-grid">


                <!-- Active 8600 Cards -->

                <div class="stat-card">

                    <div class="stat-label">
                        Active 8600 Cards
                    </div>

                    <div class="stat-value">
                        <?= $active8600Cards ?>
                    </div>

                    <div class="stat-description">
                        Active cards with 8600 prefix
                    </div>

                </div>


                <!-- Active 5614 Cards -->

                <div class="stat-card">

                    <div class="stat-label">
                        Active 5614 Cards
                    </div>

                    <div class="stat-value">
                        <?= $active5614Cards ?>
                    </div>

                    <div class="stat-description">
                        Active cards with 5614 prefix
                    </div>

                </div>


                <!-- Successful -->

                <div class="stat-card">

                    <div class="stat-label">
                        Success / min
                    </div>

                    <div class="stat-value">
                        <?= $successLastMinute ?>
                    </div>

                    <div class="stat-description">
                        Successful transactions in last minute
                    </div>

                </div>


                <!-- Failed -->

                <div class="stat-card">

                    <div class="stat-label">
                        Failed / min
                    </div>

                    <div class="stat-value">
                        <?= $failedLastMinute ?>
                    </div>

                    <div class="stat-description">
                        Failed transactions in last minute
                    </div>

                </div>

            </div>


            <!-- System Information -->

            <div class="content-card">

                <div class="card-header">

                    <div>

                        <h2>
                            System Overview
                        </h2>

                        <p>
                            Current card and transaction activity
                        </p>

                    </div>

                </div>

            </div>

        </main>

    </div>

</div>

</body>

</html>