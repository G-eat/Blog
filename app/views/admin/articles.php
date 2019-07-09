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
        <div class="list-group" id='sortable'>
          <?php foreach ($this->data['articles'] as $article) { ?>
              <a href="/post/individual/<?php echo $article['slug'] ?>" class="list-group-item list-group-item-action" id="<?php echo $article['id'] ?>">
                <?php echo $article['title'] ?>
            </a>
            <?php } ?>
        </div>
      </div>
    </div>
  </div>

 <?php
    include '../app/views/include/footer.php';
  ?>
