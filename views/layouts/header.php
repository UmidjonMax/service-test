<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        <?= Security::escape(
            $pageTitle ?? 'Card System'
        ) ?>
        | Card System
    </title>

    <link
        rel="stylesheet"
        href="/service-test-project/public/css/app.css"
    >
</head>

<header class="topbar">

    <div class="topbar-title">
        <?= Security::escape(
            $pageTitle ?? 'Dashboard'
        ) ?>
    </div>

    <div class="topbar-user">

        <div class="user-info">

            <span class="user-name">
                <?= Security::escape(
                    $_SESSION['username'] ?? ''
                ) ?>
            </span>

            <span class="user-role">
                <?= Security::escape(
                    $_SESSION['role'] ?? ''
                ) ?>
            </span>

        </div>

        <div class="user-avatar">
            <?= strtoupper(
                substr(
                    $_SESSION['username'] ?? 'U',
                    0,
                    1
                )
            ) ?>
        </div>

        <form
            action="/service-test-project/public/logout.php"
            method="POST"
        >

            <input
                type="hidden"
                name="csrf_token"
                value="<?= Security::csrfToken() ?>"
            >

            <button
                type="submit"
                class="logout-button"
            >
                Logout
            </button>

        </form>

    </div>

</header>