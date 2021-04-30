<?php
require_once 'php/DBConnect.php';
$db = new DBConnect();
$db->authLogin(); // if user has logged in already then forward it to home.php

$message=null;  // Used for wrong username or password message
if(isset($_POST['loginBtn'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $flag = $db->login($username, $password);
    if($flag){
        exit(header("Location: http://localhost:".$_SERVER['SERVER_PORT']."/www/bloodbank/emp/home.php")); //BloodBank/emp/home.php");
    } else {
        $message = "Wrong Username or Password. Try again.";
    }
}
$title = "Employee Login";
include 'layout/_header.php';
?>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <?php if(isset($message)): ?>
            <div class="alert-danger"><?php echo $message; ?></div>
            <?php endif; ?>
            <div class="card">
                <div class="card-header">
                    <h5> Employee Login </h5>
                </div>
                <div class="card-body">
                    <form class="form-vertical" role="form" method="post" action="">
                        <div class="form-group">
                            <input type="text" class="form-control" required="true" name="username" placeholder="Username">
                        </div>
                        <div class="form-group">
                            <input type="password" required="true" class="form-control" name="password" placeholder="Password">
                        </div>
                        <div class="form-group loginBtn row justify-content-center">
                            <button type="submit" name="loginBtn" value="true" class="btn btn-primary btn-md">Login</button>
                        </div>
                        <div class="form-group loginBtn row justify-content-center">
                            <a href="/www/bloodbank/index.php" class="btn btn-md btn-warning">I do not have username or password</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-3"></div>
    </div>
</div>

<?php include 'layout/_footer.php'; ?>