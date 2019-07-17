<?php

  use App\Core\Controller;
  
  include '../app/views/include/header.php';
  include '../app/views/include/messages.php';
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
                  <span style="float:left">
                      <form action="/admin/delete" method="post">
                          <input type="hidden" name="id" value="<?php echo $article['id'] ?>">
                          <input class="btn btn-danger btn-sm mr-4" type="submit" name="delete" value="X">
                      </form>
                  </span>
                <?php echo $article['title'] ?>
                <span style="float:right">
                <?php if ($article['is_published'] == 'pending'){ ?>
                        <form action="/admin/publish" method="post">
                            <input type="hidden" name="id" value="<?php echo $article['id'] ?>">
                            <input class="btn btn-success" type="submit" name="is_publish" value="Publish">
                            <input class="btn btn-danger" type="submit" name="is_publish" value="Reject">
                        </form>
                <?php } elseif($article['is_published'] == 'Publish') { ?>
                    <p class="text-success">Publish</p>
                <?php } else { ?>
                    <p class="text-danger">Reject</p>
                <?php } ?>
                </span>
            </a>
            <?php } ?>
        </div>
      </div>
    </div>
  </div>

 <?php
    include '../app/views/include/footer.php';
  ?>
