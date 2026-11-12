
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between mb-4">
        <h3>Add Product</h3>
        <a href="<?= site_url('products') ?>" class="btn btn-secondary">Back</a>
    </div>

    <?php if ($errors = session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="<?= site_url('products') ?>" method="post">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label class="form-label">Product Name *</label>
                    <input type="text" name="name" class="form-control"
                           value="<?= esc(old('name') ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control"
                              rows="3"><?= esc(old('description') ?? '') ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Unit Price (₹) *</label>
                    <input type="number" name="unit_price" class="form-control"
                           step="0.01" min="0"
                           value="<?= esc(old('unit_price') ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tax Percentage (%) *</label>
                    <input type="number" name="tax_percent" class="form-control"
                           step="0.01" min="0" max="100"
                           value="<?= esc(old('tax_percent') ?? '0') ?>" required>
                </div>

                <button type="submit" class="btn btn-primary">Save Product</button>
                <a href="<?= site_url('products') ?>" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
