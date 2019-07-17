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
        <h3 class="text-center">Comments</h3>
        <div class="list-group">
          <?php foreach ($this->data['comments'] as $comment) { ?>
              <a href='/admin/post/<?php echo $comment['article_id'] ?>' class="list-group-item list-group-item-action" id="<?php echo $comment['id'] ?>">
                <?php echo $comment['comment'] ?>
                <span style="float:right">
                <?php if ($comment['accepted'] === 'pending'){ ?>
                        <form action="/admin/accept" method="post">
                            <input class="btn btn-success" type="submit" name="is_accepted" value="Accepted">
                            <input class="btn btn-danger" type="submit" name="is_accepted" value="Reject">
                            <input type="hidden" name="id" value="<?php echo $comment['id'] ?>">
                        </form>
                <?php } elseif($comment['accepted'] == 'Accepted') { ?>
                    <p class="text-success">Accept</p>
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
