<form action="/post/search" method="post">
    <div class="input-group mb-3">
      <input type="text" class="form-control active" placeholder="Search..." value="<?php echo isset($this->data['search']) ? $this->data['search']:'' ?>" name="search" aria-label="Recipient's username" aria-describedby="basic-addon2">
      <div class="input-group-append">
        <button type="submit" name="button">Search</button>
      </div>
    </div>
</form>
