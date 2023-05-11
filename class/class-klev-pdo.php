<?php
class KlevPDO
{
    protected $db;

    public function __construct()
    {
        $logTableStracture = "
        CREATE TABLE IF NOT EXISTS pdoLog (
            logId INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            logIp VARCHAR(45),
            logTableName VARCHAR(50),
            logAction VARCHAR(10),
            logParams TEXT,
            logError TEXT,
            logCreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );";

        try {
            $dsn = "mysql:host=" . DatabaseConfig::$host . ";dbname=" . DatabaseConfig::$dbName . ";charset=" . DatabaseConfig::$charset;
            $this->db = new PDO($dsn, DatabaseConfig::$username, DatabaseConfig::$password, DatabaseConfig::$options);
            $this->tableOperation($logTableStracture, false, false);
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

    public function select($query, $params = false, $log = true)
    {
        $status = false;
        $err = null;
        $data = null;
        $rc = 0;
        try {
            $stmt = $this->execute($query, $params);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $rc = $stmt->rowCount();
            $status = true;
        } catch (PDOException $e) {
            $err = "Query Error: " . $e->getMessage();
        }
        if ($log) {
            $this->log($query, 'select', $params, $err);
        }
        return array('status' => $status, 'data' => $data, 'rc' => $rc);
    }

    public function insert($query, $params = false, $log = true)
    {
        $status = false;
        $err = null;
        $id = 0;
        try {
            $stmt = $this->execute($query, $params);
            $id = $this->db->lastInsertId();
            $status = true;
        } catch (PDOException $e) {
            $err = "Ekleme hatası: " . $e->getMessage();
        }
        if ($log) {
            $this->log($query, 'insert', $params, $err);
        }
        return array('status' => $status, 'id' => $id);
    }

    public function update($query, $params = false, $log = true)
    {
        $status = false;
        $err = null;
        try {
            $stmt = $this->execute($query, $params);
            $status = true;
        } catch (PDOException $e) {
            $err = "Güncelleme hatası: " . $e->getMessage();
        }
        if ($log) {
            $this->log($query, 'update', $params, $err);
        }
        return $status;
    }

    public function delete($query, $params = false, $log = true)
    {
        $status = false;
        $err = null;
        try {
            $stmt = $this->execute($query, $params);
            $status = true;
        } catch (PDOException $e) {
            $err = "Delete Error: " . $e->getMessage();
        }
        if ($log) {
            $this->log($query, 'delete', $params, $err);
        }
        return $status;
    }
    public function tableOperation($query, $params = false, $log = true)
    {
        $status = false;
        $err = null;
        try {
            $stmt = $this->execute($query, $params);
            $status = true;
        } catch (PDOException $e) {
            $err = "Table Operation Error: " . $e->getMessage();
        }
        if ($log) {
            $this->log($query, 'Table Operation', $params, $err);
        }
        return $status;
    }

    // Required functional functions : Gerekli işlevsel fonksiyonlar
    public function getTableName($query)
    {
        if (stripos($query, 'update') === 0) {
            // Separate the table name for the UPDATE query : UPDATE query'si için tablo adını ayır
            $pattern = '/\bUPDATE\s+\`?(\w+)\`?/i';
        } else {
            //Separate the first word after the FROM expression for other query types :  Diğer query türleri için FROM ifadesinden sonra gelen ilk kelimeyi ayır
            $pattern = '/\bFROM\s+\`?(\w+)\`?/i';
        }
        preg_match($pattern, $query, $matches);
        return isset($matches[1]) ? $matches[1] : false;
    }

    // Log function : Log fonksiyonu
    public function log($pquery, $action, $params, $error)
    {
        $query = "INSERT INTO pdoLog SET logIp=:ip,  logTableName=:tableName, logAction=:action, logParams=:params, logError=:error";
        $tableName = $this->getTableName($pquery);
        $queryDetail = array(
            'tableName' => $tableName,
            'action' => $action,
            'query' => $pquery,
            'params' => $params,
        );
        $params = array(
            'ip' => $_SERVER['REMOTE_ADDR'],
            'tableName' => $tableName,
            'action' => $action,
            'params' => json_encode($queryDetail),
            'error' => $error
        );
        return $this->insert($query, $params, false);
    }
}
