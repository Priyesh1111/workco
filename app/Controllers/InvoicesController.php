<?php

namespace App\Controllers;

use App\Models\InvoicesModel;
use App\Models\InvoiceItemsModel;
use App\Models\CustomersModel;
use App\Models\ProductsModel;
use App\Models\SettingsModel;
use App\Libraries\PdfService;

class InvoicesController extends BaseController
{
    protected $invoices;
    protected $items;
    protected $customers;
    protected $products;
    public function __construct()
    {
        $this->invoices = new InvoicesModel();
        $this->items = new InvoiceItemsModel();
        $this->customers = new CustomersModel();
        $this->products = new ProductsModel();
        helper(['form', 'url']);
    }

    private function userId(): int
    {
        return (int) session()->get('user_id');
    }

    public function index()
    {
        $invoices = $this->invoices
            ->where('user_id', $this->userId())
            ->orderBy('id', 'DESC')
            ->paginate(10);

        return view('invoices/index', [
            'title' => 'Invoices',
            'invoices' => $invoices,
            'pager' => $this->invoices->pager,
        ]);
    }

    public function new()
    {
        $customers = $this->customers
            ->where('user_id', $this->userId())
            ->orderBy('name', 'ASC')
            ->findAll();

        $products = $this->products
            ->where('user_id', $this->userId())
            ->orderBy('name', 'ASC')
            ->findAll();

        return view('invoices/create', [
            'title' => 'Create Invoice',
            'customers' => $customers,
            'products' => $products,
        ]);
    }

    public function create()
    {
        $customerId = (int) $this->request->getPost('customer_id');
        $productIds = $this->request->getPost('product_id') ?? [];
        $quantities = $this->request->getPost('qty') ?? [];

        $issueDate = $this->request->getPost('issue_date');
        $dueDate = $this->request->getPost('due_date');
        $discount = (float) $this->request->getPost('discount');
        $notes = trim((string) $this->request->getPost('notes'));

        $customer = $this->customers
            ->where('user_id', $this->userId())
            ->find($customerId);

        if (!$customer) {
            return redirect()->back()->withInput()
                ->with('error', 'Please select a valid customer.');
        }

        if (
            !is_array($productIds) ||
            !is_array($quantities) ||
            count($productIds) === 0 ||
            count($productIds) !== count($quantities)
        ) {
            return redirect()->back()->withInput()
                ->with('error', 'Please add at least one valid product.');
        }

        if (!$issueDate || !$dueDate || $dueDate < $issueDate) {
            return redirect()->back()->withInput()
                ->with('error', 'Please enter valid invoice dates.');
        }

        if ($discount < 0 || !is_finite($discount)) {
            return redirect()->back()->withInput()
                ->with('error', 'Invalid discount amount.');
        }

        $invoiceItems = [];
        $subtotal = 0;
        $taxTotal = 0;

        foreach ($productIds as $key => $productId) {
            $productId = (int) $productId;
            $qty = filter_var(
                $quantities[$key] ?? null,
                FILTER_VALIDATE_INT
            );

            // Only positive whole-number quantities are allowed.
            if ($productId <= 0 || $qty === false || $qty < 1) {
                return redirect()->back()->withInput()
                    ->with('error', 'Select valid products and enter whole-number quantities.');
            }

            $product = $this->products
                ->where('user_id', $this->userId())
                ->find($productId);

            if (!$product) {
                return redirect()->back()->withInput()
                    ->with('error', 'A selected product is invalid.');
            }

            $price = (float) $product['unit_price'];
            $taxPercent = (float) $product['tax_percent'];

            $base = round($qty * $price, 2);
            $tax = round($base * $taxPercent / 100, 2);
            $lineTotal = round($base + $tax, 2);

            $subtotal += $base;
            $taxTotal += $tax;

            $invoiceItems[] = [
                'product_id' => $productId,
                'description' => $product['name'],
                'qty' => $qty,
                'unit_price' => $price,
                'tax_percent' => $taxPercent,
                'line_total' => $lineTotal,
            ];
        }

        $subtotal = round($subtotal, 2);
        $taxTotal = round($taxTotal, 2);

        if ($discount > $subtotal) {
            return redirect()->back()->withInput()
                ->with('error', 'Discount cannot exceed subtotal.');
        }

        $grandTotal = round($subtotal - $discount + $taxTotal, 2);

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $invoiceNo = 'INV-' . date('Ymd') . '-' .
                strtoupper(bin2hex(random_bytes(3)));

            $this->invoices->insert([
                'user_id' => $this->userId(),
                'customer_id' => $customerId,
                'invoice_no' => $invoiceNo,
                'issue_date' => $issueDate,
                'due_date' => $dueDate,
                'status' => 'draft',
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax_total' => $taxTotal,
                'grand_total' => $grandTotal,
                'notes' => $notes,
            ]);

            $invoiceId = $this->invoices->getInsertID();

            if (!$invoiceId) {
                throw new \RuntimeException('Invoice could not be created.');
            }

            foreach ($invoiceItems as $item) {
                $item['invoice_id'] = $invoiceId;
                $this->items->insert($item);
            }

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Unable to save invoice.');
            }

            $db->transCommit();

        } catch (\Throwable $e) {
            $db->transRollback();

            log_message(
                'error',
                'Invoice creation failed: ' . $e->getMessage()
            );

            return redirect()->back()->withInput()
                ->with('error', 'Invoice could not be saved. Please try again.');
        }

        return redirect()->to(site_url('invoices'))
            ->with('success', 'Invoice created successfully.');
    }

    public function show(int $id)
    {
        $invoice = $this->invoices
            ->where('user_id', $this->userId())
            ->find($id);

        if (!$invoice) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $customer = $this->customers
            ->where('user_id', $this->userId())
            ->find($invoice['customer_id']);

        $items = $this->items
            ->where('invoice_id', $id)
            ->findAll();

        return view('invoices/show', [
            'title' => 'Invoice Details',
            'invoice' => $invoice,
            'customer' => $customer,
            'items' => $items,
        ]);
    }

        public function pdf(int $id)
    {
        $invoice = $this->invoices
            ->where('user_id', $this->userId())
            ->find($id);

        if (!$invoice) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $customer = $this->customers
            ->where('user_id', $this->userId())
            ->withDeleted()
            ->find($invoice['customer_id']);

        $items = $this->items
            ->where('invoice_id', $id)
            ->orderBy('id', 'ASC')
            ->findAll();

        $settings = (new SettingsModel())
            ->where('user_id', $this->userId())
            ->first() ?? [];

        $isOverdue = !in_array($invoice['status'], ['paid', 'cancelled'], true)
            && $invoice['due_date'] < date('Y-m-d');

        if (!class_exists(\Dompdf\Dompdf::class)) {
            return redirect()->back()
                ->with('error', 'PDF library missing. Run: composer require dompdf/dompdf');
        }

        $pdf = new PdfService();

        try {
            $binary = $pdf->render([
                'invoice'   => $invoice,
                'customer'  => $customer,
                'items'     => $items,
                'settings'  => $settings,
                'isOverdue' => $isOverdue,
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'Invoice PDF failed: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'The PDF could not be generated. Please try again.');
        }

        $filename = $pdf->filename($invoice);

        // ?inline=1 opens the PDF in the browser tab (used by the Print button).
        if ($this->request->getGet('inline') === '1') {
            return $this->response
                ->setContentType('application/pdf')
                ->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"')
                ->setBody($binary);
        }

        return $this->response->download($filename, $binary, true);
    }
    public function updateStatus(int $id)
    {
        $invoice = $this->invoices
            ->where('user_id', $this->userId())
            ->find($id);

        if (!$invoice) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $status = $this->request->getPost('status');
        $allowed = ['draft', 'due', 'sent', 'paid', 'cancelled'];

        if (!in_array($status, $allowed, true)) {
            return redirect()->back()
                ->with('error', 'Invalid invoice status.');
        }

        $this->invoices->update($id, ['status' => $status]);

        return redirect()->back()
            ->with('success', 'Invoice status updated.');
    }
}