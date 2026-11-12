<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use PHPUnit\Framework\Constraint\Constraint;

class SettingsMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'usnsigned' => true,
                'auto_increment' => true
            ],

            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true
            ],

            'company_name' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => true,
            ],

            'address' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'phone' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],

            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => true,
            ],

            'tax_id' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],

            'logo_path' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],

            'bank_details' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'invoice_prefix' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'default' => 'INV',
            ],

            'footer_note' => [
                'type' => 'TEXT',
                'null' => true
            ],
        ]);

        // Primary key
        $this->forge->addKey('id', true);

        // Each user can have only one settings record
        $this->forge->addKey('user_id', false, true);

        // Foreign key: settings.user_id references users.id
        $this->forge->addForeignKey(
            'user_id',
            'users',
            'id',
            'CASCADE',
            'CASCADE'
        );

        // Create settings table
        $this->forge->createTable('settings');
    }

    public function down()
    {
        $this->forge->dropTable('settings');
    }
}
