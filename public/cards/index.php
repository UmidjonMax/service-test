<?php

require_once __DIR__ . '/../../app/bootstrap.php';   // public/cards/, public/transactions/
require_once __DIR__ . '/../../app/Middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../app/Models/Card.php';

AuthMiddleware::requirePermission(Permission::ViewCards);

$cardModel = new Card();

$query = trim($_GET['q'] ?? '');

$cards = $cardModel->search($query);

$pageTitle = 'Cards';

ob_start();
?>

    <div class="page-header">

        <div>
            <h1>Cards</h1>
            <p>Manage and view card information</p>
        </div>

    </div>

    <div class="content-card">

        <form
            method="GET"
            action="index.php"
            class="search-form"
        >

            <input
                type="text"
                name="q"
                value="<?= Security::escape($query) ?>"
                placeholder="Search card number or cardholder..."
            >

            <button type="submit">
                Search
            </button>

            <?php if ($query !== ''): ?>

                <a href="index.php">
                    Clear
                </a>

            <?php endif; ?>

        </form>

    </div>

    <div class="content-card">

        <div class="table-header">

            <div>
                <h2>Card List</h2>

                <span>
                    <?= count($cards) ?> cards
                </span>
            </div>

        </div>

        <div class="table-wrapper">

            <table>

                <thead>

                    <tr>
                        <th>ID</th>
                        <th>Card Number</th>
                        <th>Cardholder</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Created</th>
                    </tr>

                </thead>

                <tbody>

                <?php if (empty($cards)): ?>

                    <tr>
                        <td colspan="6">
                            No cards found.
                        </td>
                    </tr>

                <?php else: ?>

                    <?php foreach ($cards as $card): ?>

                        <tr>

                            <td>
                                <?= (int) $card['id'] ?>
                            </td>

                            <td>
                                    <a href="<?= url('cards/show.php?id=' . (int) $card['id']) ?>">
                                <strong>
                                    <?= Security::escape(Format::cardNumber($card['card_number'])) ?>
                                </strong>
                                    </a>
                            </td>

                            <td>
                                <?= Security::escape(
                                    $card['holder_name']
                                ) ?>
                            </td>

                            <td>
                                <?= Security::escape(
                                    $card['type']
                                ) ?>
                            </td>

                            <td>

                                <span class="status status-<?= strtolower(
                                    Security::escape($card['status'])
                                ) ?>">
                                    <?= Security::escape(
                                        $card['status']
                                    ) ?>
                                </span>

                            </td>

                            <td>
                                <?= Security::escape(
                                    $card['created_at']
                                ) ?>
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