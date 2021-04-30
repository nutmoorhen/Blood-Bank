<?php 
require_once 'php/DBConnect.php';
$db = new DBConnect();

$searchByName = (!(empty($_GET['Branch_Name']))) ? $_GET['Branch_Name'] : null;
if (isset($searchByName)){
    $branches = $db->searchBranchByName($searchByName);
}
else{
    $branches = $db->showBranches();
    $count = $db->countBranches();
}

$setBranchActive = "active";
$title = "Our Branches";
include 'layout/_header.php';
include 'layout/_navbar.php';
?>

<div class="container my-3">
    <div class="row justify-content-center">
        <div class="col-sm-8">
            <form method="get" role="form-horizontal" action="branch.php">
                <div class="form-group row justify-content-center">
                    <input type="text" class="form-inline" name="Branch_Name" placeholder="Search by branch name">
                    <button type="submit" class="btn btn-danger offset-sm-1">Search</button>
                    <a href="branch.php" class="btn btn-danger offset-sm-1">See All Branches</a>
                </div>
            </form>
        <?php if(!isset($searchByName)): ?>
            Number of Branches: <?php echo $count['Count']; ?>
        <?php if($count > 0): ?>
        <div class="table-responsive">
            <table class="table table-condensed">
                <thead class="bg-info">
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                </thead>
            <?php $i = 0; ?>
            <?php foreach($branches as $b): ++$i; ?>
                <tr class="<?php if($i&1) {echo "tr";} else {echo "tr-next";}?>">
                    <td><?php echo $b['Name']; ?></td>
                    <td><?php echo $b['Email']; ?></td>
                    <td><?php echo $b['Phone']; ?></td>
                    <td><?php echo wordwrap($b['Address'], 36, "<br>"); ?></td>
                </tr>
            <?php endforeach ?>
            </table>
        </div>
        <?php else: ?>
            <p class="text-center font-weight-bold p-2">No branches found!</p>
        <?php endif ?>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-condensed">
               <thead class="bg-info">
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                </thead>
            <?php $i = 0; ?>
            <?php foreach($branches as $b): ++$i; ?>
                <tr class="<?php if($i&1) {echo "tr";} else {echo "tr-next";}?>">
                    <td><?php echo $b['Name']; ?></td>
                    <td><?php echo $b['Email']; ?></td>
                    <td><?php echo $b['Phone']; ?></td>
                    <td><?php echo wordwrap($b['Address'], 36, "<br>"); ?></td>
                </tr>
            <?php endforeach ?>
            </table>
        </div>
        <?php endif ?>
        </div>
    </div>
</div>

<?php include 'layout/_footer.php' ?>