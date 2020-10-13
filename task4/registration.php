<?php
require 'db.php';
$data=$_POST;
if(isset($data['do_signup'])){
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
    if($data['confirmPassword']!=$data['password']){
        $errors[]='Passwords do not match';
    }
    if(R::count('users',"email = ?",array($data['email']))>0){
        $errors[]=' User with current email is exist';
    }
    if(empty($errors)){
        $user=R::dispense('users');
        $user->email=$data['email'];
        $user->password=password_hash($data['password'],PASSWORD_DEFAULT);
        $currdate=date("d.m.y H:i:s");
        $user->firstdatelogin=$currdate;
        $user->lastdatelogin=$currdate;
        $user->status='unblocked';
        $user->lastactivity=time();
        R::store($user);
        $_SESSION['logged_user']=$user->id;
        header ('Location: /');
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
    <span class="navbar-brand mb-0 h1">Registration</span>
</nav>
<form action="/registration.php" class="mx-auto" style="width: 80%" method="POST">
    <div class="form-group">
        <label for="exampleInputEmail1">Email address</label>
        <input type="email" class="form-control" name="email" value="<?php echo @$data['email'];?>" aria-describedby="emailHelp">
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">Password</label>
        <input type="password" class="form-control" name="password" value="<?php echo @$data['password'];?>">
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">Confirm Password</label>
        <input type="password" class="form-control" name="confirmPassword" id="confirmInputPassword1" value="<?php echo @$data['confirmPassword'];?>">
    </div>
    <button type="submit" name="do_signup"  class="btn btn-primary btn-lg" >Sign up</button>
    <button type="submit" align="right" name="return" class="btn btn-secondary btn-lg" >return to main page</button>
</form>