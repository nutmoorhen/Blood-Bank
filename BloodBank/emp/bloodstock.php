<?php
require_once 'php/DBConnect.php';
$db = new DBConnect();
$db->auth();

$currBr = $_SESSION['Branch'];
$stock = $db->viewBloodStockByBranch($currBr, ((isset($_GET['sort']))? $_GET['sort'] : null), (isset($_GET['findBloodType']) && ($_GET['findBloodType'] != "null") ? $_GET['findBloodType'] : null));
$totalUnits = $db->totalBloodStockByBranch($currBr);

$title = "Blood Stock";
$setStockActive = "active";
include 'layout/_header.php';
include 'layout/_navbar.php';
?>

<div class="container my-3">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <form method="get" class="form-horizontal mb-3" role="form" action="bloodstock.php">
                <div class="form-group row justify-content-center" style="color: #fffffe">
                    <label for="sort" class="col-sm-3 col-form-label">Sort by Amount:</label>
                    
                    <select name="sort" id="sort" class="col-sm-4 form-control" style="height: 40px">
                        <option value="null"></option>
                        <option value="ASC">Ascending</option>
                        <option value="DESC">Descending</option>
                    </select>
                    
                    <div class="col-sm-1">
                        <button class="btn btn-success" type="submit">Sort</button>
                    </div>
                </div>
                <div class="form-group row justify-content-center" style="color: #fffffe">
                    <label for="findBloodType" class="col-sm-3 col-form-label">Find for:</label>
                    
                    <select name="findBloodType" id="findBloodType" class="col-sm-4 form-control" style="height: 35px">
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
                    
                    <div class="col-sm-1">
                        <button class="btn btn-success" type="submit">Find</button>
                    </div>
                </div>
            </form>
            
            <?php if ($totalUnits != 0): ?>
            <div class="table-responsive">
                <table class="table table-condensed">
                    <thead>
                        <th>Blood Group</th>
                        <th>Units Available</th>
                    </thead>
                <?php $i=0; ?>
                <?php foreach($stock as $s): ++$i; ?>   
                    <tr class="<?php if($i&1) {echo "tr";} else {echo "tr-next";} ?>">
                        <td><?php echo $s['BloodGroup']; ?></td>
                        <td><?php echo $s['Amount']; ?></td>
                    </tr>
                <?php endforeach ?>
                </table>
            </div>
            <?php else: ?>
            <div class="row justify-content-center">
                <span class="font-weight-bold" style="color: #fffffe">No results found!</span>
            </div>
            <?php endif ?>
        </div>
        <div class="col-md-2"></div>
    </div>
</div>

<?php include 'layout/_footer.php' ?>