<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= esc($title ?? 'Invoice App') ?></title>

    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <style>
        * {
            box-sizing: border-box;
            padding: 0;
            margin: 0;
        }

        footer {
            position: fixed;
            bottom: 0;
            width: 100vw;
        }

        .login-f {
            margin-top: 100px;
            display: flex;
            align-items: center;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header class="bg-dark text-white p-3">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Invoice Management System</h3>
        </div>
    </header>
    <div class="login-f row justify-content-center">
        <div class="col-md-5">

            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">User Login</h4>
                </div>

                <div class="card-body">

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?= esc(session()->getFlashdata('error')) ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= site_url('login') ?>" method="post">

                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Enter your email"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control"
                                placeholder="Enter your password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            Login
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </div>

    <footer class="bg-dark text-white text-center p-3">
        <p class="mb-0">&copy;
            <?= date('Y') ?> Invoice App
        </p>
    </footer>
</body>

</html>