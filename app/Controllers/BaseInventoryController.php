<?php

namespace App\Controllers;

use App\Models\InventoryModel;
use App\Models\ProductModel;
use App\Models\UserModel;

abstract class BaseInventoryController extends BaseController
{
    protected $inventoryModel;
    protected $productModel;
    protected $userModel;
    protected $viewPrefix;

    public function __construct()
    {
        $this->inventoryModel = new InventoryModel();
        $this->productModel = new ProductModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $products = $this->productModel
            ->select('name')
            ->groupBy('name')
            ->orderBy('name', 'asc')
            ->findAll();

        $employees = $this->userModel
            ->select('id, name')
            ->orderBy('name', 'asc')
            ->like('role', 'Manufacturing worker')
            ->findAll();

        $data = [
            'title' => 'Inventory Management',
            'employees' => $employees,
            'products' => $products,
            'viewPrefix' => $this->viewPrefix
        ];

        return view($this->viewPrefix . '/inventory/index', $data);
    }

    public function getInventory()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to($this->viewPrefix . '/inventory');
        }

        $draw = (int)$this->request->getPost('draw');
        $start = (int)$this->request->getPost('start');
        $length = (int)$this->request->getPost('length');
        $search = $this->request->getPost('search')['value'];
        $order = $this->request->getPost('order')[0];

        $builder = $this->getBaseQuery();
        
       
        if ($search) {
            $builder->groupStart()
                ->like('products.name', $search)
                ->orLike('products.size', $search)
                ->orLike('users.name', $search)
                ->groupEnd();
        }

        $total = $builder->countAllResults(false);
        
        // Apply ordering
        $columns = [
            'products.name',
            'products.size',
            'users.name',
            'inventory.issued_quantity',
            'inventory.received_quantity',
            'inventory.cost',
            'inventory.created_at'
        ];
        $orderColumn = $columns[$order['column']];
        $orderDir = $order['dir'];
        
        $records = $builder->orderBy($orderColumn, $orderDir)
            ->limit($length, $start)
            ->find();

        return $this->response->setJSON([
            'draw' => $draw,
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $records
        ]);
    }

    protected function getBaseQuery()
    {
        return $this->inventoryModel
            ->select('inventory.*, products.name as product_name, products.size as product_size, users.name as employee_name')
            ->join('products', 'products.id = inventory.product_id')
            ->join('users', 'users.id = inventory.employee_id');
    }

    protected function shouldFilterByUser()
    {
        return session()->get('role') === 'Inventory Manager';
    }

    public function getSizes()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to($this->viewPrefix . '/inventory');
        }

        $productName = $this->request->getPost('product_name');
        $sizes = $this->productModel
            ->select('id, size, price')
            ->where('name', $productName)
            ->orderBy('size', 'asc')
            ->findAll();

        return $this->response->setJSON([
            'success' => true,
            'sizes' => $sizes
        ]);
    }

    // Common CRUD operations with role-based checks
    public function save()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to($this->viewPrefix . '/inventory');
        }

        $rules = [
            'product_id' => 'required|numeric',
            'employee_id' => 'required|numeric',
            'issued_quantity' => 'required|numeric|greater_than_equal_to[0]',
            'received_quantity' => 'required|numeric|greater_than_equal_to[0]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $this->validator->getErrors()
            ]);
        }

        try {
            $data = [
                'product_id' => (int)$this->request->getPost('product_id'),
                'employee_id' => (int)$this->request->getPost('employee_id'),
                'issued_quantity' => (int)$this->request->getPost('issued_quantity'),
                'received_quantity' => (int)$this->request->getPost('received_quantity'),
                'cost' => (float)$this->request->getPost('total_cost'),
                'created_by' => session()->get('user_id')
            ];

            $this->inventoryModel->insert($data);
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Inventory entry added successfully'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to add inventory entry: ' . $e->getMessage()
            ]);
        }
    }

    public function edit($id)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to($this->viewPrefix . '/inventory');
        }

        $inventory = $this->getBaseQuery()->find($id);
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $inventory
        ]);
    }

    public function update()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to($this->viewPrefix . '/inventory');
        }

        // Add your validation rules here
        
        try {
            $id = $this->request->getPost('id');
            $data = [
                'product_id' => (int)$this->request->getPost('product_id'),
                'employee_id' => (int)$this->request->getPost('employee_id'),
                'issued_quantity' => (int)$this->request->getPost('issued_quantity'),
                'received_quantity' => (int)$this->request->getPost('received_quantity'),
                'cost' => (float)$this->request->getPost('total_cost')
            ];

            $this->inventoryModel->update($id, $data);
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Inventory updated successfully'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update inventory: ' . $e->getMessage()
            ]);
        }
    }

    public function delete($id)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to($this->viewPrefix . '/inventory');
        }

        try {
            $this->inventoryModel->delete($id);
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Inventory deleted successfully'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to delete inventory: ' . $e->getMessage()
            ]);
        }
    }

    // Add other common methods (edit, update, delete) with similar structure
} 