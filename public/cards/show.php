<?php

require_once __DIR__ . '/../../app/bootstrap.php';
require_once __DIR__ . '/../../app/Middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../app/Models/Card.php';

AuthMiddleware::requirePermission(Permission::ViewCards);

$cardModel = new Card();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1],
]);

$card = is_int($id) ? $cardModel->findById($id) : null;

if ($card === null) {
    http_response_code(404);
}

$pageTitle = 'Cards';

ob_start();
?>

<p class="back-link">
    <a href="<?= url('cards/index.php') ?>">← All cards</a>
</p>

<?php if ($card === null): ?>

    <div class="content-card">
        <h2>Card not found</h2>
        <p class="muted">This card doesn't exist or the link is wrong.</p>
    </div>

<?php else: ?>

    <div class="content-card">

        <div class="card-detail-header">
            <div>
                <h1 class="card-detail-number">
                    <?= Security::escape(Format::cardNumber($card['card_number'])) ?>
                </h1>
                <p class="muted"><?= Security::escape($card['holder_name']) ?></p>
            </div>

            <?= Format::status($card['status']) ?>
        </div>

        <dl class="details">
            <div>
                <dt>Card ID</dt>
                <dd><?= (int) $card['id'] ?></dd>
            </div>
            <div>
                <dt>Type</dt>
                <dd><?= Security::escape($card['type']) ?></dd>
            </div>
            <div>
                <dt>Issued</dt>
                <dd><?= Security::escape($card['created_at']) ?></dd>
            </div>
            <?php if (isset($card['status_changed_at'])): ?>
                <div>
                    <dt>Last status change</dt>
                    <dd>
                        <?= Security::escape($card['status_changed_at']) ?>
                        by <?= Security::escape($card['status_changed_by'] ?? '') ?>
                    </dd>
                </div>
            <?php endif; ?>
        </dl>

                <?php
        $canBlock = $cardModel->canChangeStatus($card, 'BLOCKED');
        $canUnblock = $cardModel->canChangeStatus($card, 'ACTIVE');
        ?>

        <?php if ($canBlock && Auth::can(Permission::BlockCard)): ?>

            <form method="POST" action="<?= url('cards/status.php') ?>" class="card-actions">
                <input type="hidden" name="csrf_token" value="<?= Security::escape(Security::csrfToken()) ?>">
                <input type="hidden" name="id" value="<?= (int) $card['id'] ?>">
                <button type="submit" name="action" value="block" class="btn btn-danger">
                    Block card
                </button>
            </form>

        <?php elseif ($canUnblock && Auth::can(Permission::UnblockCard)): ?>

            <form method="POST" action="<?= url('cards/status.php') ?>" class="card-actions">
                <input type="hidden" name="csrf_token" value="<?= Security::escape(Security::csrfToken()) ?>">
                <input type="hidden" name="id" value="<?= (int) $card['id'] ?>">
                <button type="submit" name="action" value="unblock" class="btn btn-success">
                    Unblock card
                </button>
            </form>

        <?php elseif ($canBlock || $canUnblock): ?>

            <p class="muted">Your role can't change this card's status. Ask an administrator.</p>

        <?php else: ?>

            <p class="muted">This card's status can't be changed.</p>

        <?php endif; ?>

    </div>

<?php endif; ?>

<?php
$content = ob_get_clean();
require __DIR__ . '/../../views/layouts/app.php';