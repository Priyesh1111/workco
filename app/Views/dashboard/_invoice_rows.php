<?php
$badge = [
    'draft'     => 'bg-secondary',
    'due'       => 'bg-warning text-dark',
    'sent'      => 'bg-primary',
    'paid'      => 'bg-success',
    'cancelled' => 'bg-danger',
];
?>
<?php if (!empty($invoices)): ?>
    <?php foreach ($invoices as $invoice): ?>
        <?php $overdue = !in_array($invoice['status'], ['paid', 'cancelled'], true) && $invoice['due_date'] < $today; ?>
        <tr>
            <td><?= esc($invoice['invoice_no']) ?></td>
            <td><?= esc($invoice['customer_name'] ?? '—') ?></td>
            <td><?= esc($invoice['issue_date']) ?></td>
            <td><?= esc($invoice['due_date']) ?></td>
            <td>
                <span class="badge <?= $badge[$invoice['status']] ?? 'bg-secondary' ?>">
                    <?= esc(ucfirst($invoice['status'])) ?>
                </span>
                <?php if ($overdue): ?>
                    <span class="badge bg-danger">Overdue</span>
                <?php endif; ?>
            </td>
            <td class="text-end">₹<?= number_format((float) $invoice['grand_total'], 2) ?></td>
            <td class="text-end text-nowrap">
                <a href="<?= site_url('invoices/view/' . $invoice['id']) ?>" class="btn btn-sm btn-info">View</a>
                <a href="<?= site_url('invoices/pdf/' . $invoice['id']) ?>"
                    class="btn btn-sm btn-outline-primary">PDF</a>
            </td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="7" class="text-center text-muted py-4">No invoices created yet.</td>
    </tr>
<?php endif; ?>