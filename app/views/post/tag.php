<?php
  include '../app/views/include/header.php';
  include '../app/views/include/messages.php';
?>

  <div class="container mt-4">
    <div class="row">
      <div class="col-9 border-right">
        <h3 class="text-info mt-3"><?php echo $this->data['tag'] ?></h3>
            <?php foreach ($this->data['articles'] as $article) { ?>
                <div class="card mb-3">
                  <img class="card-img-top" src="\postPhoto\<?php echo $article[0]['file_name'] ?>" style="width:65%;height:50%;margin:auto" alt="Card image cap">
                  <div class="card-body">
                    <h5 class="card-title"><?php echo $article[0]['title'] ?></h5>
                    <p class="card-text"><?php echo substr($article[0]['body'], 0, 300); ?>...</p>
                    <div class="row">
                      <p class="card-text col-8"><small class="text-muted">Created at : <?php echo $article[0]['created_at'] ?></small></p>
                      <a href="/post/individual/<?php echo $article[0]['slug'] ?>" class="btn btn-primary col-4" target="_blank">Read More</a>
                    </div>
                  </div>
                </div>
            <?php } ?>
      </div>
      <div class="col-3 border-left" style="position:fixed;right:30px">
        <h3 class="text-center">Categories</h3>
        <div class="list-group">
          <?php foreach ($this->data['categories'] as $category) { ?>
                <a href="/post/category/<?php echo $category['name'] ?>" class="list-group-item list-group-item-action"><?php echo $category['name'] ?></a>
            <?php } ?>
        </div>
      </div>
    </div>
  </div>

 <?php
    include '../app/views/include/footer.php';
  ?>
