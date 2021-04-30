    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="home.php"><img src="/www/BloodBank/assets/RW_BB.png" alt="Redwater Blood Banks" width="125" height="50"></a>
        <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false">
            <span class="navbar-toggler-icon"></span>
        </button>
            
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item <?php if(isset($setHomeActive)) { echo $setHomeActive; } else { echo '';}?>">
                    <a class="nav-link" href="home.php">Home</a>
                </li>
                
                <li class="nav-item dropdown <?php if(isset($setDonorActive)) {echo $setDonorActive;} else {echo '';}?>">
                    <a class="nav-link dropdown-toggle" id="Donor" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Donors
                    </a>
                    <div class="dropdown-menu" aria-labelledby="Donor">
                        <a class="dropdown-item <?php if(isset($setDonorRegActive)) {echo $setDonorRegActive;} else {echo '';}?>" href="donor.php?donors=Registered">Registered</a>
                        <a class="dropdown-item <?php if(isset($setDonorDonActive)) {echo $setDonorDonActive;} else {echo '';}?>" href="donor.php?donors=Donated">Donated</a>
                        <a class="dropdown-item <?php if(isset($setDonorAllActive)) {echo $setDonorActive;} else {echo '';}?>" href="donor.php">All</a>
                    </div>
                </li>
                
                <li class="nav-item dropdown <?php if(isset($setEmployeeActive)) {echo $setEmployeeActive;} else {echo '';}?>">
                    <a class="nav-link dropdown-toggle" id="Employee" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Employees
                    </a>
                    <div class="dropdown-menu" aria-labelledby="Employee">
                        <a class="dropdown-item <?php if(isset($setHomeActive)) {echo $setHomeActive;} else {echo '';}?>" href="home.php">Add</a>
                        <a class="dropdown-item <?php if(isset($setEmpViewActive)) {echo $setEmpViewActive;} else {echo '';}?>" href="employee.php">View</a>
                        <form method="get" action="employee.php">
                            <button class="dropdown-item <?php if(isset($setEmpUpdateActive)) {echo $setEmpUpdateActive;} else {echo '';}?>" name="Update" value="true" type="submit" role="button" href="employee.php">Update</button>
                        </form>
                        <form method="get" action="employee.php">
                            <button class="dropdown-item <?php if(isset($setEmpDeleteActive)) {echo $setEmpDeleteActive;} else {echo '';}?>" name="Delete" value="true" type="submit" role="button" href="employee.php">Delete</a>
                        </form>
                    </div>
                </li>
                
                <li class="nav-item dropdown <?php if(isset($setBranchActive)) {echo $setBranchActive;} else {echo '';}?>">
                    <a class="nav-link dropdown-toggle" id="Branch" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Branch
                    </a>
                    <div class="dropdown-menu" aria-labelledby="Branch">
                        <a class="dropdown-item <?php if(isset($setBrAddActive)) {echo $setBrAddActive;} else {echo '';}?>" href="addBr.php">Add</a>
                        <a class="dropdown-item <?php if(isset($setBrViewActive)) {echo $setBrViewActive;} else {echo '';}?>" href="branch.php">View</a>
                        <form method="get" action="branch.php">
                            <button class="dropdown-item <?php if(isset($setBrUpdateActive)) {echo $setBrUpdateActive;} else {echo '';}?>" name="Update" value="true" type="submit" role="button" href="branch.php">Update</button>
                        </form>
                        <form method="get" action="branch.php">
                            <button class="dropdown-item <?php if(isset($setBrDeleteActive)) {echo $setBrDeleteActive;} else {echo '';}?>" name="Delete" value="true" type="submit" role="button" href="branch.php">Delete</button>
                        </form>
                    </div>
                </li>
                
                <li class="nav-item <?php if(isset($setStockActive)) {echo $setStockActive; } else {echo '';}?>">
                    <a class="nav-link" href="bloodstock.php">Blood Stock</a>
                </li>
            </ul>
            <span class="navbar-text">
                <a class="btn btn-outline-danger ml-2" href="logout.php">Logout</a>
            </span>
        </div>
    </nav>