<?php
require 'db.php';
$unblocked_user=R::findOne('users','id=?',[$_SESSION['logged_user']]);
if($unblocked_user->status=='blocked'){
    unset($_SESSION['logged_user']);
    header('Location: /');
}else {
    if (isset($_POST['delete'])) {
        $checks = $_POST['cheks'];
        if (empty($checks)) {
            echo '<div class="alert alert-danger" role="alert">Choose user</div>';
        } else {
            foreach ($checks as $item) {
                $user = R::findOne('users', 'email=?', [$item]);
                if ($_SESSION['logged_user'] == $user->id) {
                    unset($_SESSION['logged_user']);
                }
                R::trash($user);
            }
            header('Location: /');
        }
    }
    if (isset($_POST['block'])) {
        $checks = $_POST['cheks'];
        if (empty($checks)) {
            echo '<div class="alert alert-danger" role="alert">Choose user</div>';
        } else {
            foreach ($checks as $item) {
                $user = R::findOne('users', 'email=?', [$item]);
                if ($_SESSION['logged_user'] == $user->id) {
                    unset($_SESSION['logged_user']);
                }
                $user->status = 'blocked';
                R::store($user);
            }
            header('Location: /');
        }
    }
    if (isset($_POST['unblock'])) {
        $checks = $_POST['cheks'];
        if (empty($checks)) {
            echo '<div class="alert alert-danger" role="alert">Choose user</div>';
        } else {
            foreach ($checks as $item) {
                $user = R::findOne('users', 'email=?', [$item]);
                $user->status = 'unblocked';
                R::store($user);
            }
            header('Location: /');
        }
    }
    if (isset($_POST['logoff'])) {
        unset($_SESSION['logged_user']);
        header('Location: /');
    }
}
?>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
</head>
<?php if(isset($_SESSION['logged_user'])){
    $users = R::findAll('users'); ?>
<form action="index.php" method="POST">
<div class="btn-group" role="group" aria-label="Basic example">
  <button type="submit" name="block" class="btn btn-secondary">Block</button>
  <button type="submit" name="unblock" class="btn btn-secondary">Unblock</button>
  <button type="submit" name="delete" class="btn btn-secondary">Delete</button>
  <button type="submit" name="logoff" class="btn btn-primary">Log off</button>
</div>
<table class="table" name="usertable">
  <thead>
    <tr>
      <th scope="col">
      <input type="checkbox"  id="select_all">
      </th>
      <th scope="col">#</th>
      <th scope="col">Email</th>
      <th scope="col">First Login Date</th>
      <th scope="col">Last Login Date</th>
      <th scope="col">Status</th>
    </tr>
  </thead>
    <script src="check_all.js"></script>
  <tbody>
  <?php
    $i=0;
    $name='checkbox';
    foreach ($users as &$item){ $i++;?>
        <tr>
      <td>
      <input type='checkbox' name='cheks[]' value="<?php echo $item->email;?>">
      </td>
      <th scope='row'><?php echo $i;?></th>
      <td><?php echo $item->email;?></td>
      <td><?php echo $item->firstdatelogin;?></td>
      <td><?php echo $item->lastdatelogin;?></td>
      <td><?php echo $item->status;?></td>
    </tr>
    <?php }?>
  </tbody>
 </table>
</form>
<?php }else{?>
<button type = "button" onclick = "location.href='registration.php'" class="btn btn-primary btn-lg btn-block" > Sign up </button >
<button type = "button" onclick = "location.href='authorization.php'" class="btn btn-secondary btn-lg btn-block" > Sign in </button >
  <?php
}?>
