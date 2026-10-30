<?php

require_once __DIR__ . '/../../app/bootstrap.php';   // public/cards/, public/transactions/
require_once __DIR__ . '/../../app/Middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../app/Models/Transaction.php';

AuthMiddleware::requirePermission(Permission::ViewTransactions);

$query = $_GET['q'] ?? '';
$status = $_GET['status'] ?? '';
$dateFrom = $_GET['date_from'] ?? '';
$dateTo = $_GET['date_to'] ?? '';

$query = is_string($query) ? trim($query) : '';
$status = is_string($status) ? $status : '';
$dateFrom = is_string($dateFrom) ? $dateFrom : '';
$dateTo = is_string($dateTo) ? $dateTo : '';

$allowedStatuses = ['', 'SUCCESS', 'FAILED', 'PENDING'];

if (!in_array($status, $allowedStatuses, true)) {
    $status = '';
}

$validDate = static function (string $date): bool {
    $parsed = DateTimeImmutable::createFromFormat('!Y-m-d', $date);

    return $parsed !== false
        && $parsed->format('Y-m-d') === $date;
};

if (!$validDate($dateFrom)) {
    $dateFrom = '';
}

if (!$validDate($dateTo)) {
    $dateTo = '';
}

if ($dateFrom !== '' && $dateTo !== '' && $dateFrom > $dateTo) {
    [$dateFrom, $dateTo] = [$dateTo, $dateFrom];
}

$transactionModel = new Transaction();

$transactions = $transactionModel->search(
    $query,
    $status,
    $dateFrom,
    $dateTo
);

$pageTitle = 'Transactions';

ob_start();
?>

    <div class="page-header">
        <div>
            <h1>Transactions</h1>
            <p>Search and review card transactions</p>
        </div>
    </div>

    <div class="content-card">

        <form method="GET" action="index.php" class="search-form">

            <input
                type="search"
                name="q"
                value="<?= Security::escape($query) ?>"
                placeholder="Search ID, card number or type..."
            >

            <select name="status">
                <option value="">All statuses</option>

                <?php foreach (['SUCCESS', 'FAILED', 'PENDING'] as $option): ?>
                    <option
                        value="<?= $option ?>"
                        <?= $status === $option ? 'selected' : '' ?>
                    >
                        <?= $option ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>
                From
                <input
                    type="date"
                    name="date_from"
                    value="<?= Security::escape($dateFrom) ?>"
                >
            </label>

            <label>
                To
                <input
                    type="date"
                    name="date_to"
                    value="<?= Security::escape($dateTo) ?>"
                >
            </label>

            <button type="submit">Filter</button>

            <a href="index.php">Clear</a>

        </form>

    </div>

    <div class="content-card">

        <div class="table-header">
            <div>
                <h2>Transaction List</h2>
                <span>
                    <?= count($transactions) ?> transactions found
                </span>
            </div>
        </div>

        <div class="table-wrapper">

            <table>
                <thead>
                    <tr>
                        <th>Transaction ID</th>
                        <th>Card Number</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Response Code</th>
                        <th>Date &amp; Time</th>
                    </tr>
                </thead>

                <tbody>

                <?php if (empty($transactions)): ?>

                    <tr>
                        <td colspan="7">No transactions found.</td>
                    </tr>

                <?php else: ?>

                    <?php foreach ($transactions as $transaction): ?>

                        <tr>
                            <td>
                                #<?= (int) $transaction['id'] ?>
                            </td>

                            <td>
                                <strong>
                                    <?= Security::escape(Format::cardNumber($transaction['card_number'])) ?>
                                </strong>
                            </td>

                            <td>
                                <?= Security::escape($transaction['type']) ?>
                            </td>

                            <td>
                                <?= number_format(
                                    (float) $transaction['amount'],
                                    0,
                                    '.',
                                    ' '
                                ) ?>
                                <?= Security::escape($transaction['currency']) ?>
                            </td>

                            <td>
                                <span class="status status-<?= strtolower(
                                    Security::escape($transaction['status'])
                                ) ?>">
                                    <?= Security::escape($transaction['status']) ?>
                                </span>
                            </td>

                            <td>
                                <?= Security::escape($transaction['response_code']) ?>
                            </td>

                            <td>
                                <?= Security::escape($transaction['created_at']) ?>
                            </td>
                        </tr>

                    <?php endforeach; ?>

                <?php endif; ?>

                </tbody>
            </table>

        </div>

    </div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../../views/layouts/app.php';
