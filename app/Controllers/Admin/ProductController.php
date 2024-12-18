<?php

namespace App\Controllers\Admin;

use CodeIgniter\RESTful\ResourceController;

class ProductController extends ResourceController
{
    public function list()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('products');
        
        $request = service('request');
        
        // DataTables parameters
        $length = (int)$request->getPost('length') ?? 10;
        $start = (int)$request->getPost('start') ?? 0;
        $search = $request->getPost('search')['value'] ?? '';
        $draw = (int)$request->getPost('draw');
        
        // Count total records before filtering
        $totalRecords = $builder->countAllResults(false);
        
        // Apply search if any
        if (!empty($search)) {
            $builder->groupStart()
                ->like('name', $search)
                ->orLike('description', $search)
                ->groupEnd();
        }
        
        // Count filtered records
        $filteredRecords = $builder->countAllResults(false);
        
        // Get records with limit and offset
        $records = $builder->limit($length, $start)->get()->getResultArray();
        
        // Format the data for DataTables
        foreach ($records as &$record) {
            $record['status'] = $record['status'] ?? 'Active'; // Default status if not set
            // Format price if needed
            if (isset($record['price'])) {
                $record['price'] = number_format($record['price'], 2);
            }
        }
        
        $output = [
            "draw" => $draw,
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $filteredRecords,
            "data" => $records
        ];
        
        log_message('debug', 'Products query result: ' . json_encode($records));
        return $this->response->setJSON($output);
    } catch (\Exception $e) {
        log_message('error', 'Products list error: ' . $e->getMessage());
        return $this->response->setStatusCode(500)->setJSON([
            'error' => true,
            'message' => 'Error fetching products'
        ]);
    }
}

    public function delete($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('products');
        
        try {
            $deleted = $builder->delete(['id' => $id]);
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Product deleted successfully'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error deleting product'
            ]);
        }
    }
} 