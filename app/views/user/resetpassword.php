<?php

  use App\Core\Controller;
  
  include '../app/views/include/header.php';
  include '../app/views/include/messages.php';

  if (!isset($_SESSION['user'])){ ?>
    <?php if ($this->data['error'] == 'error'): ?>
      <h5 class="alert alert-danger container">Not same password-confirm password.</h5>
    <?php endif; ?>

  <div class="container mt-4">
    <h3 class="text-primary mb-3">New Password</h3>
    <form action="/user/resetpassword" method="POST">
      <div class="form-group">
        <label class="text-info" for="exampleInputPassword1">New Password :</label>
        <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" name='password' required minlength=8 maxlength=20>
        <small id="passwordHelp" class="form-text text-muted">8-20 Characters long.</small>
      </div>
      <div class="form-group">
        <label class="text-info" for="exampleInputPassword2">Confirm :</label>
        <input type="password" class="form-control" id="exampleInputPassword2" placeholder="Confirm password" name='confirmpassword' required minlength=8 maxlength=20>
        <small id="confirmpassHelp" class="form-text text-muted">8-20 Characters long.</small>
      </div>
      <input type="hidden" name="hidden" value="<?php echo isset($this->data['username']) ? $this->data['username']:'' ?>">
      <input type="hidden" name="hiddenToken" value="<?php echo isset($this->data['token']) ? $this->data['token']:'' ?>">
      <button type="submit" name="submit" class="btn btn-primary">New Password</button>
    </form>
  </div>

 <?php
    include '../app/views/include/footer.php';
  ?>

<?php } else {
  Controller::redirect('/post/index');
} ?>
