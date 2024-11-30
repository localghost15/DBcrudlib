install : composer dump-autoload 

ishlatish : require_once 'vendor/autoload.php';

use App\Config\Database;
use App\Services\CRUD;

// Ma’lumotlar bazasi konfiguratsiyasi
$config = [
    'host' => 'localhost',
    'dbname' => 'database_name',
    'user' => 'username',
    'password' => 'password',
];

// Xizmatni yaratish
$database = new Database($config);
$crud = new CRUD($database);


CRUD metodlari
1. Ma’lumot qo‘shish
Metod nomi: create
Tavsif: Jadvalga yangi ma’lumotlar qo‘shadi.

Parametrlar:

$table: Jadval nomi (string).
$data: Qo‘shiladigan ma’lumotlar (associative array).
Foydalanish:

php
Копировать код
$data = [
    'name' => 'Mahsulot nomi',
    'price' => 10000
];
$result = $crud->create('products', $data);
if ($result) {
    echo "Ma’lumot qo‘shildi!";
}
2. Ma’lumotlarni o‘qish
Metod nomi: read
Tavsif: Jadvaldan barcha ma’lumotlarni olish uchun ishlatiladi.

Parametrlar:

$table: Jadval nomi (string).
$order: Tartiblash (string, ASC yoki DESC, default — ASC).
Foydalanish:

php
Копировать код
$data = $crud->read('products');
foreach ($data as $row) {
    echo $row['name'] . " - " . $row['price'] . "\n";
}
3. Bitta ma’lumotni o‘qish
Metod nomi: readSingle
Tavsif: Id bo‘yicha bitta yozuvni olish.

Parametrlar:

$table: Jadval nomi (string).
$id: Yozuvning ID qiymati.
Foydalanish:

php
Копировать код
$id = 1;
$data = $crud->readSingle('products', $id);
if ($data) {
    echo "Mahsulot nomi: " . $data['name'];
}
4. Shart bo‘yicha ma’lumotlarni o‘qish
Metod nomi: readAllWithWhere
Tavsif: Shart asosida yozuvlarni qaytaradi.

Parametrlar:

$table: Jadval nomi (string).
$conditions: Shartlar (associative array).
Foydalanish:

php
Копировать код
$conditions = ['price' => 10000];
$data = $crud->readAllWithWhere('products', $conditions);
foreach ($data as $row) {
    echo $row['name'] . "\n";
}
5. Ma’lumotlarni yangilash
Metod nomi: update
Tavsif: Jadvaldagi yozuvlarni yangilash.

Parametrlar:

$table: Jadval nomi (string).
$data: Yangilanishi kerak bo‘lgan ma’lumotlar (associative array).
$conditions: Shartlar (associative array).
Foydalanish:

php
Копировать код
$data = ['price' => 12000];
$conditions = ['id' => 1];
$result = $crud->update('products', $data, $conditions);
if ($result) {
    echo "Ma’lumot yangilandi!";
}
6. Ma’lumotlarni o‘chirish
Metod nomi: delete
Tavsif: Shart asosida yozuvlarni o‘chirish.

Parametrlar:

$table: Jadval nomi (string).
$conditions: Shartlar (associative array).
Foydalanish:

php
Копировать код
$conditions = ['id' => 1];
$result = $crud->delete('products', $conditions);
if ($result) {
    echo "Ma’lumot o‘chirildi!";
}
7. Pagination bilan o‘qish
Metod nomi: readAllWithPagination
Tavsif: Shartlar asosida sahifalash bilan yozuvlarni qaytaradi.

Parametrlar:

$table: Jadval nomi (string).
$recordsPerPage: Har bir sahifada yozuvlar soni (integer).
$page: Sahifa raqami (integer).
Foydalanish:

php
Копировать код
$data = $crud->readAllWithPagination('products', [], 'price', 'ASC', 5, 2);
foreach ($data as $row) {
    echo $row['name'] . "\n";
}


