<?php 

class DBConnect{
    private $db = NULL;
    
    const DB_SERVER = "localhost";
    const DB_USER = "root";
    const DB_PASSWORD = "";
    const DB_NAME = "bloodbank";

    public function __construct() {
        $dsn = 'mysql:dbname=' . self::DB_NAME . ';host=' . self::DB_SERVER;
        try {
            $this->db = new PDO($dsn, self::DB_USER, self::DB_PASSWORD);    
        } catch (PDOException $e) {
            throw new Exception('Connection failed: ' . $e->getMessage());
        }
        return $this->db;
    }
    
    public function showBranches(){
        $stmt = $this->db->prepare("SELECT * FROM Branch");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function countBranches(){
        $stmt = $this->db->prepare("SELECT COUNT(*) AS Count FROM Branch ORDER BY Name");
        $stmt->execute();
        return $stmt->fetch();
    }
    
    public function searchBranchByName($branchName){
        $stmt = $this->db->prepare("SELECT * FROM Branch WHERE Name LIKE ? ORDER BY Name");
        $stmt->execute(["%".$branchName."%"]);
        return $stmt->fetchAll();
    }
}