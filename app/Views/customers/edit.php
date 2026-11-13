<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Header & Back Button -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">
            <?= esc($title ?? 'Edit Customer') ?>
        </h2>
        <a href="<?= base_url('customers') ?>" class="btn btn-secondary">Back</a>
    </div>

    <!-- Validation Errors -->
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Edit Form -->
    <div class="card">
        <div class="card-body">
            <form action="<?= site_url('customers/update/' . $customer['id']) ?>" method="post">
                <?= csrf_field() ?>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name"
                            value="<?= old('name', esc($customer['name'])) ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="<?= old('email', esc($customer['email'])) ?>">
                    </div>

                    <div class="col-md-6">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" maxlength="10"
                            value="<?= old('phone', esc($customer['phone'])) ?>">
                    </div>

                    <div class="col-md-6">
                        <label for="tax_id" class="form-label">Tax ID</label>
                        <input type="text" class="form-control" id="tax_id" name="tax_id"
                            value="<?= old('tax_id', esc($customer['tax_id'])) ?>">
                    </div>

                    <div class="col-12">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address"
                            rows="3"><?= old('address', esc($customer['address'])) ?></textarea>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Update Customer</button>
                    <a href="<?= base_url('customers') ?>" class="btn btn-light border ms-2">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>