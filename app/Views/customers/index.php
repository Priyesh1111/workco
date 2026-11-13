<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">

    <!-- Header & Add Button -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Customers</h2>
        <a href="<?= base_url('customers/new') ?>" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add Customer
        </a>
    </div>

    <!-- Flash Message -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Customers Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>address</th>
                            <th>tax_id</th>
                            <th style="width: 180px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($customers) && is_array($customers)): ?>
                            <?php foreach ($customers as $index => $customer): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= esc($customer['name']) ?></td>
                                    <td><?= esc($customer['email']) ?></td>
                                    <td><?= esc($customer['phone']) ?></td>
                                    <td><?= esc($customer['address']) ?></td>
                                    <td><?= esc($customer['tax_id']) ?></td>
                                    <td>
                                        <a href="<?= base_url('customers/edit/' . $customer['id']) ?>"
                                            class="btn btn-sm btn-warning">Edit
                                        </a>

                                        <!-- Delete with Confirmation -->
                                        <form action="<?= base_url('customers/delete/' . $customer['id']) ?>" method="post"
                                            class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this customer?');">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted">No customers found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<?= $this->endSection() ?>