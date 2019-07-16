<?php
  include '../app/views/include/header.php';
  include '../app/views/include/messages.php';
?>

  <div class="container mt-4">
    <div class="row">
      <div class="col-9 border-right">
        <?php  include '../app/views/include/search.php'; ?>
        <h3 class="mt-3" style="float:left">Articles</h3>
        <form method="post" action='/post/index' style="margin-top:30px">
            <input type="submit" name="position" class="btn btn-sm btn-dark ml-1" style="float:right" value="Default">
            <input type="submit" name="created_at" class="btn btn-sm btn-secondary ml-3" style="float:right" value="New">
            <label style="float:right">OrderBy:</label>
        </form>
        <div style="clear:both"></div>
        <?php if (isset($this->data['error']) && $this->data['error'] !== ''): ?>
            <h6 class="alert alert-warning"><?php echo $this->data['error'] ?></h6>
        <?php endif; ?>
        <?php foreach ($this->data['articles'] as $article) { ?>
          <div class="card mb-3">
            <img class="card-img-top" src="\postPhoto\<?php echo $article['file_name'] ?>" style="width:65%;height:50%;margin:auto" alt="Card image cap">
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

  <nav aria-label="...">
    <ul class="pagination container">
        <?php for ($i=1; $i <= $this->data['nr_page']; $i++) { ?>
            <li class="page-item <?php echo ($this->data['page_current'] == $i) ? 'active':'' ?>"><a class="page-link" href="/post/index/<?php echo $this->data['order'] ?>/<?php echo $i ?>"><?php echo $i ?></a></li>
        <?php } ?>
    </ul>
  </nav>

 <?php
    include '../app/views/include/footer.php';
  ?>
