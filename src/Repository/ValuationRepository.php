<?php

namespace App\Repository;

use App\DataBase\ConnectionHandler;

class ValuationRepository extends Repository
{
    protected $tablename = "valuation";

    protected $columnUserId = "userId";
    protected $columnValue = "value";
    protected $columnPrice = "price";

    public function readByID($userId, $id)
    {
        $query = "SELECT * FROM $this->tablename WHERE $this->columnUserId = ? AND $this->columnId = ?";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param("ii", $userId, $id);
        $statement->execute();

        return $this->processSingleResult($statement->get_result());
    }

    public function readNextBiggerValue($userId, $value) {
        $query = "SELECT * FROM $this->tablename WHERE $this->columnUserId = ? AND $this->columnValue = (SELECT MIN($this->columnValue) FROM $this->tablename WHERE $this->columnUserId = ? AND $this->columnValue >= ?)";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param("iid", $userId, $userId, $value);
        $statement->execute();

        return $this->processSingleResult($statement->get_result());
    }

    public function deleteById($id)
    {
        $query = "DELETE FROM $this->tablename WHERE $this->columnId = ?";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('i', $id);

        if (!$statement->execute()) {
            throw new Exception($statement->error);
        }
    }

    public function create($userId, $value, $price)
    {
        $query = "INSERT INTO $this->tablename ($this->columnUserId, $this->columnValue, $this->columnPrice) VALUES (?, ?, ?)";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('idd', $userId, $value, $price);
        if (!$statement->execute()) {
            throw new Exception($statement->error);
        }
    }

    public function update($userId, $id, $value, $price)
    {
        $query = "UPDATE $this->tablename SET $this->columnValue = ?, $this->columnPrice = ? WHERE $this->columnUserId = ? AND $this->columnId = ?";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('ddii', $value, $price, $userId, $id);
        if (!$statement->execute()) {
            throw new Exception($statement->error);
        }
    }
}