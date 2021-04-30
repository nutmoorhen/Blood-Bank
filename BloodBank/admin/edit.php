<?php
require_once 'php/DBConnect.php';
$db = new DBConnect();
$db->auth();

$success = NULL;
$message = NULL;

if(isset($_GET['id'])){
    $Emp_ID = $_GET['id']; // To get employee ID to update
    $emp = $db->getEmployeeById($Emp_ID);
    $empLog = $db->getEmployeeLoginById($Emp_ID);
}
if (isset($_POST['Submit'])) {
    $Emp_ID = $_POST['Emp_ID'];
    $Name = $_POST['Name'];
    $branch = $_POST['Branch'];
    $phone = $_POST['Phone'];
    $email = $_POST['Email'];
    $salary = $_POST['Salary'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $flag = $db->updateEmployee($Emp_ID, $Name, $branch, $phone, $email, $salary, $username, $password);
    $emp = $db->getEmployeeById($Emp_ID);
    $empLog = $db->getEmployeeLoginById($Emp_ID);

    if ($flag) {
        $success = "Employee data has been updated successfully!";
    } else {
        $message = "Error while updating the employee to the database.";
    }
}

$title = "Update Employee";
$setEmployeeActive = "active";
$setEmpUpdateActive = "active";
include 'layout/_header.php';
include 'layout/navbar.php';
?>

<div class="container my-3">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <?php if (isset($success)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo $success; ?><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
            <?php endif ?>
            <?php if (isset($message)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $message; ?><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
            <?php endif ?>

            <div class="card">
                <div class="card-header">
                    <h3>Update Employee</h3>
                </div>
                <div class="card-body">
                    <?php if(isset($Emp_ID)): ?>
                    <form class="form-horizontal" role="form" method="post" action="edit.php?">
                        <input type="hidden" name="Emp_ID" value="<?php echo $Emp_ID; ?>">
                        <div class="form-group">
                            <label class="col-sm-3">Name</label>
                            <div class="col-sm-9"><input type="text" name="Name" value="<?php echo $emp['Name']; ?>" class="form-control" required="true"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3">Branch</label>
                            <div class="col-sm-9"><input type="text" name="Branch" value="<?php echo $emp['Branch']; ?>" class="form-control" required="true"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-5">Phone<span class="offset-sm-1 font-weight-light"><sub>Should be 10 digit long</sub></span></label>
                            <div class="col-sm-9"><input type="text" name="Phone" value="<?php echo $emp['Phone']; ?>" class="form-control" maxlength="10" required="true" pattern="[0-9]{10}"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3">Email</label>
                            <div class="col-sm-9"><input type="email" name="Email" value="<?php echo $emp['Email']; ?>" class="form-control" required="true"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3">Salary</label>
                            <div class="col-sm-9"><input type="number" name="Salary" value="<?php echo $emp['Salary']; ?>" class="form-control" required="true"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4">Username</label>
                            <div class="col-sm-9"><input type="text" name="username" value="<?php echo $empLog['Username']; ?>" class="form-control" required="true"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3">Password</label>
                            <div class="col-sm-9"><input type="password" name="password" value="<?php echo $empLog['Password']; ?>"  class="form-control" required="true"></div>
                        </div>
                        <div class="form-group row justify-content-center">
                            <button type="submit" class="btn btn-success btn-md" value="true" name="Submit">Update Info</button>
                        </div>
                    </form>
                    <?php else: ?>
                    <p class="p-3 font-weight-bold text-center">Invalid Update Access</p>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'layout/_footer.php'; ?>
