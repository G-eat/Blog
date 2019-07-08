<?php

  include '../app/views/include/header.php';
  if (!isset($_SESSION['admin'])) {
    Controller::redirect('post/index');
  }
?>

  <div class="container mt-4">
    <div class="row">
      <div class="col-12">
        <h3 class="text-center">Articles</h3>
        <div class="list-group">
          <?php foreach ($this->data['articles'] as $article) { ?>
              <div class="list-group-item list-group-item-action">
                <?php echo $article['title'] ?>
              </div>
            <?php } ?>
        </div>
      </div>
    </div>
  </div>


 <?php
    include '../app/views/include/footer.php';
  ?>
