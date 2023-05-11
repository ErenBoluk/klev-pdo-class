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

    public function select($sorgu, $parametre = array())
    {
        $status = false;
        $data = null;
        $rc = 0;
        try {
            $stmt = $this->db->prepare($sorgu);
            $stmt->execute($parametre);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $rc = $stmt->rowCount();
            $status = true;
        } catch (PDOException $e) {
            throw new Exception("Sorgu hatası: " . $e->getMessage());
        }
        return array('status' => $status, 'data' => $data, 'rc' => $rc);
    }

    public function insert($sorgu, $parametre = array())
    {
        $status = false;
        $id = 0;
        try {
            $stmt = $this->db->prepare($sorgu);
            $stmt->execute($parametre);
            $id = $this->db->lastInsertId();
            $status = true;
        } catch (PDOException $e) {
            throw new Exception("Ekleme hatası: " . $e->getMessage());
        }
        return array('status' => $status, 'id' => $id);
    }

    public function update($sorgu, $parametre = array())
    {
        $status = false;
        try {
            $stmt = $this->db->prepare($sorgu);
            $stmt->execute($parametre);
            $status = true;
        } catch (PDOException $e) {
            throw new Exception("Güncelleme hatası: " . $e->getMessage());
        }
        return $status;
    }
}
