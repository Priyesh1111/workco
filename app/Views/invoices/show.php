<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<style>
    .invoice-page {
        padding-bottom: 30px;
    }

    .invoice-paper {
        background: #fff;
        max-width: 1000px;
        margin: auto;
        padding: 45px;
        border-radius: 10px;
        box-shadow: 0 3px 18px rgba(0, 0, 0, 0.07);
    }

    .invoice-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .invoice-brand {
        font-size: 30px;
        font-weight: 800;
        letter-spacing: 2px;
        color: #243b53;
    }

    .invoice-status {
        display: inline-block;
        padding: 6px 15px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .status-draft {
        background: #e9ecef;
        color: #495057;
    }

    .status-sent {
        background: #cfe2ff;
        color: #084298;
    }

    .status-paid {
        background: #d1e7dd;
        color: #0f5132;
    }

    .status-cancelled {
        background: #f8d7da;
        color: #842029;
    }

    .section-heading {
        font-size: 12px;
        font-weight: 700;
        color: #64748b;
        letter-spacing: 1px;
        margin-bottom: 15px;
    }

    .invoice-info p {
        margin-bottom: 7px;
        color: #495057;
    }

    .invoice-table {
        margin-top: 15px;
    }

    .invoice-table thead th {
        background: #243b53;
        color: #fff;
        padding: 14px 12px;
        font-size: 13px;
        border: none;
    }

    .invoice-table tbody td {
        padding: 14px 12px;
        vertical-align: middle;
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        padding: 9px 0;
        color: #495057;
    }

    .grand-total {
        display: flex;
        justify-content: space-between;
        padding: 17px 15px;
        margin-top: 12px;
        background: #243b53;
        color: #fff;
        border-radius: 6px;
        font-size: 17px;
    }

    .invoice-notes {
        padding: 18px;
        background: #f8f9fa;
        border-radius: 6px;
    }

    .invoice-footer {
        text-align: center;
        margin-top: 55px;
        padding-top: 20px;
        border-top: 1px solid #dee2e6;
        color: #64748b;
    }

    @media print {
        @page {
            size: A4;
            margin: 15mm;
        }

        body {
            background: #fff !important;
            min-width: 0 !important;
        }

        header,
        aside,
        footer,
        nav,
        .no-print {
            display: none !important;
        }

        main {
            margin: 0 !important;
            padding: 0 !important;
        }

        .container-fluid {
            padding: 0 !important;
            margin: 0 !important;
        }

        .invoice-paper {
            max-width: 100%;
            padding: 0;
            margin: 0;
            box-shadow: none;
            border-radius: 0;
        }

        .invoice-table thead th {
            background: #243b53 !important;
            color: white !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .grand-total {
            background: #243b53 !important;
            color: white !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .invoice-status {
            border: 1px solid #999;
        }

        .invoice-table tr {
            page-break-inside: avoid;
        }
    }
</style>
<div class="container-fluid invoice-page">

    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <h3 class="mb-0">Invoice Details</h3>
        <div class="d-flex gap-2">
                <a href="<?= site_url('invoices/pdf/' . $invoice['id'] . '?inline=1') ?>" target="_blank"
                rel="noopener" class="btn btn-dark">
                Print Invoice
            </a>
            <a href="<?= site_url('invoices/pdf/' . $invoice['id']) ?>" class="btn btn-primary">
            Download PDF
            </a>
            <a href="<?= site_url('invoices') ?>" class="btn btn-secondary">
                Back
            </a>
        </div>
    </div>

    <div class="invoice-paper">

        <!-- Invoice Header -->
        <div class="invoice-header">
            <div>
                <h2 class="invoice-brand">INVOICE</h2>
                <!-- <p class="text-muted mb-0">Invoice Management System</p> -->
            </div>

            <div class="text-end">
                <h5 class="mb-2">
                    <?= esc($invoice['invoice_no']) ?>
                </h5>
                <span class="invoice-status status-<?= esc($invoice['status']) ?>">
                    <?= esc(ucfirst($invoice['status'])) ?>
                </span>
            </div>
        </div>

        <hr>

        <!-- Invoice Information -->
        <div class="row invoice-info">
            <div class="col-md-6 mb-4">
                <h6 class="section-heading">BILL TO</h6>

                <h5 class="mb-2">
                    <?= esc($customer['name'] ?? 'Customer unavailable') ?>
                </h5>

                <?php if (!empty($customer['email'])): ?>
                    <p><?= esc($customer['email']) ?></p>
                <?php endif; ?>

                <?php if (!empty($customer['phone'])): ?>
                    <p><?= esc($customer['phone']) ?></p>
                <?php endif; ?>

                <?php if (!empty($customer['address'])): ?>
                    <p><?= nl2br(esc($customer['address'])) ?></p>
                <?php endif; ?>
            </div>

            <div class="col-md-6 mb-4 text-md-end">
                <h6 class="section-heading">INVOICE INFORMATION</h6>

                <p>
                    <strong>Invoice Number:</strong>
                    <?= esc($invoice['invoice_no']) ?>
                </p>

                <p>
                    <strong>Issue Date:</strong>
                    <?= esc($invoice['issue_date']) ?>
                </p>

                <p>
                    <strong>Due Date:</strong>
                    <?= esc($invoice['due_date']) ?>
                </p>
            </div>
        </div>

        <!-- Product Table -->
        <div class="table-responsive">
            <table class="table invoice-table">
                <thead>
                    <tr>
                        <th style="width: 40%">Description</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-end">Unit Price</th>
                        <th class="text-center">Tax</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td>
                                <strong><?= esc($item['description']) ?></strong>
                            </td>

                            <td class="text-center">
                                <?= esc($item['qty']) ?>
                            </td>

                            <td class="text-end">
                                ₹<?= number_format((float) $item['unit_price'], 2) ?>
                            </td>

                            <td class="text-center">
                                <?= esc($item['tax_percent']) ?>%
                            </td>

                            <td class="text-end">
                                ₹<?= number_format((float) $item['line_total'], 2) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Totals -->
        <div class="row justify-content-end mt-4">
            <div class="col-md-5 col-lg-4">

                <div class="total-row">
                    <span>Subtotal</span>
                    <strong>
                        ₹<?= number_format((float) $invoice['subtotal'], 2) ?>
                    </strong>
                </div>

                <!-- <div class="total-row">
                    <span>Discount</span>
                    <strong>
                        ₹<?= number_format((float) $invoice['discount'], 2) ?>
                    </strong>
                </div> -->

                <div class="total-row">
                    <span>Tax</span>
                    <strong>
                        ₹<?= number_format((float) $invoice['tax_total'], 2) ?>
                    </strong>
                </div>

                <div class="grand-total">
                    <span>Grand Total</span>
                    <strong>
                        ₹<?= number_format((float) $invoice['grand_total'], 2) ?>
                    </strong>
                </div>

            </div>
        </div>

        <!-- Notes -->
        <?php if (!empty($invoice['notes'])): ?>
            <div class="invoice-notes mt-5">
                <h6 class="section-heading">NOTES</h6>
                <p><?= nl2br(esc($invoice['notes'])) ?></p>
            </div>
        <?php endif; ?>

        <!-- Footer -->
        <div class="invoice-footer">
            <p class="mb-1">Thank you for your business!</p>
            <small>Generated by Invoice Management System</small>
        </div>

        <!-- Status Update -->
        <div class="no-print status-update mt-4">
            <hr>

            <form method="post" action="<?= site_url('invoices/status/' . $invoice['id']) ?>"
                class="d-flex flex-wrap align-items-center gap-2">

                <?= csrf_field() ?>

                <label class="form-label mb-0">
                    <strong>Update Invoice Status:</strong>
                </label>

                <select name="status" class="form-select" style="max-width:180px">
                    <?php foreach (['draft', 'due', 'sent', 'paid', 'cancelled'] as $status): ?>
                        <option value="<?= esc($status) ?>" <?= $invoice['status'] === $status ? 'selected' : '' ?>>
                            <?= esc(ucfirst($status)) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="submit" class="btn btn-primary">
                    Update Status
                </button>
            </form>
        </div>

    </div>
</div>


<?= $this->endSection() ?>