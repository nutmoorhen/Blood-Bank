    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="home.php"><img src="/www/BloodBank/assets/RW_BB.png" alt="Redwater Blood Banks" width="125" height="50"></a>
        <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item <?php if(isset($setHomeActive)) { echo $setHomeActive; } else { echo ''; }?>">
                    <a class="nav-link" href="home.php">Home</a>
                </li>
                
                <li class="nav-item btn-group <?php if(isset($setDonorActive)) { echo $setDonorActive; } else { echo ''; }?>">
                    <a class="nav-link" type="button" href="donor.php">Donors</a>
                    <a class="nav-link dropdown-toggle dropdown-toggle-split col-sm-1" type="button"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="sr-only">Toggle Dropdown</span>
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item <?php if(isset($setRegDonorActive)) {echo $setRegDonorActive;} else {echo '';}?>" href="donor.php?donors=Registered">Registered</a>
                        <a class="dropdown-item <?php if(isset($setDonDonorActive)) {echo $setDonDonorActive;} else {echo '';}?>" href="donor.php?donors=Donated">Donated</a>
                    </div>
                </li>
                
                <li class="nav-item <?php if(isset($setEmpActive)) { echo $setEmpActive; } else { echo ''; } ?>">
                    <a class="nav-link" href="employee.php">Employees</a>
                </li>
                
                <li class="nav-item <?php if(isset($setStockActive)) { echo $setStockActive; } else { echo ''; } ?>">
                    <a class="nav-link" href="bloodstock.php">Available Stock</a>
                </li>
                
                <li class="nav-item <?php if(isset($setRecordActive)) { echo $setRecordActive; } else { echo ''; } ?>">
                    <a class="nav-link" href="record.php">Donation Record</a>
                </li>
            </ul>
            <span class="navbar-text">
                Welcome <?php echo $_SESSION['Name']; ?>
                <a class="btn btn-outline-danger ml-2" href="logout.php">Logout</a>
            </span>
        </div>
    </nav>
