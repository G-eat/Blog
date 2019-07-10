<?php
  include '../app/views/include/header.php';
  if (!isset($_SESSION['admin'])) {
    Controller::redirect('post/index');
  }
?>

  <div class="container mt-4">
    <h5>Users.</h5>
    <ul class="list-group">
      <?php foreach ($this->data['users'] as $user) { ?>
        <li class="list-group-item list-group-item-action">
          <?php echo $user['username']; ?>
        </li>
      <?php } ?>
    </ul>
  </div>

 <?php
    include '../app/views/include/footer.php';
  ?>
