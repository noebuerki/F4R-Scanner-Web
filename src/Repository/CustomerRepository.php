<?php

namespace App\Repository;

use App\DataBase\ConnectionHandler;
use Exception;

require_once '../src/DataBase/ConnectionHandler.php';

class CustomerRepository extends Repository
{
    protected $tablename = "customer";

    protected $columnUserId = "userId";
    protected $columnCustomerNumber = "customerNumber";
    protected $columnStorageSpace = "storageSpace";
    protected $columnTitle = "title";
    protected $columnFirstName = "firstName";
    protected $columnLastName = "lastName";
    protected $columnMiddleName = "middleName";
    protected $columnStreet = "street";
    protected $columnHouseNumber = "houseNumber";
    protected $columnPostCode = "postCode";
    protected $columnCity = "city";

    /* Database-Statements */
    public function readByID($userId, $id)
    {
        $query = "SELECT * FROM $this->tablename WHERE $this->columnUserId = ? AND $this->columnId = ?";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param("ii", $userId, $id);
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

    public function readByCustomerNumber($userId, $customerNumber)
    {
        $query = "SELECT * FROM $this->tablename WHERE $this->columnUserId = ? AND $this->columnCustomerNumber = ?";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param("ii", $userId, $customerNumber);
        $statement->execute();

        return $this->processSingleResult($statement->get_result());
    }

    public function readByName($userId, $firstName, $lastName)
    {
        $query = "";
        if (is_string($firstName) && is_string($lastName)) {
            $query = "SELECT * FROM $this->tablename WHERE $this->columnUserId = ? AND $this->columnFirstName LIKE ? AND $this->columnLastName LIKE ?";
        } elseif (is_string($firstName)) {
            $query = "SELECT * FROM $this->tablename WHERE $this->columnUserId = ? AND $this->columnFirstName LIKE ?";
        } elseif (is_string($lastName)) {
            $query = "SELECT * FROM $this->tablename WHERE $this->columnUserId = ? AND $this->columnLastName LIKE ?";
        }

        $statement = ConnectionHandler::getConnection()->prepare($query);

        if (is_string($firstName) && is_string($lastName)) {
            $wildCardFirstName = "%$firstName%";
            $wildCardLastName = "%$lastName%";
            $statement->bind_param('iss', $userId, $wildCardFirstName, $wildCardLastName);
        } elseif (is_string($firstName)) {
            $statement->bind_param('is', $userId, $wildCardFirstName);
        } elseif (is_string($lastName)) {
            $statement->bind_param('is', $userId, $wildCardLastName);
        }

        $statement->execute();

        return $this->processMultipleResults($statement->get_result());
    }

    public function create($userId, $customerNumber, $storageSpace, $title, $firstName, $lastName, $middleName, $street, $houseNumber, $postCode, $city)
    {
        $query = "INSERT INTO $this->tablename ($this->columnUserId, $this->columnCustomerNumber, $this->columnStorageSpace, $this->columnTitle, $this->columnFirstName, $this->columnLastName, $this->columnMiddleName, $this->columnStreet, $this->columnHouseNumber, $this->columnPostCode, $this->columnCity) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('iiissssssss', $userId, $customerNumber, $storageSpace, $title, $firstName, $lastName, $middleName, $street, $houseNumber, $postCode, $city);
        if (!$statement->execute()) {
            throw new Exception($statement->error);
        }
    }

    public function update($userId, $id, $customerNumber, $storageSpace, $title, $firstName, $lastName, $middleName, $street, $houseNumber, $postCode, $city)
    {
        $query = "UPDATE $this->tablename SET $this->columnCustomerNumber = ?, $this->columnStorageSpace = ?, $this->columnTitle = ?, $this->columnFirstName = ?, $this->columnLastName = ?, $this->columnMiddleName = ?, $this->columnStreet = ?, $this->columnHouseNumber = ?, $this->columnPostCode = ?, $this->columnCity = ? WHERE $this->columnUserId = ? AND $this->columnId = ?";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('iissssssssii', $customerNumber, $storageSpace, $title, $firstName, $lastName, $middleName, $street, $houseNumber, $postCode, $city, $userId, $id);
        if (!$statement->execute()) {
            throw new Exception($statement->error);
        }
    }

    public function countCustomers($userId)
    {
        $query = "SELECT count(*) AS 'number' FROM $this->tablename WHERE $this->columnUserId = ?";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('i', $userId);
        $statement->execute();

        return $this->processSingleResult($statement->get_result());
    }
}
