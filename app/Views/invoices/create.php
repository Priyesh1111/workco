
<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Create Invoice</h3>
        <a href="<?= site_url('invoices') ?>" class="btn btn-secondary">Back</a>
    </div>

    <?php if ($error = session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= esc($error) ?></div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="post" action="<?= site_url('invoices') ?>">
                <?= csrf_field() ?>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Customer *</label>
                        <select name="customer_id" class="form-select" required>
                            <option value="">Select Customer</option>
                            <?php foreach ($customers as $customer): ?>
                                <option value="<?= $customer['id'] ?>"
                                    <?= old('customer_id') == $customer['id'] ? 'selected' : '' ?>>
                                    <?= esc($customer['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Issue Date *</label>
                        <input type="date" name="issue_date" class="form-control"
                               value="<?= esc(old('issue_date', date('Y-m-d'))) ?>" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Due Date *</label>
                        <input type="date" name="due_date" class="form-control"
                               value="<?= esc(old('due_date', date('Y-m-d', strtotime('+7 days')))) ?>" required>
                    </div>
                </div>

                <h5 class="mt-4">Invoice Items</h5>

                <div class="table-responsive">
                    <table class="table table-bordered" id="itemsTable">
                        <thead class="table-light">
                            <tr>
                                <th style="width:45%">Product</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Tax %</th>
                                <th>Line Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="itemsBody">
                            <tr>
                                <td>
                                    <select name="product_id[]" class="form-select product-select" required>
                                        <option value="">Select Product</option>
                                        <?php foreach ($products as $product): ?>
                                            <option value="<?= $product['id'] ?>"
                                                data-price="<?= esc($product['unit_price']) ?>"
                                                data-tax="<?= esc($product['tax_percent']) ?>">
                                                <?= esc($product['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="qty[]" min="1" step="1" value="1" required>
                                </td>
                                <td class="price">0.00</td>
                                <td class="tax">0.00%</td>
                                <td class="line-total">0.00</td>
                                <td>
                                    <button type="button" class="remove-item btn btn-danger btn-sm">Remove</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <button type="button" id="addRow" class="btn btn-outline-primary btn-sm">
                    + Add Product
                </button>

                <div class="row justify-content-end mt-4">
                    <div class="col-md-4">
                        <div class="mb-2 d-flex justify-content-between">
                            <strong>Subtotal:</strong>
                            <span id="subtotal">₹0.00</span>
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Discount (₹)</label>
                            <input type="number" name="discount" id="discount"
                                   class="form-control" value="<?= esc(old('discount', '0')) ?>"
                                   min="0" step="0.01">
                        </div>

                        <div class="mb-2 d-flex justify-content-between">
                            <strong>Tax:</strong>
                            <span id="taxTotal">₹0.00</span>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between fs-5">
                            <strong>Grand Total:</strong>
                            <strong id="grandTotal">₹0.00</strong>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="2"><?= esc(old('notes')) ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary mt-4">Save Invoice</button>
                <a href="<?= site_url('invoices') ?>" class="btn btn-secondary mt-4">Cancel</a>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const body = document.getElementById('itemsBody');

    function calculate() {
        let subtotal = 0;
        let taxTotal = 0;

        body.querySelectorAll('tr').forEach(row => {
            const select = row.querySelector('.product-select');
            const option = select.options[select.selectedIndex];
            const qty = parseFloat(row.querySelector('.qty').value) || 0;

            const price = parseFloat(option.dataset.price) || 0;
            const tax = parseFloat(option.dataset.tax) || 0;

            const base = qty * price;
            const taxAmount = base * tax / 100;

            subtotal += base;
            taxTotal += taxAmount;

            row.querySelector('.price').textContent = price.toFixed(2);
            row.querySelector('.tax').textContent = tax.toFixed(2) + '%';
            row.querySelector('.line-total').textContent =
                (base + taxAmount).toFixed(2);
        });

        const discount = parseFloat(document.getElementById('discount').value) || 0;
        const grandTotal = subtotal - discount + taxTotal;

        document.getElementById('subtotal').textContent = '₹' + subtotal.toFixed(2);
        document.getElementById('taxTotal').textContent = '₹' + taxTotal.toFixed(2);
        document.getElementById('grandTotal').textContent =
            '₹' + grandTotal.toFixed(2);
    }

    function bindRow(row) {
        row.querySelector('.product-select').addEventListener('change', calculate);
        row.querySelector('.qty').addEventListener('input', calculate);

        row.querySelector('.remove-row').addEventListener('click', function () {
            if (body.querySelectorAll('tr').length > 1) {
                row.remove();
                calculate();
            }
        });
    }

    document.getElementById('addRow').addEventListener('click', function () {
        const first = body.querySelector('tr');
        const row = first.cloneNode(true);

        row.querySelector('.product-select').selectedIndex = 0;
        row.querySelector('.qty').value = 1;

        body.appendChild(row);
        bindRow(row);
        calculate();
    });

    document.getElementById('discount').addEventListener('input', calculate);

    body.querySelectorAll('tr').forEach(bindRow);
    calculate();
});
</script>

<?= $this->endSection() ?>
