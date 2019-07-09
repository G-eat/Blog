<?php
  include '../app/views/include/header.php';
?>

  <div class="container mt-4">
    <div class="row">
      <div class="col-9 border-right">
        <h3 class="text-center">Articles</h3>
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
        <h3 class="text-center">Categories</h3>
        <div class="list-group">
          <?php foreach ($this->data['categories'] as $category) { ?>
            <!-- < if (isset($_SESSION['admin'])){ ?> -->
              <!-- <form action="/category/delete" method="post">
                <input type="hidden" name="category_id" value="< echo $category['id'] ?>">
                <a href="/category/show/< echo $category['name'] ?>" class="list-group-item list-group-item-action">< echo $category['name'] ?><span style="float:right"><button type="submit" class="btn btn-outline-danger btn-sm">X</button></span></a>
              </form> -->
              <!--  } else { ?> -->
                <a href="/post/category/<?php echo $category['name'] ?>" class="list-group-item list-group-item-action"><?php echo $category['name'] ?></a>
              <!--  } ?> -->
            <?php } ?>
        </div>
        <!-- < if(isset($_SESSION['admin'])) {?> -->
          <!-- Button trigger modal -->
          <!-- <button type="button" class="btn btn-primary btn-block mt-3" data-toggle="modal" data-target="#exampleModal">
            ADD Category
          </button> -->
        <!-- < } ?> -->
      </div>
    </div>
  </div>

  <!-- Modal -->
  <!-- <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add Category</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="/category/add" method="post">
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <button type="submit" name="submit" class="btn btn-primary">Add</button>
              </div>
              <input type="text" name="add_category" placeholder="Add new category..." class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" required maxlength="20">
            </div>
          </form>
        </div>
      </div>
    </div>
  </div> -->

 <?php
    include '../app/views/include/footer.php';
  ?>
