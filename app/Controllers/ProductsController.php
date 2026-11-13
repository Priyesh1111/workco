<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\ProductsModel;

class ProductsController extends BaseController
{
    protected $products;
    public function __construct()
    {
        $this->products = new ProductsModel();
        helper(['form', 'url']);
    }

    private function currentUserId(): int
    {
        return (int) session()->get('user_id');
    }

    private function findOwnedProduct(int $id): array
    {
        $product = $this->products
            ->where('user_id', $this->currentUserId())
            ->find($id);

        if (!$product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'Product not found.'
            );
        }

        return $product;
    }

    public function index()
    {
        $data = [
            'title' => 'Products',
            'products' => $this->products
                ->where('user_id', $this->currentUserId())
                ->orderBy('id', 'DESC')
                ->paginate(10),
            'pager' => $this->products->pager,
        ];

        return view('products/index', $data);
    }
    public function new()
    {
        return view('products/create', [
            'title' => 'Add Product',
        ]);
    }

    public function create()
    {
        $data = [
            'name' => trim((string) $this->request->getPost('name')),
            'description' => trim((string) $this->request->getPost('description')),
            'unit_price' => $this->request->getPost('unit_price'),
            'tax_percent' => $this->request->getPost('tax_percent'),
        ];

        $rules = [
            'name' => 'required|min_length[2]|max_length[100]',
            'description' => 'permit_empty',
            'unit_price' => 'required|decimal|greater_than_equal_to[0]',
            'tax_percent' => 'required|decimal|greater_than_equal_to[0]|less_than_equal_to[100]',
        ];

        if (!$this->validateData($data, $rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data['user_id'] = $this->currentUserId();

        $this->products->insert($data);

        return redirect()->to(site_url('products'))
            ->with('success', 'Product added successfully.');
    }

    public function edit(int $id)
    {
        return view('products/edit', [
            'title' => 'Edit Product',
            'product' => $this->findOwnedProduct($id),
        ]);
    }

    public function update(int $id)
    {
        $this->findOwnedProduct($id);

        $data = [
            'name' => trim((string) $this->request->getPost('name')),
            'description' => trim((string) $this->request->getPost('description')),
            'unit_price' => $this->request->getPost('unit_price'),
            'tax_percent' => $this->request->getPost('tax_percent'),
        ];

        $rules = [
            'name' => 'required|min_length[2]|max_length[100]',
            'description' => 'permit_empty',
            'unit_price' => 'required|decimal|greater_than_equal_to[0]',
            'tax_percent' => 'required|decimal|greater_than_equal_to[0]|less_than_equal_to[100]',
        ];

        if (!$this->validateData($data, $rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->products->update($id, $data);

        return redirect()->to(site_url('products'))
            ->with('success', 'Product updated successfully.');
    }

    public function delete(int $id)
    {
        $this->findOwnedProduct($id);

        $this->products->delete($id);

        return redirect()->to(site_url('products'))
            ->with('success', 'Product deleted successfully.');
    }
}