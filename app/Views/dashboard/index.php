<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<style>
    .stat-card {
        border: 0;
        border-left: 4px solid var(--stat-color);
        box-shadow: 0 1px 6px rgba(0, 0, 0, .08);
    }

    .stat-card .stat-label {
        font-size: .75rem;
        letter-spacing: .05em;
        text-transform: uppercase;
        color: #6c757d;
    }

    .stat-card .stat-value {
        font-size: 1.35rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .invoice-scroll {
        max-height: 480px;
        overflow-y: auto;
    }

    .invoice-scroll thead th {
        position: sticky;
        top: 0;
        z-index: 1;
    }
</style>

<div class="container-fluid">

    <h2 class="mb-4">Dashboard</h2>

    <!-- Summary cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="card stat-card h-100" style="--stat-color:#0d6efd">
                <div class="card-body">
                    <div class="stat-label">Total Invoices</div>
                    <div class="stat-value" data-stat="total_invoices"><?= esc($stats['total_invoices']) ?></div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card stat-card h-100" style="--stat-color:#198754">
                <div class="card-body">
                    <div class="stat-label">Paid Amount</div>
                    <div class="stat-value" data-stat="paid_amount"><?= esc($stats['paid_amount']) ?></div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card stat-card h-100" style="--stat-color:#fd7e14">
                <div class="card-body">
                    <div class="stat-label">Unpaid Amount</div>
                    <div class="stat-value" data-stat="unpaid_amount"><?= esc($stats['unpaid_amount']) ?></div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card stat-card h-100" style="--stat-color:#dc3545">
                <div class="card-body">
                    <div class="stat-label">Overdue Invoices</div>
                    <div class="stat-value" data-stat="overdue_count"><?= esc($stats['overdue_count']) ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Invoice table, newest first -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center bg-white">
            <h5 class="mb-0">Invoices</h5>
            <a href="<?= site_url('invoices/new') ?>" class="btn btn-primary btn-sm">+ Create Invoice</a>
        </div>
        <div class="card-body p-0 invoice-scroll">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Invoice No.</th>
                        <th>Customer</th>
                        <th>Issue Date</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th class="text-end">Grand Total</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody id="invoice-rows">
                    <?= view('dashboard/_invoice_rows', ['invoices' => $invoices, 'today' => $today]) ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
    (function () {
        var FEED_URL = <?= json_encode(site_url('dashboard/data')) ?>;
        var LOGIN_URL = <?= json_encode(site_url('login')) ?>;
        var INTERVAL_MS = 10000;
        var busy = false;

        function refresh() {
            if (busy || document.hidden) { return; }
            busy = true;

            fetch(FEED_URL, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                cache: 'no-store'
            })
                .then(function (res) {
                    var type = res.headers.get('content-type') || '';
                    if (!res.ok || type.indexOf('application/json') === -1) {
                        // Session expired: the auth filter redirected us to the login page.
                        window.location.href = LOGIN_URL;
                        throw new Error('not json');
                    }
                    return res.json();
                })
                .then(function (data) {
                    Object.keys(data.stats).forEach(function (key) {
                        var el = document.querySelector('[data-stat="' + key + '"]');
                        if (el) { el.textContent = data.stats[key]; }
                    });
                    document.getElementById('invoice-rows').innerHTML = data.rows_html;
                })
                .catch(function () { /* keep showing the last good data */ })
                .then(function () { busy = false; });
        }

        setInterval(refresh, INTERVAL_MS);
        document.addEventListener('visibilitychange', refresh);
    })();
</script>

<?= $this->endSection() ?>
