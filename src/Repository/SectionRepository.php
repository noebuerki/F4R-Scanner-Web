<?php

namespace App\Repository;

use App\DataBase\ConnectionHandler;
use Exception;

require_once '../src/DataBase/ConnectionHandler.php';

class SectionRepository extends Repository
{
    protected $tablename = "section";

    private $columnNumber = "number";
    private $columnTargetQuantity = "targetQuantity";
    private $columnStocktakingId = "stocktakingId";
    private $columnUserId = "userId";

    public function create($number, $targetQuantity, $stocktakingId, $userId)
    {
        $query = "INSERT INTO $this->tablename ($this->columnNumber, $this->columnTargetQuantity, $this->columnStocktakingId, $this->columnUserId) VALUES (?, ?, ?, ?)";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('iiii', $number, $targetQuantity, $stocktakingId, $userId);
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

    public function readByStocktaking($stocktakingId, $userId) {
        $query = "SELECT * FROM $this->tablename WHERE $this->columnStocktakingId = ? AND $this->columnUserId = ?";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('ii', $stocktakingId, $userId);
        $statement->execute();

        return $this->processMultipleResults($statement->get_result());
    }

    public function readByNumberAndStocktaking($number, $stocktakingId, $userId) {
        $query = "SELECT * FROM $this->tablename WHERE $this->columnNumber = ? AND $this->columnStocktakingId = ? AND $this->columnUserId = ?";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('iii', $number, $stocktakingId, $userId);
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
