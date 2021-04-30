<?php
require_once 'php/DBConnect.php';
$db = new DBConnect();
$db->auth();

$success = NULL;
$message = NULL;
if (isset($_POST['submit'])) {
    $Emp_ID = $_POST['Emp_ID'];
    $Name = $_POST['Name'];
    $branch = $_POST['Branch'];
    $phone = $_POST['Phone'];
    $email = $_POST['Email'];
    $salary = $_POST['Salary'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $flag = $db->addEmployee($Emp_ID,$Name,$branch,$phone,$email,$salary,$username,$password);

    if ($flag) {
        $success = "Employee has been added successfully!";
    } else {
        $message = "Error adding the employee to the database!";
    }
}
$title = "Admin Home";
$setHomeActive = "active";
include_once 'layout/_header.php';
include_once 'layout/navbar.php';
?>
<div class="container my-3">
    <div class="row justify-content-center mb-2">
        <div class="col-sm-6">
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
                    <h3>Add Employee</h3>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" role="form" method="post" action="home.php">
                        <div class="form-group">
                            <label class="col-sm-4">Employee ID</label>
                            <div class="col-sm-9"><input type="text" name="Emp_ID" class="form-control" required="true"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3">Name</label>
                            <div class="col-sm-9"><input type="text" name="Name" class="form-control" required="true"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3">Branch</label>
                            <div class="col-sm-9"><input type="text" name="Branch" class="form-control" required="true"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-5">Phone<span class="offset-sm-1 font-weight-light"><sub>Should be 10 digit long</sub></span></label>
                            <div class="col-sm-9"><input type="text" name="Phone" class="form-control" maxlength="10" required="true" pattern="[0-9]{10}"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3">Email</label>
                            <div class="col-sm-9"><input type="email" name="Email" class="form-control" required="true"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3">Salary</label>
                            <div class="col-sm-9"><input type="number" name="Salary" class="form-control" required="true"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4">Username:</label>
                            <div class="col-sm-9"><input type="text" name="username" class="form-control" required="true"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3">Password:</label>
                            <div class="col-sm-9"><input type="password" name="password" class="form-control" required="true"></div>
                        </div>
                        <div class="form-group row justify-content-center">
                            <button type="submit" class="btn btn-success btn-md" name="submit">Add Employee</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>

<?php include 'layout/_footer.php';?>