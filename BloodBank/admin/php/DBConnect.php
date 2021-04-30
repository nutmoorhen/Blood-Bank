<?php
class DBConnect {
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
            throw new Exception('Connection failed: ' .
            $e->getMessage());
        }
        return $this->db;
    }
    
    public function auth(){
        if(!isset($_SESSION)){
            session_start(); 
        }
        if(! isset($_SESSION['username'])){
            exit(header("Location: http://localhost:".$_SERVER['SERVER_PORT']."/www/Bloodbank/admin"));
        }
    }
    
    public function authLogin(){
        if(!isset($_SESSION)){
            session_start(); 
        }
        if(isset($_SESSION['username'])){
            exit(header("Location: http://localhost:".$_SERVER['SERVER_PORT']."/www/Bloodbank/admin/home.php"));
        }
    }

    public function logout(){
        if(!isset($_SESSION)){
            session_start(); 
        }
        session_destroy();
        exit(header("Location:http://localhost:".$_SERVER['SERVER_PORT']."/www/Bloodbank/admin"));
    }
    
    public function viewBranches($Br_ID=null,$branchName=null,$email=null,$phone=null,$address=null){
        $query = "SELECT * FROM Branch";
        
        $arg_list = "";
        $newVals = array();
        $mask = 0;
        if($Br_ID != null){
            $arg_list .= "Br_ID = ?";
            array_push($newVals, $Br_ID);
            $mask ^= 1;
        }
        if($branchName != null){
            if($mask != 0)
                $arg_list .= " AND ";
            $arg_list .= "Name LIKE ?";
            array_push($newVals, $branchName."%");
            $mask ^= 2;
        }
        if($email != null){
            if($mask != 0)
                $arg_list .= " AND ";
            $arg_list .= "Email = ?";
            array_push($newVals, $email);
            $mask ^= 4;
        }
        if($phone != null){
            if($mask != 0)
                $arg_list .= " AND ";
            $arg_list .= "Phone = ?";
            array_push($newVals, $phone);
            $mask ^= 8;
        }
        if($address != null){
            if($mask != 0)
                $arg_list .= " AND ";
            $arg_list .= "Address LIKE ?";
            array_push($newVals, "%".$address."%");
            $mask ^= 16;
        }
        
        if($mask != 0){
            $query .= (" WHERE " . $arg_list);
        }
        $query .= " ORDER BY Name";
        
        $stmt = $this->db->prepare($query);
        if($mask != 0)
            $stmt->execute($newVals);
        else
            $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function addBranch($br_ID,$name,$email,$phone,$address){
        try{
            $this->db->beginTransaction();
            $stmt = $this->db->prepare("INSERT INTO branch(Br_ID,Name,Email,Phone,Address) VALUES (?,?,?,?,?)");
            $stmt->execute([$br_ID,$name,$email,$phone,$address]);
            $this->db->commit();
            
            if($stmt->errorCode() === '00000')
                return true;
            else
                return false;

        }
        catch(Exception $e){
            $this->db->rollback();
            throw $e;
        }
    }

    public function updateBranch($br_ID,$name,$email,$phone,$address){
        try{
            $this->db->beginTransaction();
            $stmt = $this->db->prepare("UPDATE branch SET Name = ?, Email = ?, Phone = ?, Address = ? WHERE Br_ID = ?");
            $stmt->bindParam(1, $name, PDO::PARAM_STR);
            $stmt->bindParam(2, $email, PDO::PARAM_STR);
            $stmt->bindParam(3, $phone, PDO::PARAM_STR);
            $stmt->bindParam(4, $address, PDO::PARAM_STR);
            $stmt->bindParam(5, $br_ID, PDO::PARAM_INT);
            $stmt->execute();
            
            if($stmt->errorCode() === '00000'){
                $this->db->commit();
                return true;
            }
            else{
                return false;
            }
        }
        catch(Exception $e){
            $this->db->rollback();
            throw $e;
        }
    }

    public function removeBranch($br_ID){
        try{
            $this->db->beginTransaction();
            $stmt = $this->db->prepare("DELETE FROM branch WHERE Br_ID=?");
            $stmt->bindParam(1, $br_ID, PDO::PARAM_INT);
            $stmt->execute();
            
            if($stmt->errorCode() === '00000'){
                $this->db->commit();
                return true;
            }
            else{
                return false;
            }
        }
        catch(Exception $e){
            $this->db->rollback();
            throw $e;
        }
    }

    // volumeInfo -> {"Vol" => [2 volume amts for "BETWEEN" clause or 1st ele is used], "<=" => either true or false, ">=" => either true or false}
    public function viewBloodStockByBranch($branch=null, $bloodGroup=null, $volumeInfo=null, $sort=null){
        $query = "SELECT * FROM Blood";
        $newVals = array();
        $mask = 0;
        
        if($branch != null){
            $query .= " WHERE Br_ID = ?";
            array_push($newVals, $branch);
            $mask ^= 1;
        }
        
        if($bloodGroup != null){
            if ($mask != 0){
                $query .= " AND BloodGroup = ?";
            }
            else{
                $query .= " WHERE BloodGroup = ?";
            }
            array_push($newVals, $bloodGroup);
            $mask ^= 2;
        }
        
        if($volumeInfo != null){
            if ($mask != 0){
                $query .= " AND ";
            }else{
                $query .= " WHERE ";
            }
            
            if (count($volumeInfo) > 0){
                $v1 = $volumeInfo['Vol'][0];
                array_push($newVals, $v1);
                
                if ($volumeInfo['<='] && $volumeInfo['>=']){
                    $query .= "Amount BETWEEN ? AND ?";
                    $v2 = $volumeInfo['Vol'][1];
                    array_push($newVals, $v2);
                }
                else if ($volumeInfo['<=']){
                    $query .= "Amount <= ?";
                }
                else if ($volumeInfo['>=']){
                    $query .= "Amount >= ?";
                }
                else{
                    $query .= "Amount = ?";
                }
            }
            $mask ^= 4;
        }
        
        if($sort != null){
            $query .= ($sort == "ASC" ? " ORDER BY Amount" : " ORDER BY Amount DESC");
        }
        else{
            $query .= " ORDER BY Br_ID, BloodGroup";
        }
        
        $stmt = $this->db->prepare($query);
        if ($mask != 0)
            $stmt->execute($newVals);
        else
            $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function addEmployee($emp_ID,$name,$branch,$phone,$email,$salary,$username,$password){
        try{
            $this->db->beginTransaction();
            $stmt = $this->db->prepare("INSERT INTO employee(Emp_ID,Name,Branch,Phone,Email,Salary) VALUES (?,?,?,?,?,?)");
            $stmt->execute([$emp_ID,$name,$branch,$phone,$email,$salary]);
            
            if($stmt->errorCode() === '00000')
                $this->db->commit();
            else
                return false;
        }
        catch(Exception $e){
            $this->db->rollback();
            throw $e;
        }
        
        try{
            $this->db->beginTransaction();
            $stmt = $this->db->prepare("INSERT INTO employeeLogin(Emp_ID,Username,Password) VALUES (?,?,?)");
            $stmt->execute([$emp_ID,$username,$password]);
            $this->db->commit();
            
            if($stmt->errorCode() == '00000')
                return true;
            else
                return false;
        }
        catch(Exception $e){
            $this->db->rollback();
            throw $e;
        }
    }
    
    public function getEmployees(){
        $stmt = $this->db->prepare("SELECT * FROM employee");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getEmployeeById($emp_ID){
        $stmt = $this->db->prepare("SELECT * FROM employee WHERE Emp_ID=?");
        $stmt->execute([$emp_ID]);
        return $stmt->fetch();
    }

    public function getEmployeeLoginById($Emp_ID){
        $stmt = $this->db->prepare("SELECT * FROM employeeLogin WHERE Emp_ID = ?");
        $stmt->execute([$Emp_ID]);
        return $stmt->fetch();
    }
    
    public function updateEmployee($emp_ID,$name,$branch,$phone,$email,$salary,$username,$password){
        try{
            $this->db->beginTransaction();
            $stmt = $this->db->prepare("UPDATE employee SET Name = ?, Branch = ?, Phone = ?, Email = ?, Salary = ? WHERE Emp_ID = ?");
            $stmt->bindParam(1, $name, PDO::PARAM_STR);
            $stmt->bindParam(2, $branch, PDO::PARAM_INT);
            $stmt->bindParam(3, $phone, PDO::PARAM_STR);
            $stmt->bindParam(4, $email, PDO::PARAM_STR);
            $stmt->bindParam(5, $salary, PDO::PARAM_INT);
            $stmt->bindParam(6, $emp_ID, PDO::PARAM_STR);
            $stmt->execute();
            
            if($stmt->errorCode() === '00000'){
                $this->db->commit();
            }else{
                return false;
            }
        }
        catch(Exception $e){
            $this->db->rollback();
            throw $e;
        }
        
        try{
            $this->db->beginTransaction();
            $stmt = $this->db->prepare("INSERT INTO employeeLogin(Emp_ID,Username,Password) VALUES (:empID,:uname,:pw) ON DUPLICATE KEY UPDATE Username = :uname, Password = :pw");
            //$stmt = $this->db->prepare("UPDATE employeeLogin SET Username = ?, Password = ? WHERE Emp_ID = ?");
            $stmt->bindParam(":empID", $emp_ID, PDO::PARAM_STR);
            $stmt->bindParam(":uname", $username, PDO::PARAM_STR);
            $stmt->bindParam(":pw", $password, PDO::PARAM_STR);
            $stmt->execute();
            $this->db->commit();
            
            if($stmt->errorCode() === '00000'){
                return true;
            }else{
                return false;
            }
        }
        catch(Exception $e){
            $this->db->rollback();
            throw $e;
        }
    }
    
    public function removeEmployee($Emp_ID){
        try{
            $this->db->beginTransaction();
            $stmt = $this->db->prepare("DELETE FROM employee WHERE Emp_ID=?");
            $stmt->execute([$Emp_ID]);
            
            if($stmt->errorCode() === '00000'){
                return true;
            }else{
                return false;
            }
        }
        catch(Exception $e){
            $this->db->rollback();
            throw $e;
        }
    }

    public function getDonors(){
        $stmt = $this->db->prepare("SELECT * FROM donor");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getRegisteredDonors(){
        $stmt = $this->db->prepare("CALL findRegisteredDonors()");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getDonatedDonors(){
        $stmt = $this->db->prepare("CALL findDonatedDonors()");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
