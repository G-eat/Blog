<?php
  include '../app/views/include/header.php';
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
      <div class="col-3 border-left" style="position:fixed;right:30px">
        <?php if (isset($_SESSION['user'])){ ?>
            <p>Do you want to create post? </p><a href="/post/createpost">Create here.</a>
        <?php } else { ?>
            <p><a href="/user/login">LogIn</a> to create post.</p>
        <?php } ?>
      </div>
    </div>
  </div>

 <?php
    include '../app/views/include/footer.php';
  ?>
