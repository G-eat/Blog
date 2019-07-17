<?php

  use App\Core\Controller;
  
  include '../app/views/include/header.php';
  include '../app/views/include/messages.php';

  if (!isset($_SESSION['admin'])) {
    Controller::redirect('post/index');
  }
?>

  <div class="container mt-4">
    <h5>Click on item-list for more details.</h5>
    <div class="list-group">
      <a href="/admin/categories" class="list-group-item list-group-item-action">Categories</a>
      <a href="/admin/users" class="list-group-item list-group-item-action">Users</a>
      <a href="/admin/articles" class="list-group-item list-group-item-action">Articles</a>
      <a href="/admin/comments" class="list-group-item list-group-item-action">Comments</a>
      <a href="/admin/tags" class="list-group-item list-group-item-action">Tags</a>
    </div>
  </div>

 <?php
    include '../app/views/include/footer.php';
  ?>
