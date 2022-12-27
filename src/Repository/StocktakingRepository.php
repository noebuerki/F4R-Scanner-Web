<?php

namespace App\Repository;

use App\DataBase\ConnectionHandler;
use Exception;

require_once '../src/DataBase/ConnectionHandler.php';

class StocktakingRepository extends Repository
{
    protected $tablename = "stocktaking";
    private $columnDate = "date";
    private $columnBranch = "branch";
    private $columnUserId = "userId";

    public function create($date, $branch, $userId)
    {
        $query = "INSERT INTO $this->tablename ($this->columnDate, $this->columnBranch, $this->columnUserId) VALUES (?, ?, ?)";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('sii', $date, $branch, $userId);
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
    public function deleteById($id, $userId)
    {
        $query = "DELETE FROM $this->tablename WHERE $this->columnId = ? AND $this->columnUserId = ?";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('ii', $id, $userId);

        if (!$statement->execute()) {
            throw new Exception($statement->error);
        }
    }

    public function countStocktakings($userId)
    {
        $query = "SELECT count(*) AS 'number' FROM $this->tablename WHERE $this->columnUserId = ?";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('i', $userId);
        $statement->execute();

        return $this->processSingleResult($statement->get_result());
    }

    public function countTotalStocktakings()
    {
        $query = "SELECT count(*) AS 'number' FROM $this->tablename";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->execute();
        return $this->processSingleResult($statement->get_result());
    }
}
