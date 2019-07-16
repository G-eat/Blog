<?php
  include '../app/views/include/header.php';
  include '../app/views/include/messages.php';
?>

  <div class="container mt-4">
    <div class="row">
      <div class="col-9 border-right">
        <h3 class="text-center">Articles of <?php echo $this->data['category'] ?></h3>
        <?php if (!count($this->data['articles'])): ?>
            <h6 class="alert alert-warning">No posts in this category.</h6>
        <?php endif; ?>
        <?php foreach ($this->data['articles'] as $article) { ?>
          <div class="card mb-3">
            <img class="card-img-top" src="\postPhoto\<?php echo $article['file_name'] ?>" style="width:70%;height:50%;margin:auto" alt="Card image cap">
            <div class="card-body">
              <h5 class="card-title"><?php echo $article['title'] ?></h5>
              <p class="card-text"><?php echo substr($article['body'], 0, 300); ?>...</p>
              <div class="row">
                <p class="card-text col-8"><small class="text-muted">Created at : <?php echo $article['created_at'] ?></small></p>
                <a href="/post/individual/<?php echo $article['slug'] ?>" class="btn btn-primary col-4" target="_blank">Read More</a>
              </div>
            </div>
          </div>
        <?php } ?>
      </div>
      <?php if (isset($this->data['categories'])){ ?>
          <div class="col-3 border-left" style="position:fixed;right:30px; height: 85vh;overflow: auto">
              <h3 class="text-center">Categories</h3>
              <div class="list-group">
                <?php foreach ($this->data['categories'] as $category) { ?>
                      <a href="/post/category/<?php echo $category['name'] ?>" class="list-group-item list-group-item-action <?php echo (isset($this->data['category']) && $this->data['category'] == $category['name']) ? 'active':'' ?>"><?php echo $category['name'] ?></a>
                  <?php } ?>
              </div>
      <?php } ?>
      <?php if (isset($this->data['category_articles'])){ ?>
              <h3 class="text-center mt-3">Some other post</h3>
              <div class="list-group">
                  <?php foreach ($this->data['category_articles'] as $article) { ?>
                      <a href="/post/individual/<?php echo $article['slug'] ?>" class="list-group-item list-group-item-action"><?php echo $article['title'] ?></a>
                  <?php } ?>
              </div>
          </div>
      <?php } ?>
    </div>
  </div>

 <?php
    include '../app/views/include/footer.php';
  ?>
