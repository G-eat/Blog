<?php

  use App\Core\Data;

  $data = new Data;

  include '../app/views/include/header.php';
  include '../app/views/include/messages.php';

  if (isset($_SESSION['user'])) {

    if (isset($this->data['errrors'])) {
      echo '<h5 class="alert alert-danger container">'.$this->data['errrors'].'</h5>';
    }

    if (!isset($_SESSION['user'])) {
        Controller::redirect('/post/index');
    }

    include '../app/views/include/messages.php';
?>
  <div class="container mt-3">
    <div class="border border-secondary p-5">
      <h5 class="mb-4">Create new post.</h5>
      <form action="/post/create" method="post" enctype="multipart/form-data">
        <div class="row">
          <div class="form-group col-6">
            <label class="text-info" for="exampleInputTitle">Title :</label>
            <input type="text" class="form-control" aria-describedby="titleHelp" name="title" placeholder="Post title" value="<?php echo isset($_SESSION['title']) ? $data->display('title') :'' ?>" required minlength=3>
            <?php
                if (isset($_SESSION['title'])) {
                    unset($_SESSION['title']);
                }
            ?>
          </div>
          <div class="form-group col-6">
            <label class="text-info" for="exampleInputSlug">Slug :</label>
            <input type="text" class="form-control" aria-describedby="slugHelp" name="slug" placeholder="Post slug" value="<?php echo isset($_SESSION['slug']) ? $data->display('slug') :'' ?>" required minlength=3>
          </div>
        </div>
        <div class="form-group">
          <label class="text-info" for="exampleFormControlTextarea1">Body :</label>
          <textarea class="form-control" id="exampleFormControlTextarea1" name="body-editor1" rows="5" placeholder="Post body"><?php echo isset($_SESSION['body']) ? $data->display('body') :'' ?></textarea>
        </div>
        <div class="form-group">
          <label class="text-info" for="exampleFormControlSelect1">Category :</label>
          <select class="form-control" id="exampleFormControlSelect1" name="category" required>
            <?php foreach ($this->data['categories'] as $category) { ?>
              <option <?php echo (isset($_SESSION['category']) && $_SESSION['category'] == $category['name']) ? 'selected' :'' ?> value="<?php echo $category['name'] ?>"><?php echo $category['name'] ?></option>
            <?php } ?>
            <?php unset($_SESSION['category']); ?>
          </select>
        </div>
        <div class="form-group">
          <label class="text-info" for="exampleFormControlSelect2">Tags :</label>
          <select class="form-control" id="exampleFormControlSelect2" name="tags[]" multiple required>
            <?php foreach ($this->data['tags'] as $tag) { ?>
              <option value="<?php echo $tag['name'] ?>"><?php echo $tag['name'] ?></option>
            <?php } ?>
          </select>
        </div>
        <div class="form-group">
          <input type="file" name="image" class="form-control-file" id="exampleFormControlFile1" value="a" placeholder="2" required>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Create</button>
      </form>
    </div>
  </div>
<?php
  } else {
    header("Location: /post/index");
  }
?>

<?php
   include '../app/views/include/footer.php';
 ?>
