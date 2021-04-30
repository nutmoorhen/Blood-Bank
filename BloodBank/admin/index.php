<?php
require_once 'php/DBConnect.php';
$db = new DBConnect();
$db->authLogin();

$message = NULL;
if(isset($_POST['loginBtn'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if($username == "abc"){
        if($password == "000"){
            session_start();
            $_SESSION['username'] = $username;
            header("Location: http://localhost:".$_SERVER['SERVER_PORT']."/www/BloodBank/admin/home.php");
        } else {
            $message = "Invalid Password!";
        }
    }else{
        $message = "Invalid username or password!";
    }
}

$title="Admin Login";
include 'layout/_header.php';  
?>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <?php if(isset($message)): ?>
            <div class="alert-danger alert-dismissible fade show" role="alert">
                <?php echo $message; ?><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <?php endif; ?>
            <div class="card">
                <div class="card-header">
                    <h5> Admin Login </h5>
                </div>
                <div class="card-body">
                    <form class="form-vertical" role="form" method="post" action="">
                        <div class="form-group">
                            <input type="text" class="form-control" required="true" name="username" placeholder="Username">
                        </div>
                        <div class="form-group">
                            <input type="password" required="true" class="form-control" name="password" placeholder="Password">
                        </div>
                        <div class="form-group row justify-content-center">
                            <button type="submit" name="loginBtn" class="btn btn-primary btn-lg">Login</button>
                            <a type="button" name="home" class="btn btn-success btn-lg offset-sm-1" href="/www/BloodBank">Home</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-3"></div>
    </div>
</div>
<?php include 'layout/_footer.php'; ?>