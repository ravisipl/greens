<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\UserModel;
use App\Models\InventoryModel;

class Dashboard extends BaseController
{
    protected $productModel;
    protected $userModel;
    protected $inventoryModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->userModel = new UserModel();
        $this->inventoryModel = new InventoryModel();
    }

    public function index()
    {
        // Get today's statistics
        $todayStats = $this->inventoryModel
            ->select('
                users.name as employee_name,
                COUNT(inventory.id) as total_entries,
                SUM(inventory.received_quantity) as total_quantity,
                SUM(inventory.cost) as total_cost
            ')
            ->join('users', 'users.id = inventory.employee_id')
            ->where('DATE(inventory.created_at)', date('Y-m-d'))
            ->groupBy('inventory.employee_id')
            ->find();

        // Get current month statistics
        $monthStats = $this->inventoryModel
            ->select('
                users.name as employee_name,
                COUNT(inventory.id) as total_entries,
                SUM(inventory.received_quantity) as total_quantity,
                SUM(inventory.cost) as total_cost
            ')
            ->join('users', 'users.id = inventory.employee_id')
            ->where('MONTH(inventory.created_at)', date('m'))
            ->where('YEAR(inventory.created_at)', date('Y'))
            ->groupBy('inventory.employee_id')
            ->find();

        $data = [
            'title' => 'Dashboard',
            'total_products' => $this->productModel->countAll(),
            'total_users' => $this->userModel->countAll(),
            'today_stats' => $todayStats,
            'month_stats' => $monthStats,
            'today_total' => array_sum(array_column($todayStats, 'total_cost')),
            'month_total' => array_sum(array_column($monthStats, 'total_cost'))
        ];

        return view('user/dashboard', $data);
    }
} 