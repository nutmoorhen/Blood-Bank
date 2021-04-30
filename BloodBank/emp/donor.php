<?php 
require_once 'php/DBConnect.php';
$db = new DBConnect();
$db->auth();

$currBr = $_SESSION['Branch'];
$donors = null;
$message = null;
$success = null;
if(isset($_GET['donors'])){
    switch($_GET['donors']){
        case 'Registered':
            $title = "Registered Donors";
            $setRegDonorActive = "active";
            $donors = $db->getRegisteredDonorsInBranch($currBr);
            break;
        case 'Donated':
            $title = "Donors who donated";
            $setDonDonorActive = "active";
            $donors = $db->getDonatedDonorsInBranch($currBr);
            break;
    }
}
else{
    $title = "Blood Donors";
    if(isset($_POST['Submit']) && !empty($_POST['Opr'])){
        $ID = isset($_POST['ID']) ? $_POST['ID'] : null;
        $Name = isset($_POST['Name']) ? $_POST['Name'] : null;
        $BloodType = ($_POST['BloodType'] != "null") ? $_POST['BloodType'] : null;
        $Age = isset($_POST['Age']) ? $_POST['Age'] : null;
        $Phone = isset($_POST['Phone']) ? $_POST['Phone'] : null;
        $Email = isset($_POST['Email']) ? $_POST['Email'] : null;
        $Weight = isset($_POST['Weight']) ? $_POST['Weight'] : null;
        
        $Gender = null;
        if(isset($_POST['Gender'])){
            switch($_POST['Gender']){
                case "Male": $Gender = 'M'; break;
                case "Female": $Gender = 'F'; break;
                case "Other": $Gender = 'O'; break;
            }
        }
        
        if ($_POST['Opr'] == 'View'){
            $donors = $db->getDonorsByBranch($currBr,$ID,$Name,$BloodType,$Age,$Gender,$Phone,$Email,$Weight);
            
            if(count($donors) > 0)
                $success = "Results Found";
            else
                $message = "No result found";
        }
        else if ($_POST['Opr'] == 'Insert'){
            if(!empty($ID)&&!empty($Name)&&!empty($BloodType)&&!empty($Age)&&!empty($Phone)&&!empty($Email)&&!empty($Weight)){
                $flag = $db->addDonor($ID,$Name,$BloodType,$Age,$Gender,$Phone,$Email,$Weight);
                if($flag){
                    $success = "Inputs are valid and insert has been successful";
                }
                else{
                    $message = "Valid inputs but unsuccessful insert";
                }
            }
            else{
                $message = "One or more inputs are missing";
            }
        }
        else if ($_POST['Opr'] == 'Update'){
            if(!empty($ID)){
                $flag = $db->updateDonor($ID,$Name,$Age,$Gender,$Phone,$Email,$Weight);
                if($flag){
                    $success = "Update successful";
                }
                else{
                    $message = "Missing values to update";
                }
            }
            else{
                $message = "Missing Donor ID to update";
            }
        }
        else if ($_POST['Opr'] == 'Delete'){
            if(!empty($Name) || !empty($ID)){
                if(!empty($Name) && empty($ID)){
                    $donor = $db->getDonorsByBranch($currBr,$name = $Name);
                    
                    if(count($donor) == 1){
                        $ID = $donor['ID'];
                    }else{
                        $message = "Failed to find ID for name provided";
                        goto end;
                    }
                }
                else{
                    $donor = $db->getDonorsByBranch($currBr,$ID);
                    
                    if(count($donor) == 1){
                        $ID = $donor['ID'];
                    }else{
                        $message = "Failed to find donor for ID provided";
                        goto end;
                    }
                }
                // To count donation(s) done by Donor whose ID is provided
                $records = $db->findDonationsByID($ID);
                
                // If there exists more records of other branches or the one record found is done in different branch, cannot delete
                if ((count($records) != 1) || ($records['B_ID'] != $currBr)){
                    $message = "Delete failed. The existing donor has records in other branches. It is advised to delete the donor's record instead.";
                }
                // Else only the querying branch or no branch have record of donor. So then delete
                else{
                    $flag = $db->deleteDonor($currBr, $ID);
                    $success = "Delete successful";
                }
            }
            // Donor ID and name to delete is missing
            else{
                $message = "Missing Donor ID or name to be used for deletion";
            }
        }
        end: ;
    }
    else if (isset($_POST['Submit'])){
        $message = "No operation selected";
    }
}

$setDonorActive = "active";
include 'layout/_header.php';
include 'layout/_navbar.php';
?>

<div class="container my-3">
    <div class="row justify-content-center" <?php if(isset($_GET['donors'])) {echo 'hidden';} ?>>
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
            
            <form method="post" class="form-horizontal" role="form" action="donor.php">
                <div class="accordion" id="donorOptions">
                    <div class="card">
                        <div class="card-header d-flex" id="donorInfo">
                            <button class="btn btn-link btn-block text-center" type="button" data-toggle="collapse" data-target="#donorInfoOptions" aria-expanded="<?php if(isset($_POST['Submit']) && !empty($_POST['Opr'])) {echo 'true';} else {echo 'false';} ?>" aria-controls="donorInfoOptions" style="color: #000407">Access Donor Record using the following options</button>
                        </div>
                        
                        <div id="donorInfoOptions" class="<?php if(isset($_POST['Opr']) && $_POST['Opr'] == 'View') {echo 'collapse';} ?>" aria-labelledby="donorInfo" data-parent="#donorOptions">
                            <div class="card-body">
                            <div class="form-group row">
                                <label for="ID" class="col-sm-3">Donor ID</label>
                                <input type="text" name="ID" id="ID" class="col-sm-4">
                            </div>
                            <div class="form-group row">
                                <label for="Name" class="col-sm-3">Name</label>
                                <input type="text" name="Name" id="Name" class="col-sm-4">
                            </div>
                            <div class="form-group row">
                                <label for="BloodType" class="col-sm-3">Blood Type</label>
                                <select name="BloodType" id="BloodType" class="col-sm-4 form-control" style="height: 35px">
                                    <option value="null"></option>
                                    <option value="A+">A+</option>
                                    <option value="B+">B+</option>
                                    <option value="O+">O+</option>
                                    <option value="AB+">AB+</option>
                                    <option value="A-">A-</option>
                                    <option value="B-">B-</option>
                                    <option value="O-">O-</option>
                                    <option value="AB-">AB-</option>
                                </select>
                            </div>
                            <div class="form-group row">
                                <label for="Age" class="col-sm-3">Age</label>
                                <input type="number" name="Age" id="Age" class="col-sm-4" min="18" max="100">
                            </div>
                            <div class="form-group row">
                                <label for="Gender" class="col-sm-3">Gender</label>
                                <select name="Gender" id="Gender" class="col-sm-4 form-control" style="height: 35px">
                                    <option value="null"></option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="form-group row">
                                <label for="Phone" class="col-sm-3">Phone</label>
                                <input type="text" name="Phone" id="Phone" class="col-sm-4">
                            </div>
                            <div class="form-group row">
                                <label for="Email" class="col-sm-3">Email</label>
                                <input type="email" name="Email" id="Email" class="col-sm-4">
                            </div>
                            <div class="form-group row">
                                <label for="Weight" class="col-sm-3">Weight</label>
                                <input type="text" name="Weight" id="Weight" class="col-sm-4">
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3">To perform</label>
                                <select name="Opr" id="Opr" class="col-sm-3 form-control" style="height: 35px">
                                    <option value="View">View</option>
                                    <option value="Insert">Insert</option>
                                    <option value="Delete">Delete</option>
                                    <option value="Update">Update</option>
                                </select>
                            </div>
                            <hr>
                            <div class="form-group row justify-content-center">
                                <button class="btn btn-success" type="submit" value="true" name="Submit">Submit</button>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row justify-content-center mt-2">
        <?php if(isset($_GET['donors'])): ?>
            <div class="card mb-3">
                <div class="card-header">
                    <h3><?php echo $title; ?></h3>
                </div>
            </div>
        <?php endif ?>
        <?php if(isset($donors)): ?>
            <?php if(!empty($donors)): ?>
            <div class="table-responsive">
                <table class="table table-condensed">
                <thead>
                    <tr>
                        <th>Donor ID</th>
                        <th>Name</th>
                        <th>Blood Type</th>
                        <th>Age</th>
                        <th>Gender</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Weight</th>
                    </tr>
                </thead>
                <tbody>
                <?php $i = 0; ?>
                <?php foreach($donors as $d): ++$i; ?>
                    <tr class="<?php if($i&1) {echo "tr";} else {echo "tr-next";}?>">
                        <td><?php echo $d['D_ID']; ?></td>
                        <td><?php echo $d['Name']; ?></td>
                        <td><?php echo $d['Bloodtype']; ?></td>
                        <td><?php echo $d['Age']; ?></td>
                        <td><?php echo $d['Gender']; ?></td>
                        <td><?php echo $d['Phone']; ?></td>
                        <td><?php echo $d['Email']; ?></td>
                        <td><?php echo $d['Weight']; ?></td>
                    </tr>
                </tbody>
            <?php endforeach; ?>
            </table>
            </div>
            <?php else: ?>
            <div class="row justify-content-center">
                <span class="font-weight-bold" style="color: #fffffe">No results found!</span>
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
                        <ul>
                        <li>To <strong>view</strong> all donors who have donated, simply click 'View Donor' without filling any input. Multiple inputs will result in finding record(s) that matches all inputs.</li>
                        <li>To <strong>insert</strong> a new donor, fill all input options and click 'Add Donor'.</li>
                        <li>To <strong>update</strong> an existing donor, provide new inputs (except Blood Type) to the correct input box and click 'Update Donor'. Attempt to 'Update Donor' without Donor ID will result in failure.</li>
                        <li>To <strong>delete</strong> an existing donor, provide the donor information to the correct input option and click 'Delete Donor'. Donors can be deleted using their ID and/or using name. 
                        If both are provided, delete is successful if only one donor for given name is found and the given ID matches with ID present in existing record.</li>
                        </ul>
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