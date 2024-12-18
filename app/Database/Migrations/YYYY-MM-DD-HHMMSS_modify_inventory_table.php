<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyInventoryTable extends Migration
{
    public function up()
    {
        // First, rename the existing quantity column to issued_quantity
        $this->forge->modifyColumn('inventory', [
            'quantity' => [
                'name' => 'issued_quantity',
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
            ]
        ]);

        // Add new columns
        $fields = [
            'received_quantity' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
                'default' => 0,
                'after' => 'issued_quantity'
            ],
            'unit_cost' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
                'default' => 0.00,
                'after' => 'received_quantity'
            ],
            'total_cost' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
                'default' => 0.00,
                'after' => 'unit_cost'
            ]
        ];

        $this->forge->addColumn('inventory', $fields);
    }

    public function down()
    {
        // Revert the changes in case of rollback
        $this->forge->modifyColumn('inventory', [
            'issued_quantity' => [
                'name' => 'quantity',
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
            ]
        ]);

        // Remove the added columns
        $this->forge->dropColumn('inventory', ['received_quantity', 'unit_cost', 'total_cost']);
    }
} 