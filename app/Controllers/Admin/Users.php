<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Users extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $perPage = $this->request->getGet('per_page') ?? 10;
        $search = $this->request->getGet('search');
        $sort = $this->request->getGet('sort') ?? 'id';
        $order = $this->request->getGet('order') ?? 'asc';

        // Start the query
        $builder = $this->userModel;

        // Apply search if provided
        if ($search) {
            $builder->groupStart()
                    ->like('name', $search)
                    ->orLike('phone', $search)
                    ->orLike('role', $search)
                    ->groupEnd();
        }

        // Apply sorting
        $allowedSort = ['id', 'name', 'phone', 'role'];
        if (in_array($sort, $allowedSort)) {
            $builder->orderBy($sort, $order === 'asc' ? 'asc' : 'desc');
        }

        $data = [
            'title' => 'Users Management',
            'users' => $builder->paginate($perPage),
            'pager' => $builder->pager,
            'sort' => $sort,
            'order' => $order,
            'search' => $search
        ];

        return view('admin/users/index', $data);
    }

    public function add()
    {
        if ($this->request->isAJAX()) {
            return view('admin/user_form');
        }
        return redirect()->to('admin/users');
    }

    public function edit($id)
    {
        if ($this->request->isAJAX()) {
            $user = $this->userModel->find($id);
            if ($user) {
                return view('admin/user_form', ['user' => $user]);
            }
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not found'
            ]);
        }
        return redirect()->to('admin/users');
    }

    public function save()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('admin/users');
        }

        $id = $this->request->getPost('id');
        
        // Prepare the data first
        $data = [
            'name' => $this->request->getPost('name'),
            'phone' => $this->request->getPost('phone'),
            'role' => $this->request->getPost('role')
        ];

        // Add password for new users or if password is provided
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        } elseif (!$id) {
            // Require password for new users
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Password is required for new users',
                'errors' => ['password' => 'Password is required']
            ]);
        }

        // Simplified validation rules
        $rules = [
            'name' => 'required',
            'phone' => 'required',
            'role' => 'required'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $this->validator->getErrors()
            ]);
        }

        try {
            // Standardize role case
            $data['role'] = ucfirst(strtolower($data['role']));

            // Perform the save operation
            if ($id) {
                $result = $this->userModel->update($id, $data);
            } else {
                $result = $this->userModel->insert($data);
            }
            $error = $this->userModel->errors();
            if ($result) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'User saved successfully'
                ]);
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to save user'
            ]);

        } catch (\Exception $e) {
            log_message('error', '[Users::save] Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred while saving the user'
            ]);
        }
    }

    public function delete($id)
    {
        if ($this->request->isAJAX()) {
            if ($this->userModel->delete($id)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'User deleted successfully'
                ]);
            }
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error deleting user'
            ]);
        }
        return redirect()->to('admin/users');
    }

    public function getUsers()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('admin/users');
        }

        // Convert string values to integers
        $draw = (int) $this->request->getPost('draw');
        $start = (int) $this->request->getPost('start');
        $length = (int) $this->request->getPost('length');
        $search = $this->request->getPost('search')['value'];
        $order = $this->request->getPost('order')[0];
        
        $columns = ['name', 'phone', 'role'];
        $orderColumn = $columns[$order['column']];
        $orderDir = $order['dir'];

        // Get total count
        $total = $this->userModel->countAll();

        // Build query
        $builder = $this->userModel;
        
        // Apply search
        if ($search) {
            $builder->groupStart()
                    ->like('name', $search)
                    ->orLike('phone', $search)
                    ->orLike('role', $search)
                    ->groupEnd();
        }

        // Get filtered count
        $filtered = $builder->countAllResults(false);

        // Get data
        $users = $builder->orderBy($orderColumn, $orderDir)
                        ->limit($length, $start)
                        ->find();

        return $this->response->setJSON([
            'draw' => $draw,
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $users
        ]);
    }
} 