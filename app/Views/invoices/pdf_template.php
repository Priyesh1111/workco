<?php
/**
 * Invoice PDF template (A4, rendered by Dompdf).
 * Dompdf supports CSS 2.1 only: layout uses tables, not flex/grid.
 *
 * @var array      $invoice
 * @var array|null $customer
 * @var array      $items
 * @var array      $settings
 * @var array|null $logo       src/width/height of the Settings logo
 * @var bool       $isOverdue
 */
$money = static fn($v): string => '₹' . number_format((float) $v, 2);

$company = trim((string) ($settings['company_name'] ?? ''));
$footer  = trim((string) ($settings['footer_note'] ?? ''));
$status  = (string) $invoice['status'];

$statusColors = [
    'draft'     => '#6c757d',
    'due'       => '#b45309',
    'sent'      => '#0b5ed7',
    'paid'      => '#198754',
    'cancelled' => '#b02a37',
];
$statusColor = $statusColors[$status] ?? '#6c757d';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= esc($invoice['invoice_no']) ?></title>
    <style>
        @page {
            margin: 36pt 40pt 54pt 40pt;
        }

        * {
            font-family: 'DejaVu Sans', sans-serif;
        }

        body {
            margin: 0;
            font-size: 9pt;
            line-height: 1.35;
            color: #2b2f36;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        td,
        th {
            vertical-align: top;
        }

        .muted {
            color: #6b7280;
        }

        .small {
            font-size: 8pt;
        }

        .right {
            text-align: right;
        }

        .center {
            text-align: center;
        }

        .label {
            font-size: 7.5pt;
            font-weight: bold;
            letter-spacing: 1px;
            color: #6b7280;
            text-transform: uppercase;
            margin-bottom: 4pt;
        }

        .company-name {
            font-size: 14pt;
            font-weight: bold;
            color: #243b53;
            margin-top: 6pt;
        }

        .doc-title {
            font-size: 26pt;
            font-weight: bold;
            letter-spacing: 3px;
            color: #243b53;
            line-height: 1;
        }

        .status {
            font-size: 8pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .rule {
            border-top: 2px solid #243b53;
            margin: 12pt 0 12pt 0;
        }

        .meta td {
            padding: 1.5pt 0;
        }

        .meta td.k {
            color: #6b7280;
            padding-right: 10pt;
        }

        table.items {
            margin-top: 6pt;
        }

        table.items thead {
            display: table-header-group;
        }

        table.items th {
            background: #243b53;
            color: #ffffff;
            font-size: 8pt;
            padding: 6pt 8pt;
            text-align: left;
        }

        table.items td {
            padding: 5pt 8pt;
            border-bottom: 1px solid #e5e7eb;
        }

        table.items th.center {
            text-align: center;
        }

        table.items th.right {
            text-align: right;
        }

        table.items tr {
            page-break-inside: avoid;
        }

        table.items tr.alt td {
            background: #f8fafc;
        }


        .totals td {
            padding: 2.5pt 8pt;
        }

        .totals tr.grand td {
            background: #243b53;
            color: #ffffff;
            font-weight: bold;
            font-size: 10.5pt;
            padding: 7pt 8pt;
        }

        .box {
            border: 1px solid #e5e7eb;
            background: #f8fafc;
            padding: 8pt 10pt;
        }

        .footer-note {
            margin-top: 18pt;
            padding-top: 8pt;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 8.5pt;
        }
    </style>
</head>

<body>

    <!-- Header: logo + company on the left, title on the right -->
    <table>
        <tr>
            <td style="width: 58%;">
                <?php if (!empty($logo)): ?>
                    <img src="<?= $logo['src'] ?>" width="<?= (int) $logo['width'] ?>" height="<?= (int) $logo['height'] ?>"
                        alt="Logo">
                <?php endif; ?>

                <?php if ($company !== ''): ?>
                    <div class="company-name"><?= esc($company) ?></div>
                <?php endif; ?>

                <div class="muted small" style="margin-top: 3pt;">
                    <?php if (!empty($settings['address'])): ?>
                        <?= nl2br(esc($settings['address'])) ?><br>
                    <?php endif; ?>
                    <?php if (!empty($settings['phone'])): ?>
                        Phone: <?= esc($settings['phone']) ?><br>
                    <?php endif; ?>
                    <?php if (!empty($settings['email'])): ?>
                        Email: <?= esc($settings['email']) ?><br>
                    <?php endif; ?>
                    <?php if (!empty($settings['tax_id'])): ?>
                        GSTIN / Tax ID: <?= esc($settings['tax_id']) ?>
                    <?php endif; ?>
                </div>
            </td>

            <td class="right" style="width: 42%;">
                <div class="doc-title">INVOICE</div>
                <div style="margin-top: 6pt; font-size: 10pt; font-weight: bold;">
                    <?= esc($invoice['invoice_no']) ?>
                </div>
                <div class="status" style="margin-top: 4pt; color: <?= $statusColor ?>;">
                    <?= esc($status) ?>
                    <?php if (!empty($isOverdue)): ?>
                        <span style="color: #b02a37;"> &middot; Overdue</span>
                    <?php endif; ?>
                </div>
            </td>
        </tr>
    </table>

    <div class="rule"></div>

    <!-- Bill to + invoice details -->
    <table>
        <tr>
            <td style="width: 55%;">
                <div class="label">Bill To</div>
                <div style="font-size: 11pt; font-weight: bold; margin-bottom: 2pt;">
                    <?= esc($customer['name'] ?? 'Customer unavailable') ?>
                </div>
                <div class="muted">
                    <?php if (!empty($customer['address'])): ?>
                        <?= nl2br(esc($customer['address'])) ?><br>
                    <?php endif; ?>
                    <?php if (!empty($customer['phone'])): ?>
                        <?= esc($customer['phone']) ?><br>
                    <?php endif; ?>
                    <?php if (!empty($customer['email'])): ?>
                        <?= esc($customer['email']) ?><br>
                    <?php endif; ?>
                    <?php if (!empty($customer['tax_id'])): ?>
                        GSTIN / Tax ID: <?= esc($customer['tax_id']) ?>
                    <?php endif; ?>
                </div>
            </td>

            <td style="width: 45%;">
                <div class="label right">Invoice Details</div>
                <table class="meta" style="width: auto; margin-left: auto;">
                    <tr>
                        <td class="k right">Invoice No.</td>
                        <td class="right"><strong><?= esc($invoice['invoice_no']) ?></strong></td>
                    </tr>
                    <tr>
                        <td class="k right">Issue Date</td>
                        <td class="right"><?= esc(date('d M Y', strtotime($invoice['issue_date']))) ?></td>
                    </tr>
                    <tr>
                        <td class="k right">Due Date</td>
                        <td class="right"><?= esc(date('d M Y', strtotime($invoice['due_date']))) ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Line items -->
    <table class="items" style="margin-top: 14pt;">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 45%;">Description</th>
                <th class="center" style="width: 8%;">Qty</th>
                <th class="right" style="width: 17%;">Unit Price</th>
                <th class="center" style="width: 9%;">Tax</th>
                <th class="right" style="width: 16%;">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $i => $item): ?>
                <tr class="<?= $i % 2 ? 'alt' : '' ?>">
                    <td class="muted"><?= $i + 1 ?></td>
                    <td><?= esc($item['description']) ?></td>
                    <td class="center"><?= esc(rtrim(rtrim(number_format((float) $item['qty'], 2, '.', ''), '0'), '.')) ?></td>
                    <td class="right"><?= $money($item['unit_price']) ?></td>
                    <td class="center"><?= esc(rtrim(rtrim(number_format((float) $item['tax_percent'], 2), '0'), '.')) ?>%</td>
                    <td class="right"><?= $money($item['line_total']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Totals (kept together on one page) -->
    <div style="page-break-inside: avoid; margin-top: 12pt;">
        <table>
            <tr>
                <td style="width: 55%;"></td>
                <td style="width: 45%;">
                    <table class="totals">
                        <tr>
                            <td class="muted">Subtotal</td>
                            <td class="right"><?= $money($invoice['subtotal']) ?></td>
                        </tr>
                        <?php if ((float) $invoice['discount'] > 0): ?>
                            <tr>
                                <td class="muted">Discount</td>
                                <td class="right">- <?= $money($invoice['discount']) ?></td>
                            </tr>
                        <?php endif; ?>
                        <tr>
                            <td class="muted">Tax</td>
                            <td class="right"><?= $money($invoice['tax_total']) ?></td>
                        </tr>
                        <tr class="grand">
                            <td>Grand Total</td>
                            <td class="right"><?= $money($invoice['grand_total']) ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <!-- Bank details + notes -->
    <?php $bank = trim((string) ($settings['bank_details'] ?? '')); ?>
    <?php $notes = trim((string) ($invoice['notes'] ?? '')); ?>
    <?php if ($bank !== '' || $notes !== ''): ?>
        <div style="margin-top: 14pt; page-break-inside: avoid;">
        <table>
            <tr>
                <?php if ($bank !== ''): ?>
                    <td style="width: <?= $notes !== '' ? '48%' : '100%' ?>;">
                        <div class="label">Bank Details</div>
                        <div class="box"><?= nl2br(esc($bank)) ?></div>
                    </td>
                <?php endif; ?>

                <?php if ($bank !== '' && $notes !== ''): ?>
                    <td style="width: 4%;"></td>
                <?php endif; ?>

                <?php if ($notes !== ''): ?>
                    <td style="width: <?= $bank !== '' ? '48%' : '100%' ?>;">
                        <div class="label">Notes</div>
                        <div class="box"><?= nl2br(esc($notes)) ?></div>
                    </td>
                <?php endif; ?>
            </tr>
        </table>
        </div>
    <?php endif; ?>

    <div class="footer-note">
        <?= $footer !== '' ? nl2br(esc($footer)) : 'Thank you for your business!' ?>
    </div>

</body>

</html>