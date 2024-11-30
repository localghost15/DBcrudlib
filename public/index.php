<?php

require_once '../vendor/autoload.php';

use App\Config\MySQLDatabase;
use App\Services\CRUD;

// DB ni tanlash
$database = new MySQLDatabase(); //  PostgresDatabase ga o`zgartirish mumkin
$crud = new CRUD($database);

// misol
$table = 'users';

// CREATE
$crud->create($table, ['name' => 'John Doe', 'email' => 'john@example.com']);

// READ
$users = $crud->read($table);
print_r($users);

// UPDATE
$crud->update($table, ['name' => 'Jane Doe'], ['id' => 1]);

// DELETE
$crud->delete($table, ['id' => 1]);
