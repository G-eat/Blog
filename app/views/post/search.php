<?php
  include '../app/views/include/header.php';
  include '../app/views/include/messages.php';
?>

  <div class="container mt-4">
    <div class="row">
      <div class="col-9 border-right">
        <?php  include '../app/views/include/search.php'; ?>
        <h6 class="mt-3 mb-3">Search for <span class="text-info"><?php echo $this->data['search'] ?></span> results.</h6>
        <?php if (!count($this->data['articles'])): ?>
            <h6 class="alert alert-warning">No posts with those letters.</h6>
        <?php endif; ?>
        <?php foreach ($this->data['articles'] as $article) { ?>
            <div class="row" style="height:180px;overflow:hidden">
                <div class="col-4 mb-3">
                    <img class="card-img-top" src="\postPhoto\<?php echo $article['file_name'] ?>" alt="Card image cap">
                </div>
                <div class="col-8">
                    <h5 class="card-title"><?php echo $article['title'] ?></h5>
                    <p class="card-text"><?php echo substr($article['body'], 0, 100); ?>...</p>
                    <div class="row">
                        <p class="card-text col-8"><small class="text-muted">Created at : <?php echo $article['created_at'] ?></small></p>
                        <a href="/post/individual/<?php echo $article['slug'] ?>" class="btn btn-primary col-4" target="_blank">Read More</a>
                    </div>
                </div>
            </div>
            <br><hr><br>
        <?php } ?>
      </div>
      <div class="col-3 border-left" style="position:fixed;right:30px">
        <div class="list-group">
            <?php if (isset($_SESSION['user'])){ ?>
                <p>Do you want to create post? </p><a href="/post/createpost">Create here.</a>
            <?php } else { ?>
                <p><a href="/user/login">LogIn</a> to create post.</p>
            <?php } ?>
        </div>
      </div>
    </div>
  </div>

  <nav aria-label="...">
    <ul class="pagination container">
        <?php for ($i=1; $i <= $this->data['nr_page']; $i++) { ?>
            <li class="page-item <?php echo ($this->data['page_current'] == $i) ? 'active':'' ?>"><a class="page-link" href="/post/search/<?php echo $this->data['search'] ?>/<?php echo $i ?>"><?php echo $i ?></a></li>
        <?php } ?>
    </ul>
  </nav>

 <?php
    include '../app/views/include/footer.php';
  ?>
