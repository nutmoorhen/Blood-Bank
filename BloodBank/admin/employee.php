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

$employees = $db->getEmployees();

$title="View Employee";
$setEmployeeActive = "active";
$setEmpViewActive = "active";
include 'layout/_header.php'; 
include 'layout/navbar.php';
?>

<div class="container my-3">
    <div class="row justify-content-center mb-2">
        <div class="col-sm-9">
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
            
                        <div class="card ">
                <div class="card-header">
                    <h5>Employees List</h5>
                </div>
                <div class="card-body">
                    <?php if(isset($employees)): ?>
                    <div class="table-responsive">
                        <table class="table table-condensed">
                            <thead>
                            <th>Name</th>
                            <th>Employee ID</th>
                            <th>Branch</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Salary</th>
                            <th colspan=2>Options</th>
                            </thead>
                            
                            <tbody>
                                <?php $i = 0; ?>
                                <?php foreach($employees as $e): ++$i; ?>
                                <tr class="<?php if($i&1) {echo "tr";} else {echo "tr-next";} ?>">
                                    <td><?php echo $e['Emp_ID']; ?></td>
                                    <td><?php echo $e['Name']; ?></td>                            
                                    <td><?php echo $e['Branch']; ?></td>
                                    <td><?php echo $e['Phone']; ?></td>
                                    <td><?php echo $e['Email']; ?></td>
                                    <td><?php echo $e['Salary']; ?></td>
                                    <td><a href="edit.php?id=<?php echo $e['Emp_ID']; ?>" style="color: #000407">Edit</a></td>
                                    <td><a href="delete.php?id=<?php echo $e['Emp_ID']; ?>" style="color: #000407">Delete</a></td>    
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'layout/_footer.php'; ?>

