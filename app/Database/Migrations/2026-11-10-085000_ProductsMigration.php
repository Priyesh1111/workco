<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ProductsMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],

            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true
            ],

            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false
            ],

            'description' => [
                'type' => 'TEXT',
                'null' => true
            ],

            'unit_price' => [
                'type' => 'DECIMAL',
                'constraint' => '12,2',
                'null' => false,
                'default' => 0.00,
            ],

            'tax_percent' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => false,
                'default' => 0.00,
            ],

            'created_at' => [
                'type' => 'TIMESTAMP',
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
            ],

            'updated_at' => [
                'type' => 'TIMESTAMP',
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
            ],

            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ]
        ]);

        $this->forge->addKey('id', true);

        // Index for user_id
        $this->forge->addKey('user_id');

        // Foreign key: products user_id 
        $this->forge->addForeignKey(
            'user_id',
            'users',
            'id',
            'CASCADE',
            'CASCADE'
        );

        // Create products table
        $this->forge->createTable('products');
    }

    public function down()
    {
        $this->forge->dropTable('products');
    }
}
