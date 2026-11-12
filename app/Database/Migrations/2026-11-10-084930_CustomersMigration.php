<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CustomersMigration extends Migration
{
    public function up()
    {
        // Define customers table fields
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],

            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],

            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ],

            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => true,
            ],

            'phone' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],

            'address' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'tax_id' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
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
            ],
        ]);

        // Primary key
        $this->forge->addKey('id', true);

        $this->forge->addKey('user_id');

        // Foreign key: customers user_id 
        $this->forge->addForeignKey(
            'user_id',
            'users',
            'id',
            'CASCADE',
            'CASCADE'
        );

        // Create customers table
        $this->forge->createTable('customers');
    }

    public function down()
    {
        // Drop customers table
        $this->forge->dropTable('customers');
    }
}
