    <div class="container">
      <footer class="my-5 pt-5 text-muted text-center text-small bottom">
        <p class="mb-1">&copy; 2017-2019 Blog</p>
        <ul class="list-inline">
          <li class="list-inline-item"><a href="#">Privacy</a></li>
          <li class="list-inline-item"><a href="#">Terms</a></li>
          <li class="list-inline-item"><a href="#">Support</a></li>
        </ul>
      </footer>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script type="text/javascript">
        $(function() {
          var txt = $("input#noSpaces");
          var func = function() {
                       txt.val(txt.val().replace(/\s/g, ''));
                    }
          txt.keyup(func).blur(func);
        });

        // drag and drop
        $('#sortable').sortable({
          update: function (event,ui) {
            let positions = $("#sortable").sortable("toArray");

            $.ajax({
                     url: "position",
                     method:'POST',
                     data:{positions:positions},
                     dataType:'JSON',
                     success: function(result){
                     }
            });
          }
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            $(".open-AddBookDialog").click(function () {
                $("#id").val( $(this).data('id') );
                $("#slug").val( $(this).data('slug') );
                $("#comment").val( $(this).data('comment') );
            });
        });
    </script>
    <?php if (isset($this->data['page']) && $this->data['page'] == 'CreatePost'): ?>
        <script src="https://cdn.ckeditor.com/4.12.1/full-all/ckeditor.js"></script>
        <script>
            CKEDITOR.replace( 'body-editor1' );
        </script>
    <?php endif; ?>
  </body>
</html>
