<?php

namespace App\Models;

use CodeIgniter\Model;

class InventoryModel extends Model
{
    protected $table = 'inventory';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    
    protected $allowedFields = [
        'product_id',
        'employee_id',
        'issued_quantity',
        'received_quantity',
        'cost',
        'created_by'
    ];
    
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'product_id' => 'required|integer',
        'employee_id' => 'required|integer',
        'issued_quantity' => 'required|integer|greater_than[0]',
        'received_quantity' => 'required|integer|greater_than_equal_to[0]',
        'cost' => 'required|numeric|greater_than_equal_to[0]',
        'created_by' => 'required|integer'
    ];

    protected $validationMessages = [
        'product_id' => [
            'required' => 'Product is required',
            'integer' => 'Invalid product selected'
        ],
        'employee_id' => [
            'required' => 'Employee is required',
            'integer' => 'Invalid employee selected'
        ],
        'issued_quantity' => [
            'required' => 'Issued quantity is required',
            'integer' => 'Issued quantity must be a whole number',
            'greater_than' => 'Issued quantity must be greater than 0'
        ],
        'received_quantity' => [
            'required' => 'Received quantity is required',
            'integer' => 'Received quantity must be a whole number',
            'greater_than_equal_to' => 'Received quantity cannot be negative'
        ],
        'cost' => [
            'required' => 'Total cost is required',
            'numeric' => 'Total cost must be a number',
            'greater_than_equal_to' => 'Total cost cannot be negative'
        ]
    ];

    protected $beforeInsert = ['cleanData'];
    protected $beforeUpdate = ['cleanData'];

    protected function cleanData(array $data)
    {
        if (isset($data['data'])) {
            if (isset($data['data']['product_name'])) {
                $data['data']['product_name'] = trim($data['data']['product_name']);
            }
            if (isset($data['data']['product_size'])) {
                $data['data']['product_size'] = trim($data['data']['product_size']);
            }
            if (isset($data['data']['total_cost'])) {
                $data['data']['total_cost'] = (float) $data['data']['total_cost'];
            }
        }
        return $data;
    }
} 