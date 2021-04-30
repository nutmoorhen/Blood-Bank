<?php
class DBConnect {
    private $db = NULL;
    
    const DB_SERVER = "localhost";
    const DB_USER = "root";
    const DB_PASSWORD = "";
    const DB_NAME = "bloodbank";

    public function __construct() {
        $dsn = 'mysql:dbname=' . self::DB_NAME . ';host=' . self::DB_SERVER;
        $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
        try {
            $this->db = new PDO($dsn, self::DB_USER, self::DB_PASSWORD,$options);
        } catch (PDOException $e) {
            throw new Exception('Connection failed: ' . $e->getMessage()."<br>".(int)$e->getCode());
        }
        return $this->db;
    }
    
    public function auth(){
        if(!isset($_SESSION)){
            session_start(); 
        }
        if(!isset($_SESSION['Username'])){
            exit(header("Location: http://localhost:".$_SERVER['SERVER_PORT']."/www/BloodBank/emp"));
        }
    }
    
    // Used at www/bloodbank/emp/index.php
    public function authLogin(){
        if(!isset($_SESSION)){
            session_start(); 
        }
        if(isset($_SESSION['Username'])){
            exit(header("Location: http://localhost:".$_SERVER['SERVER_PORT']."/www/BloodBank/emp/home.php"));
        }
    }
    
    public function checkAuth(){
        if(!isset($_SESSION)){
            session_start(); 
        }
        if(! isset($_SESSION['Username'])){
            return false;
        } else {
            return true;
        }
    }
    
    public function login($username, $password){
        $stmt = $this->db->prepare("SELECT * FROM employeeLogin EL, employee E WHERE Username=? AND Password=? AND EL.Emp_ID = E.Emp_ID");
        $stmt->execute([$username,$password]);
        $emp = $stmt->fetchAll();
        
        # If true, then the given credentials has found a match
        if (count($emp) > 0){
            session_start();
            foreach($emp as $e){
                $_SESSION['Username'] = $username;
                $_SESSION['Emp_ID'] = $e['Emp_ID'];
                $_SESSION['Name'] = $e['Name'];
                $_SESSION['Branch'] = $e['Branch'];
                $_SESSION['Phone'] = $e['Phone'];
                $_SESSION['Email'] = $e['Email'];
            }
            return true;
        }
        else{
            return false;   
        }
    }
    
    public function logout(){
        session_start();
        session_destroy();
        header("Location: http://localhost:".$_SERVER['SERVER_PORT']."/www/BloodBank");
    }
    
    // sort - 'asc' or 'desc'
    public function viewBloodStockByBranch($branchID, $sort=null, $bloodType=null){
        $query = "SELECT BloodGroup, Amount FROM Blood WHERE Br_ID = ?";
        $args = array($branchID);
        
        if($bloodType != null){
            $query .= " AND BloodGroup = ?";
            array_push($args, $bloodType);
        }
        
        if($sort != null){
            $query .= ($sort == "DESC" ? " ORDER BY Amount DESC" : " ORDER BY Amount");
        }
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($args);
        return $stmt->fetchAll();
    }
    
    public function totalBloodStockByBranch($branchID){
        $stmt = $this->db->prepare("SELECT SUM(Amount) AS TotalUnits FROM Blood WHERE Br_ID = ?");
        $stmt->execute([$branchID]);
        return $stmt->fetch();
    }
    
    public function addDonor($ID,$name,$bloodType,$age,$gender,$phone,$email,$weight){
        try{
            $this->db->beginTransaction();
            $stmt = $this->db->prepare("INSERT INTO donor(D_ID,Name,Bloodtype,Age,Gender,Phone,Email,Weight)"
                                       ."VALUES(?,?,?,?,?,?,?,?)");
            $stmt->execute([$ID,$name,$bloodType,$age,$gender,$phone,$email,$weight]);
            $this->db->commit();
            if($stmt->errorCode() == '00000')
                return true;
            else
                return false;
        }
        catch(Exception $e){
            $this->db->rollback();
            return false;
        }
    }
    
    public function deleteDonor($branchID,$ID=null){
        // No field to identify the donor to remove
        if ($ID == null){
            return false;
        }
        try{
            $this->db->beginTransaction();
            $query = "DELETE FROM donor D WHERE D_ID = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$ID]);
            $this->db->commit();
            if ($stmt->errorCode() == '00000')
                return true;         // Delete successful
            else
                return false;  // Delete failure
        }
        catch(Exception $e){
            $this->db->rollback();
            return false;
        }
    }
    
    public function updateDonor($ID,$name=null,$age=null,$gender=null,$phone=null,$email=null,$weight=null){
        $query = "UPDATE donor SET ";
        
        $arg_list = "";
        $newVals = array();
        $mask = 0;
        if ($name != null){
            $arg_list .= "Name = ?";
            array_push($newVals, $name);
            $mask ^= 1;
        }
        if ($bloodType != null){
            if ($mask != 0)
                $arg_list .= ",";
            $arg_list .= "Bloodtype = ?";
            array_push($newVals, $bloodType);
            $mask ^= 2;
        }
        if ($age != null){
            if ($mask != 0)
                $arg_list .= ",";
            $arg_list .= "Age = ?";
            array_push($newVals, $age);
            $mask ^= 4;
        }
        if ($gender != null){
            if ($mask != 0)
                $arg_list .= ",";
            $arg_list .= "Gender = ?";
            array_push($newVals, $gender);
            $mask ^= 8;
        }
        if ($phone != null){
            if ($mask != 0)
                $arg_list .= ",";
            $arg_list .= "Phone = ?";
            array_push($newVals, $phone);
            $mask ^= 16;
        }
        if ($email != null){
            if ($mask != 0)
                $arg_list .= ",";
            $arg_list .= "Email = ?";
            array_push($newVals, $email);
            $mask ^= 32;
        }
        if ($weight != null){
            if ($mask != 0)
                $arg_list .= ",";
            $arg_list .= "Weight = ?";
            array_push($newVals, $weight);
            $mask ^= 64;
        }
        
        // No field to update as new values not received
        if ($mask == 0)
            return false;
        else{
            $query .= ($arg_list . " WHERE D_ID=?");
            array_push($newVals, $ID);
        }
        
        try{
            $this->db->beginTransaction();
            $stmt = $this->db->prepare($query);
            $stmt->execute($newVals);
            if ($stmt->errorCode() == '00000'){
                return true;    // Update successful
            }
            else{
                return false;   // Update failure
            }
        }
        catch(Exception $e){
            $this->db->rollback();
            return false;
        }
    }
    
    public function getDonorsByBranch($branchID,$ID=null,$name=null,$bloodType=null,$age=null,$gender=null,$phone=null,$email=null,$weight=null){
        $query = "SELECT D.* FROM `donor` D, `donated_At` DA WHERE ";
        
        $arg_list = "";
        $newVals = array();
        $mask = 0;
        if ($ID != null){
            $arg_list .= "`D`.`D_ID` = ?";
            array_push($newVals, $ID);
            $mask ^= 1;
        }
        if ($name != null){
            if ($mask != 0)
                $arg_list .= " AND ";
            $arg_list .= "`Name` LIKE ?";
            array_push($newVals, $name."%");
            $mask ^= 2;
        }
        if ($bloodType != null){
            if ($mask != 0)
                $arg_list .= " AND ";
            $arg_list .= "`Bloodtype` = ?";
            array_push($newVals, $bloodType);
            $mask ^= 4;
        }
        if ($age != null){
            if ($mask != 0)
                $arg_list .= " AND ";
            $arg_list .= "`Age` = ?";
            array_push($newVals, $age);
            $mask ^= 8;
        }
        if ($gender != null){
            if ($mask != 0)
                $arg_list .= " AND ";
            $arg_list .= "`Gender` = ?";
            array_push($newVals, $gender);
            $mask ^= 16;
        }
        if ($phone != null){
            if ($mask != 0)
                $arg_list .= " AND ";
            $arg_list .= "`Phone` = ?";
            array_push($newVals, $phone);
            $mask ^= 32;
        }
        if ($email != null){
            if ($mask != 0)
                $arg_list .= " AND ";
            $arg_list .= "`Email` = ?";
            array_push($newVals, $email);
            $mask ^= 64;
        }
        if ($weight != null){
            if ($mask != 0)
                $arg_list .= ",";
            $arg_list .= "`Weight` = ?";
            array_push($newVals, $weight);
            $mask ^= 128;
        }
        
        // No particular field to select. So selecting all
        if ($mask == 0)
            $query .= ($arg_list . " DA.D_ID = D.D_ID AND `B_ID` = ?");
        else{
        $query .= ($arg_list . " AND DA.D_ID = D.D_ID AND `B_ID` = ?");
        }
        array_push($newVals, $branchID);
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($newVals);
        return $stmt->fetchAll();
    }
    
    public function getDonors($ID=null,$name=null,$bloodType=null,$age=null,$gender=null,$phone=null,$email=null,$weight=null){
         $query = "SELECT * FROM donor";
        
        $arg_list = "";
        $newVals = array();
        $mask = 0;
        if ($ID != null){
            $arg_list .= "ID = ?";
            array_push($newVals, $ID);
            $mask ^= 1;
        }
        if ($name != null){
            if ($mask != 0)
                $arg_list .= " AND ";
            $arg_list .= "Name = ?";
            array_push($newVals, $name);
            $mask ^= 2;
        }
        if ($bloodType != null){
            if ($mask != 0)
                $arg_list .= " AND ";
            $arg_list .= "Bloodtype = ?";
            array_push($newVals, $bloodType);
            $mask ^= 4;
        }
        if ($age != null){
            if ($mask != 0)
                $arg_list .= " AND ";
            $arg_list .= "Age = ?";
            array_push($newVals, $age);
            $mask ^= 8;
        }
        if ($gender != null){
            if ($mask != 0)
                $arg_list .= " AND ";
            $arg_list .= "Gender = ?";
            array_push($newVals, $gender);
            $mask ^= 16;
        }
        if ($phone != null){
            if ($mask != 0)
                $arg_list .= " AND ";
            $arg_list .= "Phone = ?";
            array_push($newVals, $phone);
            $mask ^= 32;
        }
        if ($email != null){
            if ($mask != 0)
                $arg_list .= " AND ";
            $arg_list .= "Email = ?";
            array_push($newVals, $email);
            $mask ^= 64;
        }
        if ($weight != null){
            if ($mask != 0)
                $arg_list .= ",";
            $arg_list .= "Weight = ?";
            array_push($newVals, $weight);
            $mask ^= 128;
        }
        
        // No particular field to select. So selecting all
        if ($mask != 0){
            $query .= (" WHERE ". $arg_list);
        }
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($newVals);
        return $stmt->fetchAll();
    }
    
    public function getRegisteredDonorsInBranch($branchID){
        $stmt = $this->db->prepare("CALL findRegisteredDonorsInBranch(?)");
        $stmt->execute([$branchID]);
        return $stmt->fetchAll();
    }
    
    public function getDonatedDonorsInBranch($branchID){
        $stmt = $this->db->prepare("CALL findDonatedDonorsInBranch(?)");
        $stmt->execute([$branchID]);
        return $stmt->fetchAll();
    }
    
    public function addDonation($branchID, $ID, $donationDate, $volume){
        try{
            $this->db->beginTransaction();
            $stmt = $this->db->prepare("INSERT INTO donated_At(B_ID,D_ID,DonatedOn,Volume) VALUES (:Branch,:Donor,:DonateDate,:Vol)");
            $stmt->execute(['Branch'=>$branchID, 'Donor'=>$ID, 'DonateDate'=>$donationDate, 'Vol'=>$volume]);
            $this->db->commit();
            
            if ($stmt->errorCode() == '00000')
                return true;    // Insert or update (if entry exists) successful
            else
                return false;   // Insert or update failure
        }
        catch(Exception $e){
            $this->db->rollback();
            return false;
        }
    }
    
    public function updateDonation($branchID, $ID, $donationDate, $volume){
        try{
            $this->db->beginTransaction();
            $stmt = $this->db->prepare("UPDATE donated_at SET DonatedOn = ?, Volume = ? WHERE B_ID = ? AND D_ID = ?");
            $stmt->bindParam(1, $donationDate, PDO::PARAM_STR);
            $stmt->bindParam(2, $volume, PDO::PARAM_INT);
            $stmt->bindParam(3, $branchID, PDO::PARAM_INT);
            $stmt->bindParam(4, $ID, PDO::PARAM_STR);
            $stmt->execute();
            $this->db->commit();
            
            if($stmt->rowCount() == 0){
                return true;
            }
            else{
                return false;
            }
        }
        catch(Exception $e){
            $this->db->rollback();
            return false;
        }
    }
    
    public function removeDonation($branchID, $ID){
        try{
            $this->db->beginTransaction();
            $stmt = $this->db->prepare("DELETE FROM donated_At WHERE B_ID = ? AND D_ID = ?");
            $stmt->execute([$branchID, $ID]);
            $this->db->commit();
            
            if ($stmt->errorCode() == '00000')
                return true;    // Delete successful
            else
                return false;   // Delete failure
        }
        catch(Exception $e){
            $this->db->rollback();
            return false;
        }
    }
    
    public function nRecentDonations($branchID, $limit){
        // Obtain 5 most recent donations for a particular branch
        $stmt = $this->db->prepare("SELECT D_ID, Name, Bloodtype, DonatedOn, Volume FROM Donation_Record WHERE B_ID = ? ORDER BY DonatedOn DESC LIMIT ?");
        $stmt->bindParam(1, $branchID, PDO::PARAM_INT);
        $stmt->bindParam(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function findDonationsByID($ID){
        $stmt = $this->db->prepare("SELECT * FROM donated_At WHERE D_ID = $ID");
        $stmt->execute([$ID]);
        
        if($stmt->errorCode() == '00000'){
            return $stmt->fetchAll();
        }
        else{
            return false;
        }
    }
    
    public function viewDonationsByBranch($branchID){
        $stmt = $this->db->prepare("SELECT * FROM donation_record WHERE B_ID = ? ORDER BY DonatedOn DESC");
        $stmt->execute([$branchID]);
        return $stmt->fetchAll();
    }
    
    // donationInfo -> {"Date" => [2 volume amts for "BETWEEN" clause or 1st ele is used], "<=" => either true or false, ">=" => either true or false}
    // volumeInfo -> {"Vol" => [2 volume amts for "BETWEEN" clause or 1st ele is used], "<=" => either true or false, ">=" => either true or false}
    public function viewDonationsByOptions($branchID,$id=null,$name=null,$bloodType=null,$donationInfo=null,$volumeInfo=null,$sortDate=null){
        $query = "SELECT `D_ID`, `Name`, `Bloodtype`, `DonatedOn`, `Volume` FROM `Donation_Record` WHERE ";
        
        $arg_list = "";
        $newVals = array();
        $mask = 0;
        if($id != null){
            $arg_list .= "`D_ID` = ?";
            array_push($newVals, $id);
            $mask ^= 1;
        }
        if($name != null){
            if ($mask != 0)
                $arg_list .= " AND ";
            if (strpos($name, " ") != false){
                $arg_list .= "`Name` = ?";
                array_push($newVals, $name);
            }
            else{
                $arg_list .= "`Name LIKE ?";
                array_push($newVals, $name."%");
            }
            $mask ^= 2;
        }
        if($bloodType != null){
            if ($mask != 0)
                $arg_list .= " AND ";
            $arg_list .= "`Bloodtype` = ?";
            array_push($newVals, $bloodType);
            $mask ^= 4;
        }
        if($donationInfo != null){
            if ($mask != 0)
                $arg_list .= " AND ";
            
            if (count($donationInfo) > 0){
                $d1 = $donationInfo['Date'][0];
                $d2 = $donationInfo['Date'][1];
                
                array_push($newVals, $d1);
                
                if ($donationInfo['<='] && $donationInfo['>=']){
                    $arg_list .= "`DonatedOn` BETWEEN ? AND ?";
                    array_push($newVals, $d2);
                }
                else if ($donationInfo['<=']){
                    $arg_list .= "`DonatedOn` <= ?";
                }
                else if ($donationInfo['>=']){
                    $arg_list .= "`DonatedOn` >= ?";
                }
                else{
                    $arg_list .= "`DonatedOn` = ?";
                }
            }
            $mask ^= 8;
        }
        if($volumeInfo != null){
            if ($mask != 0)
                $arg_list .= " AND ";
            
            if (count($volumeInfo) > 0){
                $v1 = $volumeInfo['Vol'][0];
                array_push($newVals, $v1);
                
                if ($volumeInfo['<='] && $volumeInfo['>=']){
                    $arg_list .= "`Volume` BETWEEN ? AND ?";
                    $v2 = $volumeInfo['Vol'][1];
                    array_push($newVals, $v2);
                }
                else if ($volumeInfo['<=']){
                    $arg_list .= "`Volume` <= ?";
                }
                else if ($volumeInfo['>=']){
                    $arg_list .= "`Volume` >= ?";
                }
                else{
                    $arg_list .= "`Volume` = ?";
                }
            }
            $mask ^= 16;
        }
        
        // To show records of the branch that queries
        $query .= ($arg_list . ($mask == 0 ? " `B_ID` = ?" : " AND `B_ID` = ?"));
        array_push($newVals, $branchID);
        
        if ($sortDate != null){
            $query .= ($sortDate == "ASC" ? " ORDER BY `DonatedOn`" : " ORDER BY `DonatedOn` DESC");
        }
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($newVals);
        return $stmt->fetchAll();
    }
    
    public function viewEmployeesByBranch($branchID){
        $stmt = $this->db->prepare("SELECT * FROM employee WHERE Branch = ?");
        $stmt->execute([$branchID]);
        return $stmt->fetchAll();
    }
    
    public function viewSpecificBranchEmployees($branchID, $empID=null, $name=null, $phone=null, $email=null, $salary=null){
        $query = "SELECT * FROM employee WHERE ";
        
        $arg_list = "";
        $newVals = array();
        $mask = 0;
        if ($empID != null){
            $arg_list .= "Emp_ID = ?";
            array_push($newVals, $empID);
            $mask ^= 1;
        }
        if ($name != null){
            if ($mask != 0)
                $arg_list .= " AND ";
            $arg_list .= "Name LIKE ?";
            array_push($newVals, $name."%");
            $mask ^= 2;
        }
        if ($phone != null){
            if ($mask != 0)
                $arg_list .= " AND ";
            $arg_list .= "Phone = ?";
            array_push($newVals, $phone);
            $mask ^= 4;
        }
        if ($email != null){
            if ($mask != 0)
                $arg_list .= " AND ";
            $arg_list .= "Email = ?";
            array_push($newVals, $email);
            $mask ^= 8;
        }
        if($salary != null){
            if ($mask != 0)
                $arg_list .= " AND ";
            $arg_list .= "Salary = ?";
            array_push($newVals, $salary);
            $mask ^= 16;
        }
        // No field to select as new values not received
        if ($mask == 0)
            return false;
        else{
            $query .= ($arg_list . " AND Branch = ?");
            array_push($newVals, $branchID);
        }
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($newVals);
        return $stmt->fetchAll();
    }
}
?>