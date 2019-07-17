<?php

  use App\Core\Controller;
  
  include '../app/views/include/header.php';
  include '../app/views/include/messages.php';

  if (!isset($_SESSION['user'])){ ?>
    <?php if (isset($this->data['success'])): ?>
      <h3 class="alert alert-success container"><?php echo $this->data['success'] ?></h3>
    <?php endif; ?>

  <div class="container mt-4">
    <h3 class="text-primary mb-3">Reset Password</h3>
    <form action="/user/reset" method="POST">
      <div class="form-group">
        <label class="text-info" for="exampleInputEmail1">Email address :</label>
        <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email" name='email' required>
        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
      </div>
      <button type="submit" name="submit" class="btn btn-primary mt-3">Reset</button>
    </form>
  </div>

 <?php
    include '../app/views/include/footer.php';
  ?>

<?php } else {
  Controller::redirect('/post/index');
} ?>
