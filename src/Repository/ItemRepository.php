<?php

namespace App\Repository;

use App\DataBase\ConnectionHandler;
use Exception;

require_once '../src/DataBase/ConnectionHandler.php';

class ItemRepository extends Repository
{
    protected $tablename = "item";

    private $columnPosition = "position";
    private $columnSectionId = "sectionId";
    private $columnBarcode = "barcode";
    private $columnUserId = "userId";

    public function create($position, $sectionId, $barcode, $userId)
    {
        $query = "INSERT INTO $this->tablename ($this->columnPosition, $this->columnSectionId, $this->columnBarcode, $this->columnUserId) VALUES (?, ?, ?, ?)";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('iisi', $number, $targetNumber, $scanner, $userId);
        if (!$statement->execute()) {
            throw new Exception($statement->error);
        }
    }

    public function readByID($id, $userId)
    {
        $query = "SELECT * FROM $this->tablename WHERE $this->columnId = ? AND $this->columnUserId = ?";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('ii', $id, $userId);
        $statement->execute();

        return $this->processSingleResult($statement->get_result());
    }

    public function readBySection($sectionId, $userId) {
        $query = "SELECT * FROM $this->tablename WHERE $this->columnSectionId = ? AND $this->columnUserId = ?";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('ii', $sectonId, $userId);
        $statement->execute();

        return $this->processMultipleResults($statement->get_result());
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

    public function countItems($userId)
    {
        $query = "SELECT count(*) AS 'number' FROM $this->tablename WHERE $this->columnUserId = ?";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('i', $userId);
        $statement->execute();

        return $this->processSingleResult($statement->get_result());
    }

    public function countTotalItems()
    {
        $query = "SELECT count(*) AS 'number' FROM $this->tablename";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->execute();
        return $this->processSingleResult($statement->get_result());
    }
}
