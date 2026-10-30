<aside class="sidebar">

    <div class="sidebar-logo">
        <div class="logo-small">
            CS
        </div>

        <span>Card System</span>
    </div>

    <nav class="sidebar-nav">

        <a
            href="<?= url('dashboard.php') ?>"
            class="nav-item <?= ($pageTitle ?? '') === 'Dashboard' ? 'active' : '' ?>"
        >
            <span>▦</span>
            <span>Dashboard</span>
        </a>

        <a
            href="<?= url('cards/index.php') ?>"
            class="nav-item <?= ($pageTitle ?? '') === 'Cards' ? 'active' : '' ?>"
        >
            <span>▣</span>
            <span>Cards</span>
        </a>

        <a
            href="<?= url('transactions/index.php') ?>"
            class="nav-item <?= ($pageTitle ?? '') === 'Transactions' ? 'active' : '' ?>"
        >
            <span>↔</span>
            <span>Transactions</span>
        </a>

        <a
            href="<?= url('reports/index.php') ?>"
            class="nav-item"
        >
            <span>▤</span>
            <span>Reports</span>
        </a>

        <div class="nav-divider"></div>

        <a
            href="#"
            class="nav-item"
        >
            <span>♙</span>
            <span>Users</span>
        </a>

        <a
            href="#"
            class="nav-item"
        >
            <span>◉</span>
            <span>Audit Logs</span>
        </a>

    </nav>

</aside>