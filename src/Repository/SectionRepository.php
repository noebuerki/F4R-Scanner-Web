<?php

namespace App\Repository;

use App\DataBase\ConnectionHandler;
use Exception;

require_once '../src/DataBase/ConnectionHandler.php';

class SectionRepository extends Repository
{
    protected $tablename = "section";

    private $columnNumber = "number";
    private $columnTargetNumber = "targetNumber";
    private $columnScanner = "scanner";
    private $columnUserId = "userId";

    public function create($number, $targetNumber, $scanner, $userId)
    {
        $query = "INSERT INTO $this->tablename ($this->columnNumber, $this->columnTargetNumber, $this->columnScanner, $this->columnUserId) VALUES (?, ?, ?, ?)";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('iiii', $number, $targetNumber, $scanner, $userId);
        if (!$statement->execute()) {
            throw new Exception($statement->error);
        }
    }

    public function readAll($userId) {
        $query = "SELECT * FROM $this->tablename WHERE $this->columnUserId = ?";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('i', $userId);
        $statement->execute();

        return $this->processMultipleResults($statement->get_result());
    }

    public function readByID($id, $userId)
    {
        $query = "SELECT * FROM $this->tablename WHERE $this->columnId = ? AND $this->columnUserId = ?";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('ii', $id, $userId);
        $statement->execute();

        return $this->processSingleResult($statement->get_result());
    }

    public function readByNumber($number, $userId) {
        $query = "SELECT * FROM $this->tablename WHERE $this->columnNumber = ? AND $this->columnUserId = ?";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('ii', $number, $userId);
        $statement->execute();

        return $this->processSingleResult($statement->get_result());
    }

    public function deleteById($id, $userId)
    {
        $query = "DELETE FROM $this->tablename WHERE $this->columnId = ? AND $this->columnUserId = ?";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('ii', $id, $userId);

        if (!$statement->execute()) {
            throw new Exception($statement->error);
        }
    }

    public function countSections($userId)
    {
        $query = "SELECT count(*) AS 'number' FROM $this->tablename WHERE $this->columnUserId = ?";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('i', $userId);
        $statement->execute();

        return $this->processSingleResult($statement->get_result());
    }

    public function countTotalSections()
    {
        $query = "SELECT count(*) AS 'number' FROM $this->tablename";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->execute();
        return $this->processSingleResult($statement->get_result());
    }
}
