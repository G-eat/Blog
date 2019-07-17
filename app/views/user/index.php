<?php

  use App\Core\Controller;
  
  include '../app/views/include/header.php';
  include '../app/views/include/messages.php';
  include '../app/views/include/errors.php';

  if (!isset($_SESSION['user'])){ ?>

      <?php if ($this->data['msg'] == 'success'): ?>
        <h5 class="alert alert-<?php echo $this->data['msg']; ?> container"><?php $this->data['msg'] == 'success' ?></h5>
      <?php endif; ?>

      <?php if ($this->data['msg'] == 'error'): ?>
        <h5 class="alert alert-danger container">ERRROR.</h5>
      <?php endif; ?>


        <div class="container mt-4">
            <h3 class="text-primary mb-3">LogIn</h3>
            <form action="/user/login" method="POST">
                <div class="form-group">
                    <label class="text-info" for="exampleInputUsername1">Username :</label>
                    <input type="text" class="form-control" id="noSpaces" aria-describedby="usernameHelp" name="username" value="<?php echo isset($this->data['username']) ? $this->data['username']:'' ?>" placeholder="Username" onkeyup="this.value = this.value.toLowerCase();" autocapitalize="none" required minlength=8>
                    <small id="usernameHelp" class="form-text text-muted">Your username.</small>
                </div>
                <div class="form-group">
                    <label class="text-info" for="exampleInputPassword1">Password :</label>
                    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" name="password" required minlength=8 maxlength=20>
                </div>
                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" name="remmember_me" id="exampleCheck1">
                    <label class="form-check-label" for="exampleCheck1">Remember me</label>
                </div>
                <div>
                    <a href='/user/reset'>Reset Password.</a>
                </div>
                <button type="submit" name="submit" class="btn btn-primary mt-3">LogIn</button>
            </form>
        </div>

 <?php
    include '../app/views/include/footer.php';
  ?>

<?php } else {
  Controller::redirect('/post/index');
} ?>
