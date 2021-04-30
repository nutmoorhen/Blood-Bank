<?php
require_once 'php/DBConnect.php';
$db = new DBConnect();
$db->auth();

if(isset($_GET['id'])){
    $Br_ID = $_GET['id'];
    $br = $db->viewBranches($Br_ID);
    
    if(!empty($br)){
        $br = $br[0];
    }
}
if (isset($_POST['Delete'])) {
    if ($_POST['Delete'] == "Y"){
        $Br_ID = $_POST['Br_ID'];
        $flag = $db->removeBranch($Br_ID);

        if ($flag) {
            header("Location: http://localhost:".$_SERVER['SERVER_PORT']."/www/Bloodbank/admin/branch.php");
        }
    }
    elseif ($_POST['Delete'] == "N"){
        header("Location: http://localhost:".$_SERVER['SERVER_PORT']."/www/Bloodbank/admin/branch.php");
    }
}

$title = "Remove Branch";
$setBranchActive = "active";
$setBrDeleteActive = "active";
include 'layout/_header.php';
include 'layout/navbar.php';
?>

<div class="container my-3">
    <div class="row justify-content-center">
        <div class="col-sm-9">
            <div class="card">
                <?php if(isset($Br_ID)): ?>
                <div class="card-header">
                    <h5>Do you wish to delete the following branch record?</h5>
                </div>
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="form-group">
                            <form method="post">
                                <input hidden type="text" value="<?php echo $br['Br_ID']; ?>" name="Br_ID">
                                <div class="tbe-responsive">
                                    <table class="table table-condensed">
                                        <thead>
                                            <th>Branch ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Address</th>
                                        </thead>
                                        <tbody>
                                            <tr class="tr">
                                                <td><?php echo $br['Br_ID'];?></td>
                                                <td><?php echo $br['Name']; ?></td>
                                                <td><?php echo $br['Email']; ?></td>
                                                <td><?php echo $br['Phone']; ?></td>
                                                <td><?php echo wordwrap($br['Address'], 36, "<br>"); ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row justify-content-center">
                                    <button class="btn btn-danger btn-md" value="Y" type="submit" name="Delete">Yes</button>
                                    <button class="btn btn-success btn-md offset-sm-1" value="N" type="submit" name="Delete">No</button>
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