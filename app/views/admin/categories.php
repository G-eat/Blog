<?php
  include '../app/views/include/header.php';
  include '../app/views/include/messages.php';

  if (!isset($_SESSION['admin'])) {
    Controller::redirect('post/index');
  }
?>

  <div class="container mt-4">
    <div class="row">
      <div class="col-12">
        <h3 class="text-center">Categories</h3>
        <div class="list-group">
          <?php foreach ($this->data['categories'] as $category) { ?>
              <form action="/category/delete" method="post">
                <input type="hidden" name="category_id" value="<?php echo $category['id'] ?>">
                <input type="hidden" name="category_name" value="<?php echo $category['name'] ?>">
                <a href="/category/update/<?php echo $category['id'] ?>" class="list-group-item list-group-item-action"><?php echo $category['name'] ?><span style="float:right"><button type="submit" class="btn btn-outline-danger btn-sm">X</button></span></a>
              </form>
            <?php } ?>
        </div>
        <?php if(isset($_SESSION['admin'])) {?>
          <!-- Button trigger modal -->
          <button type="button" class="btn btn-primary btn-block mt-3" data-toggle="modal" data-target="#exampleModal">
            ADD Category
          </button>
        <?php } ?>
      </div>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
  </div>

 <?php
    include '../app/views/include/footer.php';
  ?>
