<?php

namespace App\Repository;

use App\DataBase\ConnectionHandler;

class ItemRepository extends Repository
{
    protected $tablename = "item";

    protected $columnUserId = "userId";
    protected $columnCustomerId = "customerId";
    protected $columnStorageNumber = "storageNumber";
    protected $columnInStorage = "inStorage";
    protected $columnName = "name";
    protected $columnValue = "value";
    protected $columnPrice = "price";
    protected $columnDropOfDate = "dropOfDate";
    protected $columnPickUpDate = "pickupDate";

    public function readByID($userId, $id)
    {
        $query = "SELECT * FROM $this->tablename WHERE $this->columnUserId = ? AND $this->columnId = ?";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param("ii", $userId, $id);
        $statement->execute();

        return $this->processSingleResult($statement->get_result());
    }

    public function readByCustomer($userId, $customerId)
    {
        $query = "SELECT * FROM $this->tablename WHERE $this->columnUserId = ? AND $this->columnCustomerId = ?";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param("ii", $userId, $customerId);
        $statement->execute();

        return $this->processMultipleResults($statement->get_result());
    }

    public function readStorageNumbersByCustomer($userId, $customerId) {
        $query = "SELECT DISTINCT $this->columnStorageNumber FROM $this->tablename WHERE $this->columnUserId = ? AND $this->columnCustomerId = ?";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param("ii", $userId, $customerId);
        $statement->execute();

        return $this->processMultipleResults($statement->get_result());
    }

    public function readByStorageNumber($userId, $storageNumber)
    {
        $query = "SELECT * FROM $this->tablename WHERE $this->columnUserId = ? AND $this->columnStorageNumber = ?";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param("ii", $userId, $storageNumber);
        $statement->execute();

        return $this->processMultipleResults($statement->get_result());
    }

    public function readFirstByStorageNumber($userId, $storageNumber)
    {
        $query = "SELECT * FROM $this->tablename WHERE $this->columnUserId = ? AND $this->columnStorageNumber = ? LIMIT 1";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param("ii", $userId, $storageNumber);
        $statement->execute();

        return $this->processSingleResult($statement->get_result());
    }

    public function deleteById($userId, $id)
    {
        $query = "DELETE FROM $this->tablename WHERE $this->columnUserId = ? AND $this->columnId = ?";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('ii', $userId, $id);

        if (!$statement->execute()) {
            throw new Exception($statement->error);
        }
    }

    public function create($userId, $customerId, $storageNumber, $inStorage, $name, $value, $price, $dropOfDate, $pickupDate)
    {
        $query = "INSERT INTO $this->tablename ($this->columnUserId, $this->columnCustomerId, $this->columnStorageNumber, $this->columnInStorage, $this->columnName, $this->columnValue, $this->columnPrice, $this->columnDropOfDate, $this->columnPickUpDate) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('iiiisddss', $userId, $customerId, $storageNumber, $inStorage, $name, $value, $price, $dropOfDate, $pickupDate);
        if (!$statement->execute()) {
            throw new Exception($statement->error);
        }
    }

    public function update($userId, $id, $storageNumber, $inStorage, $name, $value, $price, $dropOfDate, $pickupDate)
    {
        $query = "UPDATE $this->tablename SET $this->columnStorageNumber= ?, $this->columnInStorage= ?, $this->columnName= ?, $this->columnValue= ?, $this->columnPrice= ?, $this->columnDropOfDate= ?, $this->columnPickUpDate = ? WHERE $this->columnUserId = ? AND $this->columnId = ?";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('ibsddssii', $storageNumber, $inStorage, $name, $value, $price, $dropOfDate, $pickupDate, $userId, $id);
        if (!$statement->execute()) {
            throw new Exception($statement->error);
        }
    }

    public function countItems($userId)
    {
        $query = "SELECT count(*) AS 'number' FROM $this->tablename WHERE $this->columnUserId = ?";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('i', $userId);
        $statement->execute();

        return $this->processSingleResult($statement->get_result());
    }
}