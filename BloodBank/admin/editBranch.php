<?php
require_once 'php/DBConnect.php';
$db = new DBConnect();
$db->auth();

$success = NULL;
$message = NULL;
if(isset($_GET['id'])){
    $Br_ID = $_GET['id']; // To get branch ID to update
    $br = $db->viewBranches($Br_ID);
}
if(isset($_POST['Submit'])) {
    $Br_ID = $_POST['Br_ID'];
    $Name = $_POST['Name'];
    $email = $_POST['Email'];
    $phone = $_POST['Phone'];
    $address = $_POST['Address'];

    $flag = $db->updateBranch($Br_ID,$Name,$email,$phone,$address);
    $br = $db->viewBranches($Br_ID);

    if ($flag) {
        $success = "Branch has been successfully updated";
    } else {
        $message = "Error while updating the branch";
    }
}

$title = "Update Branch";
$setBranchActive = "active";
$setBrUpdateActive = "active";
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
                <?php if(isset($Br_ID)): ?>
                <div class="card-header">
                    <h3>Update Branch</h3>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" role="form" method="post" action="editBranch.php?">
                        <input type="hidden" name="Br_ID" value="<?php echo $Br_ID; ?>">
                        <div class="form-group">
                            <label class="col-sm-3">Name</label>
                            <div class="col-sm-9"><input type="text" name="Name" value="<?php echo $br[0]['Name']; ?>" class="form-control" required="true"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3">Email</label>
                            <div class="col-sm-9"><input type="email" name="Email" value="<?php echo $br[0]['Email']; ?>" class="form-control" required="true"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-9">Phone<span class="offset-sm-1 font-weight-light"><sub>Should be 10 digit long</sub></span></label>
                            <div class="col-sm-9"><input type="text" name="Phone" value="<?php echo $br[0]['Phone']; ?>" class="form-control" maxlength="10" required="true" pattern="[0-9]{10}"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3">Address</label>
                            <div class="col-sm-9"><input type="textarea" name="Address" value="<?php echo $br[0]['Address']; ?>" class="form-control" required="true"></div>
                        </div>
                        <div class="form-group row justify-content-center">
                            <button type="submit" class="btn btn-success btn-md" value="true" name="Submit">Update Branch</button>
                        </div>
                    </form>
                </div>
                <?php else: ?>
                <div class="card-body">
                    <p class="p-3 font-weight-bold text-center">Invalid Update Access</p>
                </div>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>

<?php include 'layout/_footer.php'; ?>
