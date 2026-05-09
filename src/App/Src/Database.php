<?php

declare(strict_types=1);

namespace App\Src;

use PDO;
use PDOException;

/**
 * Database class for MySQL connection and bingo card history operations.
 */
class Database
{
    private PDO $pdo;

    public function __construct()
    {
        $host = getenv('DB_HOST') ?: 'localhost';
        $db   = getenv('DB_NAME') ?: 'bingo_gln';
        $user = getenv('DB_USER') ?: 'root';
        $pass = getenv('DB_PASS') ?: '';
        $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
        try {
            $this->pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            die('Database connection failed: ' . $e->getMessage());
        }
    }

    /**
     * Save a bingo card to the database.
     */
    public function saveCard(?int $userId, string $title, array $numbers, string $uniqueCode, ?string $bgPath = null, int $pages = 1): bool
    {
        $sql = "INSERT INTO cards (code, numbers, bg_path, title, pages, user_id) VALUES (:code, :numbers, :bg_path, :title, :pages, :user_id)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':code' => $uniqueCode,
            ':numbers' => json_encode($numbers),
            ':bg_path' => $bgPath,
            ':title' => $title,
            ':pages' => $pages,
            ':user_id' => $userId,
        ]);
    }

    public function getCardByCode(string $code): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM cards WHERE code = :code");
        $stmt->execute([':code' => $code]);
        $result = $stmt->fetch();
        return $result ?: null;
    }
}