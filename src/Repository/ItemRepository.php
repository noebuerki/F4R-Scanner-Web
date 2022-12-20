<?php

namespace App\Repository;

use App\DataBase\ConnectionHandler;
use Exception;

require_once '../src/DataBase/ConnectionHandler.php';

class ItemRepository extends Repository
{
    protected $tablename = "item";

    private $columnPosition = "position";
    private $columnBarcode = "barcode";
    private $columnSectionId = "sectionId";
    private $columnUserId = "userId";

    public function create($position, $barcode, $sectionId, $userId)
    {
        $query = "INSERT INTO $this->tablename ($this->columnPosition, $this->columnBarcode, $this->columnSectionId, $this->columnUserId) VALUES (?, ?, ?, ?)";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('iisi', $position, $barcode, $sectionId, $userId);
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
        $statement->bind_param('ii', $sectionId, $userId);
        $statement->execute();

        return $this->processMultipleResults($statement->get_result());
    }

    public function readByPosition($position, $sectionId, $userId) {
        $query = "SELECT * FROM $this->tablename WHERE $this->columnPosition = ? AND $this->columnSectionId = ? AND $this->columnUserId = ?";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('iii', $position, $sectionId, $userId);
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

    public function countItemsBySection($sectionId, $userId)
    {
        $query = "SELECT count(*) AS 'number' FROM $this->tablename WHERE $this->columnSectionId = ? AND $this->columnUserId = ?";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('ii', $sectionId, $userId);
        $statement->execute();

        return $this->processSingleResult($statement->get_result());
    }
}
