<?php
  include '../app/views/include/header.php';
  include '../app/views/include/messages.php';
?>

  <div class="container mt-4">
    <div class="row">
      <div class="col-9 border-right">
          <?php if (!isset($_SESSION['user'])){ ?>
              <h3 class="text-center">Post of <?php echo $this->data['author'] ?></h3>
          <?php } elseif($this->data['author'] == $_SESSION['user']){ ?>
              <h3 class="text-center">Post of me </h3>
          <?php } else { ?>
              <h3 class="text-center">Post of <?php echo $this->data['author'] ?></h3>
          <?php } ?>
        <?php foreach ($this->data['articles'] as $article) { ?>
            <?php if (isset($_SESSION['user']) && $this->data['author'] == $_SESSION['user']): ?>
                <?php if ($article['is_published'] == 'pending'){ ?>
                    <span><?php echo $article['is_published'] ?></span>
                <?php } else { ?>
                    <span class="<?php echo ($article['is_published'] == 'Publish') ? 'text-success':'text-danger'?>"><?php echo $article['is_published'] ?></span>
                <?php } ?>
            <?php endif; ?>
          <div class="card mb-3">
            <?php if (isset($_SESSION['user']) && $this->data['author'] == $_SESSION['user']) { ?>
                <form action="/post/delete" method="post" style="float:right">
                    <input type="hidden" name="id" value="<?php echo $article['id'] ?>">
                    <input type="hidden" name="author" value="<?php echo $article['author'] ?>">
                    <input class="btn btn-danger btn-sm mr-4" type="submit" name="delete" value="Delete">
                </form>
            <?php } ?>
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
      <div class="col-3 border-left" style="position:fixed;right:30px">
        <?php if (isset($_SESSION['user'])){ ?>
            <p>Do you want to create post? </p><a href="/post/createpost">Create here.</a>
        <?php } else { ?>
            <p><a href="/user/login">LogIn</a> to create post.</p>
        <?php } ?>
      </div>
    </div>
  </div>

  <nav aria-label="...">
    <ul class="pagination container">
        <?php for ($i=1; $i <= $this->data['nr_page']; $i++) { ?>
            <li class="page-item <?php echo ($this->data['page_current'] == $i) ? 'active':'' ?>"><a class="page-link" href="/post/user/<?php echo $this->data['author'] ?>/<?php echo $i ?>"><?php echo $i ?></a></li>
        <?php } ?>
    </ul>
  </nav>

 <?php
    include '../app/views/include/footer.php';
  ?>
