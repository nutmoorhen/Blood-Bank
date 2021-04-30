<?php
require_once 'php/DBConnect.php';
$db = new DBConnect();
$db->auth();

if(isset($_GET['donors'])){
    switch($_GET['donors']){
        case 'Registered':
            $title = "Registered Donors";
            $donors = $db->getRegisteredDonors();
            $setDonorRegActive = "active";
            break;
        case 'Donated':
            $title = "Donated Donors";
            $donors = $db->getDonatedDonors();
            $setDonorDonActive = "active";
            break;
    }
}
else{
    $title = "Donors";
    $donors = $db->getDonors();
    $setDonorAllActive = "active";
}

$setDonorActive = "active";
include 'layout/_header.php';
include 'layout/navbar.php';
?>
<div class="container my-3">
    <div class="row justify-content-center">
        <div class="col-sm-10">
            <div class="card">
                <div class="card-header">
                    <h3><?php echo $title; ?></h3>
                </div>
                
                <div class="card-body">
                    <?php if(isset($donors) && (count($donors) != 0)): ?>
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
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                        <div class="row justify-content-center">
                            <span class="font-weight-bold">No results found!</span>
                        </div>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'layout/_footer.php'; ?>