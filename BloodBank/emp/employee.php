<?php 
require_once 'php/DBConnect.php';
$db = new DBConnect();
$db->auth();

$currBr = $_SESSION['Branch'];
$emps = null;
$message = null;
$success = null;
if (isset($_POST['Submit'])){
    $EmpID = (!empty($_POST['EmpID'])) ? $_POST['EmpID'] : null;
    $Name = (!empty($_POST['Name'])) ? $_POST['Name'] : null;
    $Phone = (!empty($_POST['Phone'])) ? $_POST['Phone'] : null;
    $Email = (!empty($_POST['Email'])) ? $_POST['Email'] : null;

    if(isset($EmpID)||isset($Name)||isset($Phone)||isset($Email)){
        $emps = $db->viewSpecificBranchEmployees($currBr,$EmpID,$Name,$Phone,$Email);
    }
    else{
        $emps = $db->viewEmployeesByBranch($currBr);
    }
    
    if(!empty($emps)){
        $success = "Results Found";
    }
    else{
        $message = "No result found";
    }
}

$setEmpActive = "active";
$title = "Employee Record";
include 'layout/_header.php';
include 'layout/_navbar.php';
?>

<div class="container my-3">
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <?php if(isset($success)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $success; ?><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <?php endif; ?>
            <?php if(isset($message)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $message; ?><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <?php endif; ?>
        
            <form method="post" class="form-horizontal" action="">
                <div class="accordion" id="empOptions">
                    <div class="card">
                        <div class="card-header d-flex" id="empInfo">
                            <div class="btn btn-link btn-block text-center" type="button" data-toggle="collapse" data-target="#empInfoOptions" aria-expanded="<?php if (isset($_POST['Submit'])) {echo 'false';} else {echo 'true';}?>" aria-controls="empInfoOptions" style="color: #000407">View Employee Record using the options below</div>
                        </div>
                        
                        <div id="empInfoOptions" class="<?php if (isset($_POST['Submit'])) {echo 'collapse';}?>" aria-labelledby="empInfo" date-parent="#empOptions">
                            <div class="card-body">
                                <div class="form-group row">
                                    <label for="EmpID" class="col-sm-3">Employee ID</label>
                                    <input type="text" name="EmpID" id="EmpID" class="col-sm-4">
                                </div>
                                <div class="form-group row">
                                    <label for="Name" class="col-sm-3">Name</label>
                                    <input type="text" name="Name" id="Name" class="col-sm-4">
                                </div>
                                <div class="form-group row">
                                    <label for="Phone" class="col-sm-3">Phone</label>
                                    <input type="text" name="Phone" id="Phone" class="col-sm-4">
                                </div>
                                <div class="form-group row">
                                    <label for="Email" class="col-sm-3">Email</label>
                                    <input type="email" name="Email" id="Email" class="col-sm-4">
                                </div>
                                <div class="form-group row justify-content-center">
                                    <button class="btn btn-success" type="submit" value="true" name="Submit">View</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-3"></div>
    </div>
    <div class="row justify-content-center mt-2">
        <?php if(isset($emps)): ?>
            <?php if(!(empty($emps))): ?>
            <p style="color: #fffffe">Result Count: <?php echo count($emps); ?></p>
            <div class="table-responsive">
                <table class="table table-condensed">
                    <thead>
                        <th>Employee ID</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                    </thead>
                <?php $i = 0; ?>
                <?php foreach($emps as $e): ++$i; ?>
                    <tr class="<?php if($i&1) {echo "tr";} else {echo "tr-next";}?>">
                        <td><?php echo $e['Emp_ID']; ?></td>
                        <td><?php echo wordwrap($e['Name'], 36, "<br>"); ?></td>
                        <td><?php echo $e['Phone']; ?></td>
                        <td><?php echo $e['Email']; ?></td>
                    </tr>
                <?php endforeach; ?>
                </table>
            </div>
            <?php else: ?>
            <div class="row justify-content-center">
                <span class="font-weight-bold p-4" style="color: #fffffe">No results found!</span>
            </div>
            <?php endif ?>
        <?php endif ?>
    </div>
    <div class="row justify-content-center mt-2">
        <button class="btn btn-outline-light col-sm-3" type="button" data-toggle="modal" data-target="#helpInfoBox"> Help </button>
        
        <div class="modal fade" id="helpInfoBox" tabindex="-1" aria-labelledby="modalHeading" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 id="modalHeading" class="modal-title">Help Information</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p> To view all employee, simply click 'View' without filling any input. To view any particular employee, provide correct value to the necessary input options and click 'View'.</p>
                        <br>
                        <p>NOTE: 'View' with multiple options will result in finding record(s) that match with all selected options.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'layout/_footer.php' ?>