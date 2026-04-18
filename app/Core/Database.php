<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

/**
 * Database class - PDO singleton for database operations
 */
class Database
{
    private static ?self $instance = null;
    private PDO $pdo;
    private string $dsn;
    private string $username;
    private string $password;
    private array $options;

    private function __construct(
        string $dsn = '',
        string $username = '',
        string $password = '',
        array $options = []
    ) {
        $this->dsn = $dsn ?: $this->getDefaultDsn();
        $this->username = $username ?: $_ENV['DB_USER'] ?? 'root';
        $this->password = $password ?: $_ENV['DB_PASSWORD'] ?? '';
        $this->options = array_merge([
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ], $options);

        $this->connect();
    }

    /**
     * Get default DSN from environment
     */
    private function getDefaultDsn(): string
    {
        $host = $_ENV['DB_HOST'] ?? 'localhost';
        $port = $_ENV['DB_PORT'] ?? 3306;
        $database = $_ENV['DB_NAME'] ?? 'abelo';

        return "mysql:host={$host}:{$port};dbname={$database}";
    }

    /**
     * Get database instance (singleton pattern)
     */
    public static function getInstance(): ?self
    {
        return self::$instance;
    }

    /**
     * Initialize the database connection
     */
    public static function initialize(
        string $dsn,
        string $username = '',
        string $password = '',
        array $options = []
    ): self {
        if (self::$instance === null) {
            self::$instance = new self($dsn, $username, $password, $options);
        }
        return self::$instance;
    }

    /**
     * Create a PDO connection
     */
    private function connect(): void
    {
        try {
            $this->pdo = new PDO(
                $this->dsn,
                $this->username,
                $this->password,
                $this->options
            );
            Logger::getInstance()->info('Database connection established', [
                'dsn' => $this->dsn,
            ]);
        } catch (PDOException $e) {
            Logger::getInstance()->critical('Database connection failed: ' . $e->getMessage(), [
                'dsn' => $this->dsn,
            ]);
            throw new PDOException('Database connection failed: ' . $e->getMessage());
        }
    }

    /**
     * Get the PDO instance
     */
    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    /**
     * Execute a raw SQL query (for migrations)
     */
    public function execute(string $query, array $params = []): bool
    {
        try {
            if (empty($params)) {
                return $this->pdo->exec($query) !== false;
            } else {
                $stmt = $this->pdo->prepare($query);
                return $stmt->execute($params);
            }
        } catch (PDOException $e) {
            Logger::getInstance()->error('Query execution failed: ' . $query, [
                'error' => $e->getMessage(),
                'params' => $params,
            ]);
            throw new PDOException('Query execution failed: ' . $e->getMessage());
        }
    }

    /**
     * ...existing code...
    {
        return $this->pdo->prepare($query);
    }

    /**
     * Execute a query and return results
     */
    public function query(string $query, array $params = [])
    {
        try {
            $stmt = $this->pdo->prepare($query);
            if (!$stmt->execute($params)) {
                throw new PDOException('Query execution failed');
            }
            Logger::getInstance()->query($query, $params);
            return $stmt;
        } catch (PDOException $e) {
            Logger::getInstance()->error('Database Query Error: ' . $query, [
                'error' => $e->getMessage(),
                'params' => $params,
            ]);
            throw new PDOException('Query execution failed: ' . $e->getMessage());
        }
    }

    /**
     * Fetch one row
     */
    public function fetchOne(string $query, array $params = []): ?array
    {
        $stmt = $this->query($query, $params);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Fetch all rows
     */
    public function fetchAll(string $query, array $params = []): array
    {
        $stmt = $this->query($query, $params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Fetch a single column value
     */
    public function fetchColumn(string $query, array $params = []): mixed
    {
        $stmt = $this->query($query, $params);
        return $stmt->fetchColumn();
    }

    /**
     * Insert a row
     */
    public function insert(string $table, array $data): int
    {
        $columns = array_keys($data);
        $quotedColumns = array_map(fn($col) => "`{$col}`", $columns);
        $placeholders = array_fill(0, count($columns), '?');

        $query = "INSERT INTO {$table} (" . implode(',', $quotedColumns) . ") VALUES (" . implode(',', $placeholders) . ")";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute(array_values($data));

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Update rows
     */
    public function update(string $table, array $data, array $where): int
    {
        $set = [];
        $values = [];

        foreach ($data as $column => $value) {
            $set[] = "`{$column}` = ?";
            $values[] = $value;
        }

        $whereClause = [];
        foreach ($where as $column => $value) {
            $whereClause[] = "`{$column}` = ?";
            $values[] = $value;
        }

        $query = "UPDATE {$table} SET " . implode(', ', $set) . " WHERE " . implode(' AND ', $whereClause);

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($values);

        return $stmt->rowCount();
    }

    /**
     * Delete rows
     */
    public function delete(string $table, array $where): int
    {
        $whereClause = [];
        $values = [];

        foreach ($where as $column => $value) {
            $whereClause[] = "`{$column}` = ?";
            $values[] = $value;
        }

        $query = "DELETE FROM {$table} WHERE " . implode(' AND ', $whereClause);

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($values);

        return $stmt->rowCount();
    }

    /**
     * Begin a transaction
     */
    public function beginTransaction(): bool
    {
        return $this->pdo->beginTransaction();
    }

    /**
     * Commit a transaction
     */
    public function commit(): bool
    {
        return $this->pdo->commit();
    }

    /**
     * Rollback a transaction
     */
    public function rollback(): bool
    {
        return $this->pdo->rollBack();
    }
}
