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
                <?= Security::escape(Auth::role()?->label() ?? 'Unknown role') ?>
            </span>

        </div>

        <div class="user-avatar">
            <?= Security::escape(mb_strtoupper(mb_substr($_SESSION['username'] ?? 'U', 0, 1))) ?>
        </div>

        <form
            action="<?= url('logout.php') ?>"
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