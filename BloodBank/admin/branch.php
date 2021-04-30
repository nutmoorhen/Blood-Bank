<?php
require_once 'php/DBConnect.php';
$db = new DBConnect();
$db->auth();

if(isset($_GET['Update'])){
    $info = "Click on 'Edit' to modify the selected row.";
}
else if(isset($_GET['Delete'])){
    $info = "Click on 'Delete' to delete the selected row.";
}

if(isset($_POST['submit'])) {
    $Name = !empty($_POST['Name']) ? $_POST['Name'] : null;
    $Email = !empty($_POST['Email']) ? $_POST['Email'] : null;
    $Phone = !empty($_POST['Phone']) ? $_POST['Phone'] : null;
    $Address = !empty($_POST['Address']) ? $_POST['Address'] : null;
    
    $branches = $db->viewBranches(null,$Name,$Email,$Phone,$Address);
    
    if(count($branches) > 0){
        $success = "Record found";
    }
    else{
        $message = "No record found";
    }
}
else{
    $branches = $db->viewBranches(null,null,null,null,null);
}

$title = "Branches";
$setBranchActive = "active";
$setBrViewActive = "active";
include 'layout/_header.php';
include 'layout/navbar.php';
?>
<div class="container my-3">
    <div class="row justify-content-center">
        <div class="col-sm-10">
            <?php if(isset($success)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $success; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <?php elseif(isset($message)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $message; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <?php elseif(isset($info)): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <?php echo $info; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <?php endif; ?>
            
            <div class="card">
                <div class="accordion" id="branchOptions">
                    <div class="card-header d-flex" id="branchInfo">
                        <div class="btn btn-link btn-block text-center" type="button" data-toggle="collapse" data-target="#branchInfoOptions" aria-expanded="<?php if (isset($_POST['submit'])) {echo 'false';} else {echo 'true';}?>" aria-controls="branchInfoOptions" style="color: #000407">Click to see available options to search branch</div>
                    </div>
                    
                    <div id="branchInfoOptions" class="collapse my-3" aria-labelledby="branchInfo" data-parent="#branchOptions">
                        <form method="post" role="form" action="branch.php">
                            <input type="text" class="form-group row col-sm-4 offset-sm-4" name="Name" placeholder="Search by Name">
                            <input type="text" class="form-group row col-sm-4 offset-sm-4" name="Email" placeholder="Search by Email">
                            <input type="text" class="form-group row col-sm-4 offset-sm-4" name="Address" placeholder="Search by Address">
                            <input type="text" class="form-group row col-sm-4 offset-sm-4" name="Phone" placeholder="Search by Phone">
                            <div class="form-group row justify-content-center">
                                <button type="submit" name="submit" value="true" class="btn btn-success offset-sm-1">Search</button>
                                <a href="branch.php" class="btn btn-success offset-sm-1">See All Branches</a>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                <?php if(isset($branches)): ?>
                    <?php if(!empty($branches) > 0): ?>
                    <table class="table table-responsive">
                        <thead class="bg-info">
                            <th>Branch ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th colspan=2>Options</th>
                        </thead>
                    <?php $i = 0; ?>
                    <?php foreach($branches as $b): ++$i; ?>
                        <tr class="<?php if($i&1) {echo "tr";} else {echo "tr-next";} ?>">
                            <td><?php echo $b['Br_ID'];?></td>
                            <td><?php echo $b['Name']; ?></td>
                            <td><?php echo $b['Email']; ?></td>
                            <td><?php echo $b['Phone']; ?></td>
                            <td><?php echo wordwrap($b['Address'], 36, "<br>"); ?></td>
                            <td><a href="editBranch.php?id=<?php echo $b['Br_ID']; ?>" style="color: #000407">Edit</a></td>
                            <td><a href="deleteBranch.php?id=<?php echo $b['Br_ID']; ?>" style="color: #000407">Delete</a></td>
                        </tr>
                    <?php endforeach ?>
                    </table>
                    <?php else: ?>
                        <p class="text-center font-weight-bold p-2">No branches found!</p>
                    <?php endif ?>
                <?php endif ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'layout/_footer.php'; ?>