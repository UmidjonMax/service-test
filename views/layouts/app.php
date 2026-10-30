<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= Security::escape($pageTitle ?? 'Dashboard') ?> | Card System</title>
    <link rel="stylesheet" href="<?= url('css/app.css') ?>">
</head>
<body>

<div class="app">

    <?php require __DIR__ . '/sidebar.php'; ?>

    <div class="main">

        <?php require __DIR__ . '/header.php'; ?>

        <main class="content">
                        <?php foreach (['success', 'error'] as $type): ?>
                <?php $message = Session::pullFlash($type); ?>
                <?php if ($message !== null): ?>
                    <p class="alert alert-<?= $type ?>" role="status">
                        <?= Security::escape($message) ?>
                    </p>
                <?php endif; ?>
            <?php endforeach; ?>
            <?= $content ?>
        </main>

    </div>

</div>

</body>
</html>