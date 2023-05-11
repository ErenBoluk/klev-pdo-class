<?php
class KlevPDO
{
    protected $db;

    public function __construct()
    {
        try {
            $dsn = "mysql:host=" . DatabaseConfig::$host . ";dbname=" . DatabaseConfig::$dbName . ";charset=" . DatabaseConfig::$charset;
            $this->db = new PDO($dsn, DatabaseConfig::$username, DatabaseConfig::$password, DatabaseConfig::$options);
        } catch (PDOException $e) {
            throw new Exception("Bağlantı hatası: " . $e->getMessage());
        }
    }
    private function execute($query, $params = false)
    {
        if ($params) {
            $stmt = $this->db->prepare($query);
            $stmt->execute($params);
        } else {
            $stmt = $this->db->query($query);

        }
        return $stmt;
    }

    public function select($query, $params)
    {
        var_dump($params);

        $status = false;
        $data = null;
        $rc = 0;
        try {
            $stmt = $this->execute($query, $params);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $rc = $stmt->rowCount();
            $status = true;
        } catch (PDOException $e) {
            throw new Exception("query hatası: " . $e->getMessage());
        }
        return array('status' => $status, 'data' => $data, 'rc' => $rc);
    }

    public function insert($query, $params = false)
    {
        $status = false;
        $id = 0;
        try {
            $stmt = $this->execute($query, $params);
            $id = $this->db->lastInsertId();
            $status = true;
        } catch (PDOException $e) {
            throw new Exception("Ekleme hatası: " . $e->getMessage());
        }
        return array('status' => $status, 'id' => $id);
    }

    public function update($query, $params = false)
    {
        $status = false;
        try {
            $stmt = $this->execute($query, $params);
            $status = true;
        } catch (PDOException $e) {
            throw new Exception("Güncelleme hatası: " . $e->getMessage());
        }
        return $status;
    }

    public function delete($query, $params = false)
    {
        $status = false;
        try {
            $stmt = $this->execute($query, $params);
            $status = true;
        } catch (PDOException $e) {
            throw new Exception("Silme hatası: " . $e->getMessage());
        }
        return $status;
    }

    // Required functional functions : Gerekli işlevsel fonksiyonlar
    public function getTableName($query)
    {
        if (stripos($query, 'update') === 0) {
            // Separate the table name for the UPDATE query : UPDATE querysu için tablo adını ayır
            $pattern = '/\bUPDATE\s+\`?(\w+)\`?/i';
        } else {
            // Diğer query türleri için FROM ifadesinden sonra gelen ilk kelimeyi ayır : Separate the first word after the FROM expression for other query types
            $pattern = '/\bFROM\s+\`?(\w+)\`?/i';
        }
        preg_match($pattern, $query, $matches);
        return isset($matches[1]) ? $matches[1] : null;
    }
}
/*
    logpdo database table : logpdo veritabanı tablosu

    CREATE TABLE IF NOT EXISTS pdoLog (
        logId INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        logTableName VARCHAR(50),
        logAction VARCHAR(10),
        logParams TEXT,
        logError TEXT,
        logCreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

*/