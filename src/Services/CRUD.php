<?php

namespace App\Services;

use App\Config\DatabaseInterface;
use PDO;

class CRUD
{
    private $pdo;

    public function __construct(DatabaseInterface $database)
    {
        $this->pdo = $database->connect();
    }

// Ma`lumotlarni bazaga yozish uchun metod
    public function create($table, $data)
    {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));

        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute($data);
    }

    // Bazadan ma`lumotlarni olish uchun metod
    public function read($table, $order = 'ASC')
    {
        $sql = "SELECT * FROM $table ORDER BY id $order"; // ID bo`yicha filtrlash uchun so`rov
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // id bo`yicha ma`lumotni olish uchun metod

    public function readSingle($table, $id, $order = 'ASC')
    {
        $sql = "SELECT * FROM $table WHERE id = :id ORDER BY id $order LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function readAllWithWhere($table, $conditions = [], $orderColumn = null, $orderDirection = 'ASC')
    {
        // AGar shartlar ko`rsatilgna bo`lsa WHERE sharti ishlashi uchun
        $where = [];
        foreach ($conditions as $column => $value) {
            $where[] = "$column = :where_$column";
        }

        $whereString = '';
        if (!empty($where)) {
            $whereString = 'WHERE ' . implode(' AND ', $where);
        }

        // Agar row ko`rsatilgan bo`lsa ORDER BY qismini ishlatish
        $orderString = '';
        if ($orderColumn) {
            $orderDirection = strtoupper($orderDirection) === 'DESC' ? 'DESC' : 'ASC';
            $orderString = "ORDER BY $orderColumn $orderDirection";
        }

        // SQL query
        $sql = "SELECT * FROM $table $whereString $orderString";
        $stmt = $this->pdo->prepare($sql);

        // WHERE uchun parametrlarni qurish
        foreach ($conditions as $column => $value) {
            $stmt->bindParam(":where_$column", $value);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function readAllWithPagination($table, $conditions = [], $orderColumn = null, $orderDirection = 'ASC', $recordsPerPage = 10, $page = 1)
    {
        // AGar shartlar ko`rsatilgna bo`lsa WHERE sharti ishlashi uchun
        $where = [];
        foreach ($conditions as $column => $value) {
            $where[] = "$column = :where_$column";
        }

        $whereString = '';
        if (!empty($where)) {
            $whereString = 'WHERE ' . implode(' AND ', $where);
        }

        //
        $orderString = '';
        if ($orderColumn) {
            $orderDirection = strtoupper($orderDirection) === 'DESC' ? 'DESC' : 'ASC';
            $orderString = "ORDER BY $orderColumn $orderDirection";
        }

        // Pagination uchun
        $offset = ($page - 1) * $recordsPerPage;

        // Pagination bilan to`liq sql query
        $sql = "SELECT * FROM $table $whereString $orderString LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);

        // WHERE uchun parametrlar
        foreach ($conditions as $column => $value) {
            $stmt->bindParam(":where_$column", $value);
        }

        // LIMIT va OFFSET parametrlari
        $stmt->bindParam(':limit', $recordsPerPage, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }




    // Ma`lumotlarni o`zgartirish uchun metod
    public function update($table, $data, $conditions)
    {
        // SET qismi
        $set = [];
        foreach ($data as $column => $value) {
            $set[] = "$column = :$column";
        }
        $setString = implode(", ", $set);

        // WHERE qismi
        $where = [];
        foreach ($conditions as $column => $value) {
            $where[] = "$column = :where_$column";
        }
        $whereString = implode(" AND ", $where);

        // SQL query
        $sql = "UPDATE $table SET $setString WHERE $whereString";
        $stmt = $this->pdo->prepare($sql);

        foreach ($data as $column => $value) {
            $stmt->bindParam(":$column", $value);
        }

        foreach ($conditions as $column => $value) {
            $stmt->bindParam(":where_$column", $value);
        }

        return $stmt->execute();
    }

    // Ma`lumotlarni o`chirish uchun metod
    public function delete($table, $conditions)
    {
        $where = [];
        foreach ($conditions as $column => $value) {
            $where[] = "$column = :where_$column";
        }
        $whereString = implode(" AND ", $where);

        $sql = "DELETE FROM $table WHERE $whereString";
        $stmt = $this->pdo->prepare($sql);

        foreach ($conditions as $column => $value) {
            $stmt->bindParam(":where_$column", $value);
        }

        return $stmt->execute();
    }
}