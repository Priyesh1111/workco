<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3>Products</h3>
            <p class="text-muted">Manage your product catalogue</p>
        </div>
        <a href="<?= site_url('products/new') ?>" class="btn btn-primary">
            + Add Product
        </a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Id</th>
                        <th>Product Name</th>
                        <th>Description</th>
                        <th>Unit Price</th>
                        <th>Tax (%)</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $index => $product): ?>
                            <tr>
                                <td>
                                    <?= $index + 1 ?>
                                </td>
                                <td>
                                    <?= esc($product['name']) ?>
                                </td>
                                <td>
                                    <?= esc($product['description'] ?? '') ?>
                                </td>
                                <td>₹
                                    <?= number_format((float) $product['unit_price'], 2) ?>
                                </td>
                                <td>
                                    <?= esc($product['tax_percent']) ?>%
                                </td>
                                <td>
                                    <a href="<?= site_url('products/edit/' . $product['id']) ?>" class="btn btn-sm btn-warning">
                                        Edit
                                    </a>

                                    <form action="<?= site_url('products/delete/' . $product['id']) ?>" method="post"
                                        class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this product?');">
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
                            <td colspan="6" class="text-center text-muted">
                                No products found. Add your first product.
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