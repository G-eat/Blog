<?php
  include '../app/views/include/header.php';
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
              <div class="row">
                <p class="card-text col-8"><small class="text-muted">Created at : <span class="text-info"><?php echo $this->data['article'][0]['created_at'] ?></span> by <span class="text-info"><a href='/post/user/<?php echo $this->data['article'][0]['author'] ?>'>
                    <?php echo $this->data['article'][0]['author'] ?></a></span></small></p>
                <p class="card-text col-4"><small class="text-muted" style="float:right">Category : <a href='/post/category/<?php echo $this->data['article'][0]['category'] ?>' class="text-info"><?php echo $this->data['article'][0]['category'] ?></a></span></small></p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-3 border-left" style="position:fixed;right:30px">
          <h3 class="text-center">Other Post of this author</h3>
          <div class="list-group">
              <?php foreach ($this->data['author_articles'] as $author_article) { ?>
                  <a href="/post/individual/<?php echo $author_article['slug'] ?>" class="list-group-item list-group-item-action"><?php echo $author_article['title'] ?></a>
              <?php } ?>
          </div>
      </div>
    </div>
  </div>


 <?php
    include '../app/views/include/footer.php';
  ?>
