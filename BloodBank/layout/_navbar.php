    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="home.php"><img src="/www/BloodBank/assets/RW_BB.png" alt="Redwater Blood Banks" width="125" height="50"></a>
        <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item <?php if(isset($setHomeActive)) {echo $setHomeActive;} else {echo '';}?>">
                    <a class="nav-link" href="/www/BloodBank/index.php">Home</a>
                </li>
                <li class="nav-item <?php if(isset($setBranchActive)) {echo $setBranchActive;} else { echo '';}?>">
                    <a class="nav-link" href="/www/BloodBank/branch.php">Our Branches</a>
                </li>
                <li class="nav-item <?php if(isset($setFAQActive)) {echo $setFAQActive;} else {echo '';}?>">
                    <a class="nav-link" href="/www/BloodBank/FAQ.php">FAQ</a>
                </li>
            </ul>
            <span class="navbar-text">
                <a class="btn btn-outline-danger ml-2" href="/www/BloodBank/emp">Employee Login</a>
            </span>
        </div>
    </nav>