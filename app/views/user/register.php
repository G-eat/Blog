<?php
  include '../app/views/include/header.php';
  include '../app/views/include/errors.php';

    if (!isset($_SESSION['user'])){
 ?>

 <div class="container mt-4">
   <h3 class="text-primary mb-3">Register</h3>
   <form action="/user/register" method="POST">
     <div class="form-group">
       <label class="text-info" for="exampleInputUsername1">Username :</label>
       <input type="text" class="form-control" id="noSpaces" aria-describedby="usernameHelp" name="username" value="<?php echo isset($this->data['username']) ? $this->data['username']:'' ?>" placeholder="Username" onkeyup="this.value = this.value.toLowerCase();" autocapitalize="none" required minlength=8>
       <!-- <small id="usernameHelp" class="form-text text-muted">Must have 8-50 characters.</small> -->
     </div>
     <div class="form-group">
       <label class="text-info" for="exampleInputEmail1">Email address :</label>
       <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="<?php echo isset($this->data['email']) ? $this->data['email']:'' ?>" placeholder="Enter email" name='email' required>
       <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
     </div>
     <div class="form-group">
       <label class="text-info" for="exampleInputPassword1">Password :</label>
       <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" name='passw' required minlength=8 maxlength=20>
       <small id="passwordHelp" class="form-text text-muted">8-20 Characters long.</small>
     </div>
     <div class="form-group">
       <label class="text-info" for="exampleInputPassword2">Confirm password :</label>
       <input type="password" class="form-control" id="exampleInputPassword2" placeholder="Confirm password" name='conpassw' required minlength=8 maxlength=20>
       <small id="confirmpassHelp" class="form-text text-muted">8-20 Characters long.</small>
     </div>
     <button type="submit" class="btn btn-primary">Register</button>
   </form>
 </div>

 <?php
    include '../app/views/include/footer.php';
  ?>

<?php } else {
  echo '<h5 class="container">You are logged in.</h5>';
} ?>
