<?php

require_once __DIR__ . '/../../app/Helpers/Session.php';
require_once __DIR__ . '/../../app/Helpers/Security.php';
require_once __DIR__ . '/../../app/Middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../app/Models/Card.php';

AuthMiddleware::handle();

$cardModel = new Card();

$query = trim($_GET['q'] ?? '');

$cards = $cardModel->search($query);

$pageTitle = 'Cards';

require_once __DIR__ . '/../../views/layouts/sidebar.php';
require_once __DIR__ . '/../../views/layouts/header.php';

?>

<main class="content">

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
                                <strong>
                                    <?= Security::escape(
                                        substr($card['card_number'], 0, 6)
                                        . '******'
                                        . substr($card['card_number'], -4)
                                    ) ?>
                                </strong>
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

</main>