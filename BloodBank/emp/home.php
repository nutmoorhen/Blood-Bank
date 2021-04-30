<?php
session_start();
require_once 'php/DBConnect.php';
$db = new DBConnect();
$db->auth();

$currBr = $_SESSION['Branch'];

// Show recent donations (change 2nd param to show number of records)
$donations = $db->nRecentDonations($currBr, 5);
// Show branch employees
$emps = $db->viewEmployeesByBranch($currBr);
//Show blood stock
$stock = $db->viewBloodStockByBranch($currBr);
$totalUnits = $db->totalBloodStockByBranch($currBr);

$title = "Employee Home";
$setHomeActive = "active";
include 'layout/_header.php';
include 'layout/_navbar.php';
?>
<div class="container my-3">
    <div class="row justify-content-center mb-2">
        <div class="col-sm-8">
            <div class="accordion" id="EmployeeOptions">
                
                <div class="card">
                    <div class="card-header" id="donorInfo">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-center" type="button" data-toggle="collapse" data-target="#donorInfoOptions" aria-expanded="false" aria-controls="donorInfoOptions" style="color:#000407">
                            Donor Information
                            </button>
                        </h2>
                    </div>
                    <div id="donorInfoOptions" class="collapse" aria-labelledby="donorInfo" data-parent="#EmployeeOptions">
                        <div class="card-body">
                            <h5>Search for Donor(s) using the following options</h5>
                            <form class="form-horizontal" method="post" action="donor.php">
                                <div class="form-group row pl-3">
                                    <label for="ID" class="col-sm-4">Donor with ID</label>
                                    <input type="text" class="col-sm-3" name="ID" id="ID" required="true">
                                    <input type="radio" value="View" name="Opr" checked hidden>
                                    <button class="btn btn-sm offset-sm-1" value="true" name="Submit" style="background-color: #edb518">Search</button>
                                </div>
                           </form>
                            <form class="form-horizontal" method="post" action="donor.php">
                                <div class="form-group row pl-3">
                                    <label for="Name" class="col-sm-4">Donor with Name</label>
                                    <input type="text" class="col-sm-3" name="Name" id="Name" required="true">
                                    <input type="radio" value="View" name="Opr" checked hidden>
                                    <button class="btn btn-sm offset-sm-1" value="true" name="Submit" style="background-color: #edb518">Search</button>
                                </div>
                           </form>
                            <form class="form-horizontal" method="post" action="donor.php">
                                <div class="form-group row pl-3">
                                    <label for="BloodType" class="col-sm-4">Donor with Blood Type</label>
                                    <select class="col-sm-3" name="BloodType" id="BloodType" class="form-control">
                                        <option value="A+">A+</option>
                                        <option value="B+">B+</option>
                                        <option value="O+">O+</option>
                                        <option value="AB+">AB+</option>
                                        <option value="A-">A-</option>
                                        <option value="B-">B-</option>
                                        <option value="O-">O-</option>
                                        <option value="AB-">AB-</option>
                                    </select>
                                    <input type="radio" value="View" name="Opr" checked hidden>
                                    <button class="btn btn-sm offset-sm-1" value="true" name="Submit" style="background-color: #edb518">Search</button>
                                </div>
                            </form>
                            <form class="form-horizontal" method="post" action="donor.php">
                                <div class="form-group row pl-3">
                                    <label for="Age" class="col-sm-4">Donor with Age</label>
                                    <input class="col-sm-3" type="number" name="Age" id="Age" min="18" max="100" required="true">
                                    <input type="radio" value="View" name="Opr" checked hidden>
                                    <button class="btn btn-sm offset-sm-1" value="true" name="Submit" style="background-color: #edb518">Search</button>
                                </div>
                            </form>
                            <div class="row justify-content-center">
                                <a class="btn" href="donor.php" style="background-color: #68020f; color: #fffffe;">For more donor search options</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header" id="recordInfo">
                        <h2 class="mb-0">
                            <form method="post" action="">
                            <button name="showRecord" class="btn btn-link btn-block text-center" type="button" data-toggle="collapse" data-target="#recordInfoOptions" aria-expanded="<?php if(!empty($_POST['showRecord'])) {echo 'true';} else {echo 'false';}?>" aria-controls="recordInfoOptions" style="color:#000407">
                            Donation Record
                            </button>
                            </form>
                        </h2>
                    </div>
                    <div id="recordInfoOptions" class="collapse" aria-labelledby="recordInfo" data-parent="#EmployeeOptions">
                        <div class="card-body">
                            <p>Number of Donations: <?php echo count($donations); ?></p>
                            <div class="table-responsive">
                                <table class="table table-condensed">
                                    <thead>
                                        <th>ID</th>
                                        <th>Donor Name</th>
                                        <th>Donation Date</th>
                                    </thead>
                                <?php $i = 0; ?>
                                <?php foreach($donations as $d): ++$i; ?>
                                    <tr class="<?php if($i&1) {echo "tr";} else {echo "tr-next";}?>">
                                        <td><?php echo $d['D_ID']; ?></td>
                                        <td><?php echo $d['Name']; ?></td>
                                        <td><?php echo $d['DonatedOn']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </table>
                            </div>
                            <label class="col-sm-4"> </label>
                            <a class="btn" href="record.php" style="background-color: #68020f; color: #fffffe;">For complete donation record</a>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header" id="empInfo">
                        <h2 class="mb-0">
                            <button name="showEmployees" class="btn btn-link btn-block text-center" type="button" data-toggle="collapse" data-target="#empInfoOptions" aria-expanded="<?php if(!empty($_POST['showEmployees'])) {echo 'true';} else {echo 'false';} ?>" aria-controls="empInfoOptions"  style="color:#000407">
                            Branch Employees
                            </button>
                        </h2>
                    </div>
                    <div id="empInfoOptions" class="collapse" aria-labelledby="empInfo" data-parent="#EmployeeOptions">
                        <div class="card-body">
                            <p>Count: <?php echo count($emps); ?></p>
                            <div class="table-responsive">
                                <table class="table table-condensed">
                                    <thead>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                    </thead>
                                <?php $i = 0; ?>
                                <?php foreach($emps as $e): ++$i; ?>
                                    <tr class="<?php if($i&1) {echo "tr";} else {echo "tr-next";}?>">
                                        <td><?php echo $e['Name']; ?></td>
                                        <td><?php echo $e['Phone']; ?></td>
                                        <td><?php echo $e['Email']; ?></td>
                                    </tr>
                                <?php endforeach ?>
                                </table>
                            </div>
                            <div class="row justify-content-center">
                                <a class="btn" href="employee.php" style="background-color: #68020f; color: #fffffe;">For more employee info</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header" id="bloodStock">
                        <h2 class="mb-0">
                            <button name="showStock" class="btn btn-link btn-block text-center" type="button" data-toggle="collapse" data-target="#bloodStockInfo" aria-expanded="<?php if(!empty($_POST['showStock'])) {echo 'true';} else {echo 'false';} ?>" aria-controls="bloodStockInfo" style="color:#000407">
                            Available Blood Stock
                            </button>
                        </h2>
                    </div>
                    <div id="bloodStockInfo" class="collapse" aria-labelledby="bloodStock" data-parent="#EmployeeOptions">
                        <div class="card-body">
                            <p>Total Blood Stock Unit: <?php echo $totalUnits['TotalUnits']; ?></p>
                            <?php if($totalUnits['TotalUnits'] > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-condensed">
                                    <thead>
                                        <th>Blood Group</th>
                                        <th>Volume Unit</th>
                                    </thead>
                                <?php $i = 0; ?>
                                <?php foreach($stock as $s): ++$i; ?>
                                    <tr class="<?php if($i&1) {echo "tr";} else {echo "tr-next";}?>">
                                        <td><?php echo $s['BloodGroup']; ?></td>
                                        <td><?php echo $s['Amount']; ?></td>
                                    </tr>
                                <?php endforeach ?>
                                </table>
                            </div>
                            <?php else: ?>
                            <div class="row justify-content-center">
                                <span class="font-weight-bold">No blood stock available!</span>
                            </div>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'layout/_footer.php'; ?>