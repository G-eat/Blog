<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Blog | Confirmation Email</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>
  <body>
    <div class="container mt-5">
      <h3>Confirmation Email</h3><br>
      <form action="/user/confirmationemail" method="post">
        <div class="form-group">
          <label class="text-info" for="exampleInputUsername1">Username :</label>
          <input type="text" class="form-control" id="noSpaces" aria-describedby="usernameHelp" name="username" value="<?php echo isset($this->data['username']) ? $this->data['username']:'' ?>" placeholder="Username" onkeyup="this.value = this.value.toLowerCase();" autocapitalize="none" required minlength=8>
          <!-- <small id="usernameHelp" class="form-text text-muted">Must have 8-50 characters.</small> -->
        </div>
        <div class="form-group">
          <label class="text-info" for="exampleInputPassword1">Password :</label>
          <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" name='password' required minlength=8 maxlength=20>
          <small id="passwordHelp" class="form-text text-muted">8-20 Characters long.</small>
        </div>
        <input type="hidden" name="token" value="<?php echo $this->data['token'] ?>">
        <button type="submit" name="submit" class="btn btn-primary">Register</button>
      </form>
    </div>

<?php  include '../app/views/include/footer.php'; ?>
