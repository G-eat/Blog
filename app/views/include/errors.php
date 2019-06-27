<div class="container">
  <?php if ($this->data['error'] >0) {
    foreach ($this->data['error'] as $error) {
      echo '<p class="alert alert-danger">' . $error . '</p>';
    }
  } ?>
</div>
