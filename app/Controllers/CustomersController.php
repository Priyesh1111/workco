<?php
namespace App\Controllers;
use App\Models\CustomersModel;

class CustomersController extends BaseController
{
    protected $customers;
    public function __construct()
    {
        $this->customers = new CustomersModel();
        helper(['form', 'url']);
    }

    //logged in user ID
    private function currentUserId(): int
    {
        return (int) session()->get('user_id');
    }

    // Find a customer belonging to the loggedin user
    private function findOwnedCustomer(int $id): array
    {
        $customer = $this->customers
            ->where('user_id', $this->currentUserId()) //call private currentUserId()
            ->find($id);
        // check is customer is present or not
        if (!$customer) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'Customer not found.'
            );
        }
        return $customer;
    }

    // Display all customers
    public function index()
    {
        $data = [
            'title' => 'Customers',
            'customers' => $this->customers
                ->where('user_id', $this->currentUserId())
                ->orderBy('id', 'DESC')
                //pagination- per page 10 customers are displayed
                ->paginate(10),
            'pager' => $this->customers->pager,
        ];
        return view('customers/index', $data);
    }
    // Display the create form
    public function new()
    {
        return view('customers/create', [
            'title' => 'Add Customer',
        ]);
    }
    // Save a new customer
    public function create()
    {
        $data = [
            'name' => trim((string) $this->request->getPost('name')),
            'email' => trim((string) $this->request->getPost('email')),
            'phone' => trim((string) $this->request->getPost('phone')),
            'address' => trim((string) $this->request->getPost('address')),
            'tax_id' => trim((string) $this->request->getPost('tax_id')),
        ];

        $rules = [
            'name' => 'required|min_length[2]|max_length[100]',
            'email' => 'valid_email|max_length[150]',
            'phone' => 'max_length[10]',
            'tax_id' => 'max_length[50]',
        ];
        if (!$this->validateData($data, $rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data['user_id'] = $this->currentUserId();
        $this->customers->insert($data);

        return redirect()->to(site_url('customers'))
            ->with('success', 'Customer added successfully.');
    }

    // Display one customer's details
    public function show(int $id)
    {
        return view('customers/show', [
            'title' => 'Customer Details',
            'customer' => $this->findOwnedCustomer($id),
        ]);
    }

    // Display the edit form
    public function edit(int $id)
    {
        return view('customers/edit', [
            'title' => 'Edit Customer',
            'customer' => $this->findOwnedCustomer($id),
        ]);
    }

    // Update a customer
    public function update(int $id)
    {
        $this->findOwnedCustomer($id);
        $data = [
            'name' => trim((string) $this->request->getPost('name')),
            'email' => trim((string) $this->request->getPost('email')),
            'phone' => trim((string) $this->request->getPost('phone')),
            'address' => trim((string) $this->request->getPost('address')),
            'tax_id' => trim((string) $this->request->getPost('tax_id')),
        ];

        $rules = [
            'name' => 'required|min_length[2]|max_length[100]',
            'email' => 'valid_email|max_length[150]',
            'phone' => 'max_length[10]',
            'tax_id' => 'max_length[50]',
        ];

        if (!$this->validateData($data, $rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }
        // update based on customer id
        $this->customers->update($id, $data);

        return redirect()->to(site_url('customers'))
            ->with('success', 'Customer updated successfully.');
    }

    // Soft-delete a customer
    public function delete(int $id)
    {
        $this->findOwnedCustomer($id);
        $this->customers->delete($id);
        return redirect()->to(site_url('customers'))
            ->with('success', 'Customer deleted successfully.');
    }
}