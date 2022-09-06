<?php

namespace App\Repository;

use App\DataBase\ConnectionHandler;
use Exception;

require_once '../src/DataBase/ConnectionHandler.php';

class UserRepository extends Repository
{
    protected $tablename = "user";

    private $columnUsername = "username";
    private $columnEmail = "email";
    private $columnPassword = "password";
    private $columnAdmin = "admin";

    /* Database-Statements */
    public function readByID($id)
    {
        $query = "SELECT * FROM $this->tablename WHERE $this->columnId = ?";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('i', $id);
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


    public function readByUsername($username)
    {
        $query = "SELECT * FROM $this->tablename WHERE $this->columnUsername = ?";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('s', $username);
        $statement->execute();

        return $this->processSingleResult($statement->get_result());
    }

    public function add($username, $email, $password)
    {
        $query = "INSERT INTO $this->tablename ($this->columnUsername, $this->columnEmail, $this->columnPassword) VALUES (?, ?, ?)";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('sss', $username, $email, password_hash($password, PASSWORD_DEFAULT));
        if (!$statement->execute()) {
            throw new Exception($statement->error);
        }
    }

    public function updateMail($userId, $mail)
    {
        $query = "UPDATE $this->tablename SET $this->columnEmail = ? WHERE $this->columnId = ?";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('si', $mail, $userId);
        if (!$statement->execute()) {
            throw new Exception($statement->error);
        }
    }

    public function updatePassword($userId, $password)
    {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $query = "UPDATE $this->tablename SET $this->columnPassword = ? WHERE $this->columnEmail = ?";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('si', $password, $userId);
        if (!$statement->execute()) {
            throw new Exception($statement->error);
        }
    }

    public function countUsers()
    {
        $query = "SELECT count(*) AS 'number' FROM $this->tablename";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->execute();
        return $this->processSingleResult($statement->get_result());
    }
}
