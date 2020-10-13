<?php
require 'db.php';
$data=$_POST;
if(isset($data['do_signin'])){
    $errors=[];
    if(trim($data['email'])==''){
        $errors[]='Input email';
    }
    if(strlen($data['email'])>100){
        $errors[]='Too big email';
    }

    if( $data['password']==''){
        $errors[]='Input password';
    }
    if(strlen($data['password'])>100){
        $errors[]='Too big password';
    }
    if(empty($errors)){
        $user=R::findOne('users','email=?',array($data['email']));
        if($user){
            if(password_verify($data['password'],$user->password)){
                if($user->status=='blocked'){
                    echo '<div class="alert alert-danger" role="alert">You are blocked</div>';
                }
                else {
                    $user->lastdatelogin = date("d.m.y H:i:s");
                    $user->status = 'unblocked';
                    $user->lastactivity = time();
                    R::store($user);
                    $_SESSION['logged_user'] = $user->id;
                    header('Location: /');
                }
            }
            else{
                echo '<div class="alert alert-danger" role="alert">Incorrect login or password</div>';
            }
        }
        else{
            echo '<div class="alert alert-danger" role="alert">Incorrect llogin or password</div>';
        }
    }
    else{
        echo '<div class="alert alert-danger" role="alert">'.array_shift($errors).'</div>';
    }
}
if(isset($data['return'])){
    header('Location: /');
}
?>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
</head>
<nav class="navbar navbar-light bg-light">
    <span class="navbar-brand mb-0 h1">Authorization</span>
</nav>
<form class="mx-auto" action="/authorization.php" method="POST" style="width: 80%">
    <div class="form-group">
        <label for="exampleInputEmail1">Email address</label>
        <input type="email" class="form-control" name="email" aria-describedby="emailHelp" value="<?php echo @$data['email'];?>">
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">Password</label>
        <input type="password" class="form-control" name="password" >
    </div>
    <button type="submit" align="right" name="do_signin" class="btn btn-primary btn-lg" >Sign in</button>
    <button type="submit" align="right" name="return" class="btn btn-secondary btn-lg" >return to main page</button>
</form>