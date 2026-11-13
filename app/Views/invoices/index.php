<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Invoices</h3>
        <a href="<?= site_url('invoices/new') ?>" class="btn btn-primary">
            + Create Invoice
        </a>
    </div>

    <?php if ($success = session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= esc($success) ?></div>
    <?php endif; ?>

    <?php if ($error = session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= esc($error) ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Invoice No.</th>
                        <th>Issue Date</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Grand Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($invoices)): ?>
                        <?php foreach ($invoices as $invoice): ?>
                            <tr>
                                <td><?= esc($invoice['invoice_no']) ?></td>
                                <td><?= esc($invoice['issue_date']) ?></td>
                                <td><?= esc($invoice['due_date']) ?></td>
                                <td>
                                    <span class="badge bg-secondary">
                                        <?= esc(ucfirst($invoice['status'])) ?>
                                    </span>
                                </td>
                                <td>₹<?= number_format((float) $invoice['grand_total'], 2) ?></td>
                                <td>
                                    <a href="<?= site_url('invoices/view/' . $invoice['id']) ?>"
                                        class="btn btn-sm btn-info">View</a>
                                         <a href="<?= site_url('invoices/pdf/' . $invoice['id']) ?>"
        class="btn btn-sm btn-outline-primary">PDF</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">
                                No invoices created yet.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <?= $pager->links() ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>