<?php

namespace Kevinhdzz\MyTasks\Database;

use PDO;
use PDOException;

class DB {
    /** @var PDO $pdo Database connection object. */
    public PDO $pdo;

    /**
     * Initializes the connection to the database.
     * 
     * @throws PDOException — if the attempt to connect to the requested database fails.
     */
    public function __construct()
    {
        [
            'host' => $host,
            'database' => $database,
            'port' => $port,
            'username' => $username,
            'password' => $password,
        ] = require __DIR__ . '/../../config/database.php';
        
        $dsn = "mysql:host={$host};port={$port};dbname={$database}";
        
        $this->pdo = new PDO($dsn, $username, $password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Executes a statement and returns the response.
     * 
     * @param string $statement
     * @param array $bind Values to be replaced in the statement.
     * @return mixed statement result.
     */
    public function statement(string $statement, array $bind = []): mixed
    {
        $stmt = $this->pdo->prepare($statement);
        $stmt->execute($bind);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
