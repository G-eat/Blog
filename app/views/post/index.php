<?php

  include '../app/views/include/header.php';
?>

  <div class="container mt-4">
    <div class="row">
      <div class="col-9 border-right">
        <h3 class="text-center">Articles</h3>
      </div>
      <div class="col-3 border-left">
        <h3 class="text-center">Categories</h3>
        <div class="list-group">
          <?php foreach ($this->data['categories'] as $category) { ?>
            <a href="/post/<?php echo $category['name'] ?>" class="list-group-item list-group-item-action"><?php echo $category['name'] ?></a>
          <?php } ?>
        </div>
        <?php if(isset($_SESSION['admin'])) {?>
          <a href="#">ADD Category</a>
        <?php } ?>
      </div>
    </div>
  </div>

 <?php
    include '../app/views/include/footer.php';
  ?>
