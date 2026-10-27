<aside class="sidebar">

    <div class="sidebar-logo">
        <div class="logo-small">
            CS
        </div>

        <span>Card System</span>
    </div>

    <nav class="sidebar-nav">

        <a
            href="/service-test-project/public/dashboard.php"
            class="nav-item <?= ($pageTitle ?? '') === 'Dashboard' ? 'active' : '' ?>"
        >
            <span>▦</span>
            <span>Dashboard</span>
        </a>

        <a
            href="/service-test-project/public/cards/index.php"
            class="nav-item <?= ($pageTitle ?? '') === 'Cards' ? 'active' : '' ?>"
        >
            <span>▣</span>
            <span>Cards</span>
        </a>

        <a
            href="/service-test-project/public/transactions/index.php"
            class="nav-item <?= ($pageTitle ?? '') === 'Transactions' ? 'active' : '' ?>"
        >
            <span>↔</span>
            <span>Transactions</span>
        </a>

        <a
            href="#"
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