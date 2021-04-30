<?php 
require_once 'php/DBConnect.php';
$db = new DBConnect();
$db->auth();

$currBr = $_SESSION['Branch'];
$records = null;
if(isset($_POST['Submit']) && !empty($_POST['Opr'])){
    $ID = !empty($_POST['D_ID']) ? $_POST['D_ID'] : null;
    $Date = !empty($_POST['DonatedOn']) ? $_POST['DonatedOn'] : null;
    $Vol = !empty($_POST['Vol']) ? $_POST['Vol'] : null;
    
    if ($_POST['Opr'] == 'Insert'){
        if(isset($ID) && isset($Date) && isset($Vol)){
            $flag = $db->addDonation($currBr,$ID,$Date,$Vol);
            if ($flag){
                $success = "New record has been added";
            }
            else{
                $message = "Valid inputs but failed to add record";
            }
        }else{
            $message = "Missing date and/or volume";
        }
    }
    else if ($_POST['Opr'] == 'Update'){
        if (isset($Date) && isset($Vol)){
            if ($Vol < 0){
                $message = "Invalid Volume provided";
            }
            else{
                $record = $db->viewDonationsByOptions($currBr,$ID);
                if(empty($record)){
                    $message = "Cannot update a record that does not exist";
                    goto end;
                }
                else{
                    $record = $record[0];
                }
                
                $origin = date_create($record['DonatedOn']);
                $update = date_create($Date);
                $interval = date_diff($origin, $update);  // Here $Date is the new date to update
                
                $flag = $db->updateDonation($currBr,$ID,$Date,$Vol);
                if($interval->format("%a") < "56" && $interval->format("%a") > 0){
                    $warning = "Record with Donor ID ".$ID." has been updated but interval between date is less than 56 days";
                }
                else{
                    $success = "Record with Donor ID ".$ID." has been updated";
                }
            }
        }else{
            $message = "Missing input or invalid volume provided.";
        }
    }
    else if ($_POST['Opr'] == 'Delete'){
        if (isset($ID)){
            $flag = $db->removeDonation($currBr,$ID);
            $success = "All donation record for donor ID ".$ID." has been deleted";
        }else{
            $message = "Donor ID is missing";
        }
    }
    else if ($_POST['Opr'] == 'View'){
        if(!empty($_POST['enableFilter'])){
            $ID = isset($_POST['enableID']) ? $_POST['ID'] : null;
            $Name = isset($_POST['enableName']) ? $_POST['Name'] : null;
            $SortDate = isset($_POST['enableSort']) ? strtoupper($_POST['SortByDate']) : null;
            $Bloodtype = isset($_POST['enableBloodType']) ? $_POST['BloodType'] : null;
            
            if(isset($_POST['enableDate'])){
                switch($_POST['dateChoice']){
                    case 'Between':
                        $DonationInfo = ['Date'=>[$_POST['date1'], $_POST['date2']], '<='=>true, '>='=>true]; break;
                    case 'On_Or_Before':
                        $DonationInfo = ['Date'=>[$_POST['date<=']], '<='=>true, '>='=>false]; break;
                    case 'On_Or_After':
                        $DonationInfo = ['Date'=>[$_POST['date>=']], '<='=>false, '>='=>true]; break;
                    default:
                        $DonationInfo = ['Date'=>[$_POST['date=']], '<='=>false, '>='=>false];
                }
            }else{
                $DonationInfo = null;
            }
            if(isset($_POST['enableVol'])){
                switch($_POST['volChoice']){
                    case 'Between':
                        $VolumeInfo = ['Vol'=>[$_POST['vol1'], $_POST['vol2']], '<='=>true, '>='=>true]; break;
                    case 'On_Or_Before':
                        $VolumeInfo = ['Vol'=>[$_POST['vol<=']], '<='=>true, '>='=>false]; break;
                    case 'On_Or_After':
                        $VolumeInfo = ['Vol'=>[$_POST['vol>=']], '<='=>false, '>='=>true]; break;
                    default:
                        $VolumeInfo = ['Vol'=>[$_POST['vol=']], '<='=>false, '>='=>false];
                }
            }else{
                $VolumeInfo = null;
            }
            $records = $db->viewDonationsByOptions($currBr,$ID,$Name,$Bloodtype,$DonationInfo,$VolumeInfo,$SortDate);
        }
        else{
            $records = $db->viewDonationsByBranch($currBr);
        }
        
        if(count($records) > 0){
            $success = "Results found";
        }else{
            $message = "No record found";
        }
    }
}

end: ;

$title = "Donation Record";
$setRecordActive = "active";
include 'layout/_header.php';
include 'layout/_navbar.php';
?>

<div class="container my-3">
    <div class="row justify-content-center">
        <div class="col-sm-8">
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
            <?php if(isset($warning)): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <?php echo $warning; ?><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <?php endif ?>
            
            <form method="post" action="">
                <div class="accordion" id="recordOptions">
                    <div class="card">
                        <div class="card-header d-flex" id="recordInfo">
                            <div class="btn btn-link btn-block text-center" type="button" data-toggle="collapse" data-target="#recordInfoOptions" aria-expanded="<?php if (isset($_POST['Submit'])) {echo 'false';} else {echo 'true';}?>" aria-controls="recordInfoOptions" style="color: #000407">Access the blood donation record using the options below</div>
                        </div>
                        
                        <div id="recordInfoOptions" class="<?php if (isset($_POST['Opr']) && $_POST['Opr'] == 'View') {echo 'collapse';}?>" aria-labelledby="recordInfo" data-parent="#recordOptions">
                            <div class="card-body">
                                <div class="form-group row">
                                    <label for="D_ID" class="col-sm-4">Donor ID</label>
                                    <input type="text" name="D_ID" id="D_ID" class="col-sm-4">
                                </div>
                                <div class="form-group row">
                                    <label for="DonatedOn" class="col-sm-4">Donated On</label>
                                    <input type="date" name="DonatedOn" id="DonatedOn" class="col-sm-4" style="height:30px">  <?php // Date Format: YYYY/MM/DD ?>
                                </div>
                                <div class="form-group row">
                                    <label for="Vol" class="col-sm-4">Volume (units)</label>
                                    <input type="text" name="Vol" id="Vol" class="col-sm-4 mr-auto">
                                    <label class="col-sm-4">1 unit = 470 ml</label>
                                </div>
                                <div class="form-group row">
                                    <label for="Opr" class="col-sm-4">To perform</label>
                                    
                                    <select name="Opr" id="Opr"  class="col-sm-4 form-control" style="height: 35px">
                                        <option value="View">View</option>
                                        <option value="Insert">Insert</option>
                                        <option value="Update">Update</option>
                                        <option value="Delete">Delete</option>
                                    </select>
                                </div>
                                
                                <div class="accordion mb-2" id="FilterOptions">
                                    <div class="card">
                                        <div class="card-header" id="FilterBtn">
                                            <h2>
                                                <button class="btn btn-link btn-block" type="button" data-toggle="collapse" data-target="#filterChoices" aria-expanded="<?php if(!empty($_POST['filterRecord'])) {echo 'true';} else {echo 'false';} ?>" aria-controls="filterChoices"  style="color: #000407">
                                                Filter Records
                                                </button>
                                            </h2>
                                        </div>
                                        <div id="filterChoices" class="collapse" aria-labelledby="FilterBtn" data-parent="#FilterOptions">
                                            <div class="card-body">
                                                <h5>Filter the record(s) to be shown using the options and enable the associated checkbox. Finally enable 'Filter' checkbox</h5>
                                                <p>NOTE: Enabling multiple options results in finding records that matches ALL enabled checkboxes.</p>
                                                <hr>
                                                <div class="form-group">
                                                    <input type="checkbox" value="true" id="enableID" name="enableID"/>
                                                    <label for="enableID" class="col-sm-3">Donor ID</label>
                                                    <input type="text" name="ID" id="ID">
                                                </div>
                                                <div class="form-group">
                                                    <input type="checkbox" value="true" id="enableName" name="enableName"/>
                                                    <label for="enableName" class="col-sm-3">Name</label>
                                                    <input type="text" name="Name" id="Name">
                                                </div>
                                                <div class="form-group">
                                                    <input type="checkbox" value="true" id="enableBloodType" name="enableBloodType"/>
                                                    <label for="enableBloodType" class="col-sm-3">Blood Type</label>
                                                    <select name="BloodType" id="BloodType" class="col-sm-4">
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
                                                <div class="form-group">
                                                    <input type="checkbox" value="true" id="enableDate" name="enableDate"/>
                                                    <label for="enableDate" class="col-sm-4">Donation Date</label>
                                                    <div class="row col-sm-12">
                                                        <label for="onDate" class="col-sm-4"><input type="radio" value="On" name="dateChoice" id="onDate" checked> On</label>
                                                        <input type="date" name="date=" style="height:30px">
                                                    </div>
                                                    <div class="row col-sm-12 mt-1">
                                                        <label for="on_beforeDate" class="col-sm-4"><input type="radio" value="On_Or_Before" name="dateChoice" id="on_beforeDate"> On or Before</label>
                                                        <input type="date" name="date<=" style="height:30px">
                                                    </div>
                                                    <div class="row col-sm-12 mt-1">
                                                        <label for="on_afterDate" class="col-sm-4"><input type="radio" value="On_Or_After" name="dateChoice" id="on_afterDate"> On or After</label>
                                                        <input type="date" name="date>=" style="height:30px">
                                                    </div>
                                                    <div class="row col-sm mt-1">
                                                        <label for="betweenDate" class="ml-3">
                                                            <input type="radio" value="Between" name="dateChoice"> Between <input type="date" name="date1" style="width:145px"> and <input type="date" name="date2" style="width:145px">
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <input type="checkbox" value="true" id="enableVol" name="enableVol"/>
                                                    <label for="enableVol" class="col-sm-4">Volume (in units)</label>
                                                    <div class="row col-sm-12">
                                                        <label for="onVol" class="col-sm-6"><input type="radio" value="On" name="volChoice" id="onVol" checked> Equal to</label>
                                                        <input type="text" name="vol=" class="col-sm-2">
                                                    </div>
                                                    <div class="row col-sm-12 mt-1">
                                                        <label for="on_lessVol" class="col-sm-6"><input type="radio" value="On_Or_Before" name="volChoice" id="on_lessVol"> Less than or equal to</label>
                                                        <input type="text" name="vol<=" class="col-sm-2">
                                                    </div>
                                                    <div class="row col-sm-12 mt-1">
                                                        <label for="on_moreVol" class="col-sm-6"><input type="radio" value="On_Or_After" name="volChoice" id="on_moreVol"> More than or equal to</label>
                                                        <input type="text" name="vol>=" class="col-sm-2">
                                                    </div>
                                                    <div class="row col-sm-12 mt-1">
                                                        <label for="betweenVol" class="ml-3">
                                                            <input type="radio" value="Between" name="volChoice"> Between <input type="text" name="vol1" style="width: 100px"> and <input type="text" name="vol2" style="width: 100px">
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <input type="checkbox" value="true" id="enableSort" name="enableSort"/>
                                                    <label for="enableSort" class="col-sm-5">Sort by Donation Date</label>
                                                    <input type="radio" value="asc" name="SortByDate" id="asc"><label for="asc" class="mx-2"> Ascending </label>
                                                    <input type="radio" value="desc" name="SortByDate" id="desc"><label for="desc" class="mx-2"> Descending </label>
                                                </div>
                                                <div class="form-group">
                                                    <input type="checkbox" value="true" id="enableFilter" name="enableFilter"/>
                                                    <label for="enableFilter" class="col-sm-9">Filter Records - <strong>Must be checked to apply</strong></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row justify-content-center">
                                    <button class="btn btn-success" type="submit" name="Submit">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row justify-content-center mt-2">
    <?php if(isset($records)): ?>
        <?php if(!empty($records)): ?>
        <p style="color: #fffffe">Record Count: <?php echo count($records); ?></p>
        <div class="table-responsive">
            <table class="table table-condensed">
                <thead>
                    <th>Donor ID</th>
                    <th>Name</th>
                    <th>Blood Type</th>
                    <th>Last Donated On</th>
                    <th>Total Volume Donated (in units)</th>
                </thead>
            <?php $i = 0; ?>
            <?php foreach($records as $r): ++$i; ?>
                <tr class="<?php if($i&1) {echo "tr";} else {echo "tr-next";} ?>">
                    <td><?php echo $r['D_ID']; ?></td>
                    <td><?php echo $r['Name']; ?></td>
                    <td><?php echo $r['Bloodtype']; ?></td>
                    <td><?php echo $r['DonatedOn']; ?></td>
                    <td><?php echo $r['Volume']; ?></td>
                </tr>
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
                        <p>To insert, update or delete record, select the appropriate option to perform before submitting.</p>
                        <ul>
                        <li>To <strong>insert</strong> a new donation record, provide input to all input boxes and select 'Insert' to perform.</li>
                        <li>When <strong>updating</strong>, make sure to provide the donor ID to update and to select 'Update' to perform.</li>
                        <ul>
                            <li>To correct most recent donation date, simply provide the new date and give previous value to the volume donated.</li>
                            <li>To only modify volume donated, provide the new volume while keeping the donation date same.</li>
                            <li>To modify both, provide the new values accordingly.</li>
                        </ul>
                        <br>
                        <li>For <strong>deleting</strong>, provide the ID of the donor to remove and select 'Delete' to perform. Note that doing so will result in deletion of all 
                        donations done by donor whose ID is provided while ignoring the remaining inputs. So delete record(s) cautiously.</li>
                        <br>
                        <li>To <strong>view</strong> all donation records, simply select 'View' to perform. To filter the records to be shown, click 'Filter Records' to see available filter options.</li>
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