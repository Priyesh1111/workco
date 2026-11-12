<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= esc($title ?? 'Invoice App') ?></title>

    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <style>
        body {
            min-width: 1024px;
            /* Ensures consistent desktop layout */
            overflow-x: auto;
        }

        /* 1. Fixed Header */
        .app-header {
            height: 60px;
            z-index: 1030;
        }

        /* 2. Fixed Sidebar */
        .app-sidebar {
            position: fixed;
            top: 60px;
            bottom: 50px;
            left: 0;
            width: 240px;
            overflow-y: auto;
            z-index: 1020;
        }

        /* 3. Main Content Space */
        .app-main {
            margin-left: 240px;
            margin-top: 60px;
            margin-bottom: 50px;
            min-height: calc(100vh - 110px);
        }

        /* 4. Fixed Footer */
        .app-footer {
            height: 50px;
            z-index: 1030;
        }

        /* Sidebar hover state */
        .app-sidebar .nav-link:hover {
            background-color: #e9ecef;
            border-radius: 6px;
        }
    </style>
</head>

<body>

    <header
        class="app-header bg-dark text-white px-4 fixed-top d-flex justify-content-between align-items-center shadow-sm">
        <h4 class="mb-0">Invoice Management System</h4>

        <?php if (session()->get('isLoggedIn')): ?>
            <div class="d-flex align-items-center gap-3">
                <span>
                    Welcome, <strong><?= esc(session()->get('user_name')) ?></strong>
                </span>

                <form action="<?= site_url('logout') ?>" method="post" class="m-0">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-outline-light btn-sm">
                        Logout
                    </button>
                </form>
            </div>
        <?php endif; ?>
    </header>

    <!-- Main content -->
    <!-- 2. Fixed Sidebar (5 Clickable Contents) -->
    <aside class="app-sidebar bg-light border-end p-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link text-dark fw-medium" href="<?= site_url('dashboard') ?>">
                    Dashboard
                </a>
            </li>
            <hr class="my-2">
            <li class="nav-item">
                <a class="nav-link text-dark fw-medium" href="<?= site_url('customers') ?>">
                    Customers
                </a>
            </li>
            <hr class="my-2">
            <li class="nav-item">
                <a href="<?= site_url('products') ?>" class="nav-link">Products</a>
            </li>
            <hr class="my-2">
            <li class="nav-item">
                <a href="<?= site_url('invoices') ?>" class="nav-link">Invoices</a>
            </li>
            <hr class="my-2">
            <li class="nav-item">
                <a href="<?= site_url('settings') ?>" class="nav-link">Settings</a>
            </li>
        </ul>
    </aside>

    <!-- 3. Main Content Space -->
    <main class="app-main p-4">

        <!-- Success message -->
        <?php if ($success = session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= esc($success) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- General error message -->
        <?php if ($error = session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= esc($error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Dynamic View Content -->
        <?= $this->renderSection('content') ?>

    </main>

    <!-- Footer -->
    <footer class="app-footer bg-dark text-white fixed-bottom d-flex justify-content-center align-items-center">
        <p class="mb-0">&copy; <?= date('Y') ?> Invoice App</p>
    </footer>

    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
</body>

</html>