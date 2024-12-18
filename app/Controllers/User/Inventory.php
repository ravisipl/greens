<?php

namespace App\Controllers\User;

use App\Controllers\BaseInventoryController;

class Inventory extends BaseInventoryController
{
    public function __construct()
    {
        parent::__construct();
        $this->viewPrefix = 'user';
    }

    protected function shouldFilterByUser()
    {
        // Users can only see their own records
        return true;
    }

    // Override other methods only if user needs specific behavior
} 