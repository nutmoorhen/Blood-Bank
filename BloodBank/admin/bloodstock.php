<?php
require_once 'php/DBConnect.php';
$db = new DBConnect();
$db->auth();

if(isset($_POST['Submit'])){
    $branchID = isset($_POST['branchID']) ? $_POST['branchID'] : null;
    $bloodType = ($_POST['BloodType'] != 'null') ? $_POST['BloodType'] : null;
    $sort = ($_POST['sort']=='ASC' || $_POST['sort']=='DESC')? $_POST['sort'] : null;
    
    if(isset($_POST['volChoice'])){
        switch($_POST['volChoice']){
            case 'Between':
                if(!empty($_POST['vol1']) && !empty($_POST['vol2'])){
                    $VolumeInfo = ['Vol'=>[$_POST['vol1'], $_POST['vol2']], '<='=>true, '>='=>true];
                    break;
                }
                else{
                    $message = "No value has been provided";
                    goto end;
                }
            case 'On_Or_Before':
                if(!empty($_POST['vol<='])){
                    $VolumeInfo = ['Vol'=>[$_POST['vol<=']], '<='=>true, '>='=>false];
                    break;
                }
                else{
                    $message = "No value has been provided";
                    goto end;
                }
            case 'On_Or_After':
                if(!empty($_POST['vol>='])){
                    $VolumeInfo = ['Vol'=>[$_POST['vol>=']], '<='=>false, '>='=>true];
                    break;
                }
                else{
                    $message = "No value has been provided";
                    goto end;
                }
            default:
                if(!empty($_POST['vol='])){
                    $VolumeInfo = ['Vol'=>[$_POST['vol=']], '<='=>false, '>='=>false];
                }
                else{
                    $message = "No value has been provided";
                    goto end;
                }
        }
    }
    else{
        $VolumeInfo = null;
    }
    $stock = $db->viewBloodStockByBranch($branchID,$bloodType,$VolumeInfo,$sort);
    
    if(count($stock) != 0){
        $success = "Records found";
    }
}
else{
    $stock = $db->viewBloodStockByBranch(null, null, null, null);
}

end: ;

$title = "Blood Stock";
$setStockActive = "active";
include 'layout/_header.php';
include 'layout/navbar.php';
?>

<div class="container my-3">
    <div class="row justify-content-center">
        <div class="col-md-7">
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
        
            <form method="post" class="form-horizontal" role="form" action="">
                <div class="accordion" id="bloodOptions">
                    <div class="card">
                        <div class="card-header d-flex" id="bloodInfo">
                            <div class="btn btn-link btn-block text-center" type="button" data-toggle="collapse" data-target="#bloodInfoOptions" aria-expanded="false" aria-controls="bloodInfoOptions" style="color: #000407"><h5>Access bloodgroup using the following options</h5></div>
                        </div>
                        
                        <div id="bloodInfoOptions" class="collapse" aria-labelledby="bloodInfo" data-parent="#bloodOptions">
                            <div class="card-body">
                            <div class="form-group row">
                                <label for="branchID" class="col-sm-3">Branch ID</label>
                                <input type="text" name="branchID" id="branchID" class="col-sm-4">
                            </div>
                            
                            <div class="form-group row">
                                <label for="BloodType" class="col-sm-3">Blood Type</label>
                                <select name="BloodType" id="BloodType" class="col-sm-4" style="height: 35px">
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
                                <label class="col-sm-4">Volume (in units)</label>
                                <div class="row col-sm-12">
                                    <label for="onVol" class="col-sm-5"><input type="radio" value="On" name="volChoice" id="onVol"> Equal to</label>
                                    <input type="text" name="vol=" class="col-sm-2">
                                </div>
                                <div class="row col-sm-12 mt-1">
                                    <label for="on_lessVol" class="col-sm-5"><input type="radio" value="On_Or_Before" name="volChoice" id="on_lessVol"> Less than or equal to</label>
                                    <input type="text" name="vol<=" class="col-sm-2">
                                </div>
                                <div class="row col-sm-12 mt-1">
                                    <label for="on_moreVol" class="col-sm-5"><input type="radio" value="On_Or_After" name="volChoice" id="on_moreVol"> More than or equal to</label>
                                    <input type="text" name="vol>=" class="col-sm-2">
                                </div>
                                <div class="row col-sm-12 mt-1">
                                    <label for="betweenVol" class="ml-3">
                                        <input type="radio" value="Between" name="volChoice"> Between <input type="text" name="vol1" style="width: 100px"> and <input type="text" name="vol2" style="width: 100px">
                                    </label>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="sort" class="col-sm-3"> Sort by Amount: </label>
                                <select name="sort" id="sort" class="col-sm-4 form-control" style="height: 40px">
                                    <option value="null"></option>
                                    <option value="ASC">Ascending</option>
                                    <option value="DESC">Descending</option>
                                </select>
                            </div>
                            
                            <div class="form-group row justify-content-center">
                                <button class="btn btn-success" type="submit" name="Submit">Submit</button>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </form>
            
            <?php if (isset($stock)): ?>
            <div class="table-responsive">
                <table class="table table-condensed mt-4">
                    <thead>
                        <th>Branch ID</th>
                        <th>Blood Group</th>
                        <th>Units Available</th>
                    </thead>
                <?php $i=0; ?>
                <?php foreach($stock as $s): ++$i; ?>
                    <tr class="<?php if($i&1) {echo "tr";} else {echo "tr-next";} ?>">
                        <td><?php echo $s['Br_ID']; ?></td>
                        <td><?php echo $s['BloodGroup']; ?></td>
                        <td><?php echo $s['Amount']; ?></td>
                    </tr>
                <?php endforeach ?>
                </table>
            </div>
            <?php else: ?>
                <div class="row justify-content-center">
                    <span class="font-weight-bold py-3" style="color: #fffffe">No results found!</span>
                </div>
            <?php endif ?>
        </div>
    </div>
</div>

<?php include 'layout/_footer.php' ?>