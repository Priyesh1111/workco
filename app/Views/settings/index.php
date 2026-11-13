<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Company Settings</h3>
        <?php if (!empty($settings)): ?>
            <a href="<?= site_url('settings/edit/' . $settings['id']) ?>" class="btn btn-primary">
                Edit Settings
            </a>
        <?php else: ?>
            <a href="<?= site_url('settings/create') ?>" class="btn btn-primary">
                Setup Settings
            </a>
        <?php endif; ?>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif; ?>

    <?php if (empty($settings)): ?>
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <p class="text-muted mb-3">No company settings configured yet.</p>
                <a href="<?= site_url('settings/create') ?>" class="btn btn-primary">Configure Now</a>
            </div>
        </div>
    <?php else: ?>
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="mb-3">Company Information</h5>
                <div class="row align-items-center mb-4">
                    <div class="col-md-3">
                        <label class="form-label text-muted d-block">Company Logo</label>
                        <?php if (!empty($settings['logo_path'])): ?>
                            <img src="<?= base_url($settings['logo_path']) ?>" alt="Company Logo" class="img-thumbnail"
                                style="max-height: 100px; max-width: 200px;">
                        <?php else: ?>
                            <span class="text-muted fst-italic">No logo uploaded</span>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted">Company Name</label>
                                <p class="fw-semibold mb-0"><?= esc($settings['company_name'] ?: '-') ?></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted">Email</label>
                                <p class="fw-semibold mb-0"><?= esc($settings['email'] ?: '-') ?></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted">Phone</label>
                                <p class="fw-semibold mb-0"><?= esc($settings['phone'] ?: '-') ?></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted">Tax ID / GSTIN</label>
                                <p class="fw-semibold mb-0"><?= esc($settings['tax_id'] ?: '-') ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-2">
                        <label class="form-label text-muted">Company Address</label>
                        <p class="fw-semibold mb-0"><?= nl2br(esc($settings['address'] ?: '-')) ?></p>
                    </div>
                </div>

                <hr>
                <h5 class="mb-3">Invoice Settings</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Invoice Prefix</label>
                        <p class="fw-semibold mb-0"><?= esc($settings['invoice_prefix'] ?: 'INV') ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Currency</label>
                        <p class="fw-semibold mb-0">Indian Rupee (₹)</p>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label text-muted">Bank Details</label>
                        <p class="fw-semibold mb-0"><?= nl2br(esc($settings['bank_details'] ?: '-')) ?></p>
                    </div>
                    <div class="col-12 mb-0">
                        <label class="form-label text-muted">Invoice Footer Note</label>
                        <p class="fw-semibold mb-0"><?= nl2br(esc($settings['footer_note'] ?: '-')) ?></p>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>