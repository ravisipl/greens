<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseInventoryController;

class Inventory extends BaseInventoryController
{
    public function __construct()
    {
        parent::__construct();
        $this->viewPrefix = 'admin';
    }

    // Override methods only if admin needs specific behavior
} 