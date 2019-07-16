<?php
  include '../app/views/include/header.php';
  include '../app/views/include/messages.php';
?>

  <div class="container mt-4">
    <div class="row">
      <div class="col-9">
        <h3 class="text-center text-info"><?php echo $this->data['article'][0]['title'] ?></h3>
        <div class="list-group">
          <div class="card mb-3">
            <img class="card-img-top" src="\postPhoto\<?php echo $this->data['article'][0]['file_name'] ?>" style="width:70%;height:50%;margin:auto" alt="Card image cap">
            <div class="card-body">
              <p class="card-text"><?php echo $this->data['article'][0]['body'] ?></p>
              <br>
              <hr>
              <?php foreach ($this->data['tags'] as $tag) { ?>
                  <a href="/post/tag/<?php echo substr($tag['tag_name'], 1); ?>"><?php echo $tag['tag_name']; ?></a>
              <?php } ?>
              <div class="row">
                <p class="card-text col-8"><small class="text-muted">Created at : <span class="text-info"><?php echo $this->data['article'][0]['created_at'] ?></span> by <span class="text-info"><a href='/post/user/<?php echo $this->data['article'][0]['author'] ?>'>
                    <?php echo $this->data['article'][0]['author'] ?></a></span></small></p>
                <p class="card-text col-4"><small class="text-muted" style="float:right">Category : <a href='/post/category/<?php echo $this->data['article'][0]['category'] ?>' class="text-info"><?php echo $this->data['article'][0]['category'] ?></a></span></small></p>
              </div>
            </div>
          </div>
        </div>
        <?php if (isset($_SESSION['user'])){ ?>
            <h6 class="mt-5">Leave a commment :</h6>
            <form action="/comment/create" method="post">
                <div class="input-group">
                    <input type="hidden" name="author" value="<?php echo $_SESSION['user'] ?>">
                    <input type="hidden" name="article_id" value="<?php echo $this->data['article'][0]['id'] ?>">
                    <input type="hidden" name="article_slug" value="<?php echo $this->data['article'][0]['slug'] ?>">
                    <input type="text" class="form-control" name="comment" placeholder="Comment..." required>
                    <input type="submit" class="input-group-prepend" name="submit" value="Comment">
                </div>
            </form>
        <?php } else { ?>
            <span class="text-muted">LogIn to leave a comment.</span><a href="/user/login">LogIN</a>
        <?php } ?>
        <h1 class="mt-3 text-info">All Comments</h1>
        <?php foreach ($this->data['comments'] as $comment): ?>
            <div class="card border border-danger">
              <div class="card-header text-info">
                <a href="/post/user/<?php echo $comment['author'] ?>"><?php echo $comment['author'] ?></a>
              </div>
              <div class="card-body">
                  <h5 class="card-title"><?php echo $comment['comment'] ?></h5>
                  <p class="card-text text-muted"><?php echo $comment['created_at'] ?>
                      <?php if ((isset($_SESSION['user']) && $_SESSION['user'] === $comment['author']) || isset($_SESSION['admin'])): ?>
                          <a href='/comment/delete/<?php echo $comment['id'] ?>/<?php echo $this->data['article'][0]['slug'] ?>' style="float:right">Delete</a>
                          <a data-toggle="modal" class="open-AddBookDialog mr-3" data-id="<?php echo $comment['id'] ?>" data-slug="<?php echo $this->data['article'][0]['slug'] ?>"
                          data-comment="<?php echo $comment['comment'] ?>" data-target="#exampleModal" style="float:right;cursor:pointer;">Update</a>
                      <?php endif; ?>
                  </p>
              </div>
            </div>
            <br>
        <?php endforeach; ?>
      </div>
      <div class="col-3 border-left" style="position:fixed;right:30px">
          <?php if (count($this->data['author_articles']) >= 1): ?>
              <h3 class="text-center">Other Post of this author</h3>
          <?php endif; ?>
          <div class="list-group">
              <?php foreach ($this->data['author_articles'] as $author_article) { ?>
                  <a href="/post/individual/<?php echo $author_article['slug'] ?>" class="list-group-item list-group-item-action <?php echo (isset($this->data['article'][0]['slug']) && $this->data['article'][0]['slug'] == $author_article['slug']) ? 'active':'' ?>"><?php echo $author_article['title'] ?></a>
              <?php } ?>
          </div>
          <?php if (!$this->data['article'][0]['category'] == null): ?>
              <a href="/post/category/<?php echo $this->data['article'][0]['category'] ?>" class="btn btn-secondary mt-3">More posts in this category</a>
          <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Update comment</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
              <div class="modal-body">
                  <form action="/comment/update" method="post">
                      <div class="input-group">
                          <input type="hidden" name="author" value="<?php echo $_SESSION['user'] ?>">
                          <input type="hidden" name="update_id" value="" id='id'>
                          <input type="hidden" name="comment_slug" value="" id='slug'>
                          <input type="text" class="form-control" name="update_comment" value="" id='comment' required>
                          <input type="submit" class="input-group-prepend" name="submit" value="Update">
                      </div>
                  </form>
              </div>
          </div>
        </div>
      </div>
    </div>

 <?php
    include '../app/views/include/footer.php';
  ?>
