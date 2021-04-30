<?php
require_once 'php/DBConnect.php';
$db = new DBConnect();
$db->auth();

if(isset($_GET['id'])){
    $Emp_ID = $_GET['id'];
    $emp = $db->getEmployeeById($Emp_ID);
}
if (isset($_POST['Delete'])) {
    if ($_POST['Delete'] == "Y"){
        $Emp_ID= $_POST['Emp_ID'];
        $flag = $db->removeEmployee($Emp_ID);
        
        if ($flag) {
            header("Location: http://localhost:".$_SERVER['SERVER_PORT']."/www/Bloodbank/admin/employee.php");
        }
    }
    elseif ($_POST['Delete'] == "N"){
        header("Location: http://localhost:".$_SERVER['SERVER_PORT']."/www/Bloodbank/admin/employee.php");
    }
}

$title = "Remove Employee";
$setEmployeeActive = "active";
$setEmpDeleteActive = "active";
include 'layout/_header.php';
include 'layout/navbar.php';
?>

<div class="container my-3">
    <div class="row justify-content-center">
        <div class="col-sm-9">
            <div class="card">
                <?php if(isset($Emp_ID)): ?>
                <div class="card-header">
                    <h5>Do you wish to delete the following employee record?</h5>
                </div>
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="form-group">
                            <form method="post" action="delete.php">
                                <input hidden type="text" value="<?php echo $emp['Emp_ID']; ?>" name="Emp_ID">
                                <div class="table-responsive">
                                    <table class="table table-condensed">
                                        <thead>
                                            <th>Employee ID</th>
                                            <th>Name</th>
                                            <th>Branch</th>
                                            <th>Phone</th>
                                            <th>Email</th>
                                        </thead>
                                        <tbody>
                                            <tr class="tr">
                                                <td><?php echo $emp['Emp_ID']; ?></td>
                                                <td><?php echo $emp['Name']; ?></td>                            
                                                <td><?php echo $emp['Branch']; ?></td>
                                                <td><?php echo $emp['Phone']; ?></td>
                                                <td><?php echo $emp['Email']; ?></td>  
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row justify-content-center">
                                    <button class="btn btn-danger btn-md" value="Y" type="submit" name="Delete">Yes</button>
                                    <button class="btn btn-success btn-md offset-md-1" value="N" type="submit" name="Delete">No</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="card-body">
                    <p class="p-3 font-weight-bold text-center">Invalid Delete Access</p>
                </div>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>

<?php include 'layout/_footer.php'; ?>
