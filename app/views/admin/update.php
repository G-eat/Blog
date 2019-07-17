<?php

  use App\Core\Controller;
  
  include '../app/views/include/header.php';
  include '../app/views/include/messages.php';

  if (!isset($_SESSION['admin'])) {
    Controller::redirect('post/index');
  }
?>

  <div class="container mt-4">
    <h5>Update</h5>
    <form action="/category/update" method="post">
      <div class="form-group">
        <input type="hidden" name="category_id" value="<?php echo $this->data['value'][0]['id'] ?>">
        <input type="text" class="form-control"  value="<?php echo $this->data['value'][0]['name'] ?>" name='category' required maxlength="20">
        <button type="submit" name="submit" class="btn btn-block btn-primary mt-3">Update</button>
      </div>
    </form>
  </div>

 <?php
    include '../app/views/include/footer.php';
  ?>
