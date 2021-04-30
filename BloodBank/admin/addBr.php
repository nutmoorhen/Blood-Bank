<?php
require_once 'php/DBConnect.php';
$db = new DBConnect();
$db->auth();

$success = NULL;
$message = NULL;
if (isset($_POST['submit'])) {
    $Br_ID = $_POST['Br_ID'];
    $Name = $_POST['Name'];
    $email = $_POST['Email'];
    $phone = $_POST['Phone'];
    $address = $_POST['Address'];
    $flag = $db->addBranch($Br_ID,$Name,$email,$phone,$address);

    if ($flag) {
        $success = "Branch has been successfully added";
    } else {
        $message = "Error while adding branch";
    }
}
$title = "New Branch";
$setBranchActive = "active";
$setBrAddActive = "active";
include_once 'layout/_header.php';
include_once 'layout/navbar.php';
?>
<div class="container my-3">
    <div class="row justify-content-center mb-2">
        <div class="col-md-5">
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
                    <h3>Add Branch</h3>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" role="form" method="post" action="">
                        <div class="form-group">
                            <label class="col-sm-4">Branch ID</label>
                            <div class="col-sm-9"><input type="text" name="Br_ID" class="form-control" required="true"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3">Name</label>
                            <div class="col-sm-9"><input type="text" name="Name" class="form-control" required="true"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3">Email</label>
                            <div class="col-sm-9"><input type="email" name="Email" class="form-control" required="true"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-9">Phone<span class="offset-sm-1 font-weight-light"><sub>Should be 10 digit long</sub></span></label>
                            <div class="col-sm-9"><input type="text" name="Phone" class="form-control" maxlength="10" required="true" pattern="[0-9]{10}"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3">Address</label>
                            <div class="col-sm-9"><input type="text" name="Address" class="form-control" required="true"></div>
                        </div>
                        <div class="form-group row justify-content-center">
                            <button type="submit" class="btn btn-success btn-md" name="submit">Add Branch</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>

<?php include 'layout/_footer.php';?>