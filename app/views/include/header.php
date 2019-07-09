<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo (isset($this->data['page'])) ? 'Blog | ' . $this->data['page'] : 'Blog' ?></title>
    <?php if (isset($this->data['page']) && $this->data['page'] == 'Individual') { ?>
      <meta name="author" content="<?php echo $this->data['article'][0]['author'] ?>">
      <meta name="category" content="<?php echo $this->data['article'][0]['category'] ?>">
      <meta name="date" content="<?php echo $this->data['article'][0]['created_at'] ?>">
    <?php } ?>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>
  <style media="screen">
    .active{
      background: #ece5e5;
    }
  </style>
  <body>

    <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm">
      <h5 class="my-0 mr-md-auto font-weight-normal">
        <?php if (isset($_SESSION['user'])){
          echo $_SESSION['user'];
       } else {
         echo 'Blog';
       } ?>
      </h5>
      <nav class="my-2 my-md-0 mr-md-3">
        <?php if (isset($_SESSION['user'])){ ?>
          <?php if (isset($_SESSION['admin'])) { ?>
            <a  href="/admin/index" class="p-2 text-dark">Admin</a>
          <?php } ?>
          <a  href="/post/index" class="p-2 text-dark">Home</a>
          <a  href="/post/createpost" class="p-2 text-dark">Create Post</a>
          <a  href="/user/logout" class="p-2 text-dark">Logout</a>
         <?php } else { ?>
           <a class="p-2 text-dark <?php echo ($this->data['page'] == 'LogIn') ? 'active':'' ?>" href="/user/login">Login</a>
           <a class="p-2 text-dark <?php echo ($this->data['page'] == 'Register') ? 'active':'' ?>" href="/user/register">Register</a>
         <?php } ?>
      </nav>
    </div>
