
<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <h3 class="mb-4">Company Settings</h3>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif; ?>

    <?php
    $value = function ($key, $default = '') use ($settings) {
        return old($key, $settings[$key] ?? $default);
    };
    ?>

    <div class="card shadow-sm">
        <div class="card-body">

            <form method="post" action="<?= site_url('settings/save') ?>">
                <?= csrf_field() ?>

                <h5 class="mb-3">Company Information</h5>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Company Name</label>
                        <input type="text" name="company_name"
                               class="form-control"
                               value="<?= esc($value('company_name')) ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email"
                               class="form-control"
                               value="<?= esc($value('email')) ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone"
                               class="form-control"
                               value="<?= esc($value('phone')) ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tax ID / GSTIN</label>
                        <input type="text" name="tax_id"
                               class="form-control"
                               value="<?= esc($value('tax_id')) ?>">
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">Company Address</label>
                        <textarea name="address" class="form-control" rows="3"><?= esc($value('address')) ?></textarea>
                    </div>
                </div>

                <hr>

                <h5 class="mb-3">Invoice Settings</h5>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Invoice Prefix</label>
                        <input type="text" name="invoice_prefix"
                               class="form-control"
                               value="<?= esc($value('invoice_prefix', 'INV')) ?>"
                               maxlength="20" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Currency</label>
                        <input type="text" class="form-control"
                               value="Indian Rupee (₹)" disabled>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">Bank Details</label>
                        <textarea name="bank_details" class="form-control" rows="3"><?= esc($value('bank_details')) ?></textarea>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">Invoice Footer Note</label>
                        <textarea name="footer_note" class="form-control" rows="3"><?= esc($value('footer_note')) ?></textarea>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    Save Settings
                </button>
            </form>

        </div>
    </div>
</div>

<?= $this->endSection() ?>
