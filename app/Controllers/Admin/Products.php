<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProductModel;

class Products extends BaseController
{
    protected $productModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
    }

    public function index()
    {
        $perPage = $this->request->getGet('per_page') ?? 10;
        $search = $this->request->getGet('search');
        $sort = $this->request->getGet('sort') ?? 'name';
        $order = $this->request->getGet('order') ?? 'asc';

        // Start the query
        $builder = $this->productModel;

        // Apply search if provided
        if ($search) {
            $builder->groupStart()
                    ->like('name', $search)
                    ->orLike('size', $search)
                    ->groupEnd();
        }

        // Apply sorting
        $allowedSort = ['name', 'size', 'price'];
        if (in_array($sort, $allowedSort)) {
            $builder->orderBy($sort, $order === 'asc' ? 'asc' : 'desc');
        }

        $data = [
            'title' => 'Products Management',
            'products' => $builder->paginate($perPage),
            'pager' => $builder->pager,
            'sort' => $sort,
            'order' => $order,
            'search' => $search
        ];

        return view('admin/products/index', $data);
    }

    public function add()
    {
        if ($this->request->isAJAX()) {
            return view('admin/product_form');
        }
        return redirect()->to('admin/products');
    }

    public function edit($id)
    {
        if ($this->request->isAJAX()) {
            $product = $this->productModel->find($id);
            if ($product) {
                return view('admin/product_form', ['product' => $product]);
            }
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Product not found'
            ]);
        }
        return redirect()->to('admin/products');
    }

    public function save()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('admin/products');
        }

        $id = $this->request->getPost('id');
        
        // Prepare the data
        $data = [
            'name' => $this->request->getPost('name'),
            'size' => $this->request->getPost('size'),
            'price' => $this->request->getPost('price')
        ];

        // Validation rules
        $rules = [
            'name' => 'required',
            'size' => 'required',
            'price' => 'required|numeric|greater_than[0]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $this->validator->getErrors()
            ]);
        }

        try {
            if ($id) {
                $result = $this->productModel->update($id, $data);
            } else {
                $result = $this->productModel->insert($data);
            }

            if ($result) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Product saved successfully'
                ]);
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to save product'
            ]);

        } catch (\Exception $e) {
            log_message('error', '[Products::save] Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred while saving the product'
            ]);
        }
    }

    public function delete($id)
    {
        if ($this->request->isAJAX()) {
            if ($this->productModel->delete($id)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Product deleted successfully'
                ]);
            }
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error deleting product'
            ]);
        }
        return redirect()->to('admin/products');
    }

    public function getProducts()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('admin/products');
        }

        // Convert string values to integers
        $draw = (int) $this->request->getPost('draw');
        $start = (int) $this->request->getPost('start');
        $length = (int) $this->request->getPost('length');
        $search = $this->request->getPost('search')['value'];
        $order = $this->request->getPost('order')[0];
        
        $columns = ['name', 'size', 'price'];
        $orderColumn = $columns[$order['column']];
        $orderDir = $order['dir'];

        // Get total count
        $total = $this->productModel->countAll();

        // Build query
        $builder = $this->productModel;
        
        // Apply search
        if ($search) {
            $builder->groupStart()
                    ->like('name', $search)
                    ->orLike('size', $search)
                    ->orLike('price', $search)
                    ->groupEnd();
        }

        // Get filtered count
        $filtered = $builder->countAllResults(false);

        // Get data
        $products = $builder->orderBy($orderColumn, $orderDir)
                           ->limit($length, $start)
                           ->find();

        return $this->response->setJSON([
            'draw' => $draw,
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $products
        ]);
    }
} 