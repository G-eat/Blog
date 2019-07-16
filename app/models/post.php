<?php
/**
 * articles/post
 */
class Post {

    public function slug($text='') {
      // replace non letter or digits by -
      $text = preg_replace('~[^\pL\d]+~u', '-', $text);
      // transliterate
      $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
      // remove unwanted characters
      $text = preg_replace('~[^-\w]+~', '', $text);
      // trim
      $text = trim($text, '-');
      // remove duplicate -
      $text = preg_replace('~-+~', '-', $text);
      // lowercase
      $text = strtolower($text);

      if (empty($text)) {
        return 'n-a';
      }

      return $text;
    }

    public function getArticles($order,$by,$limit_from,$to) {
        return Database::select(['*'],['articles'],[['is_published','=','"Publish"']],null,[$order,$by],[$limit_from,$to]);
    }

    public function nrPageOfArticle() {
        $all_articles = Database::select(['*'],['articles'],[['is_published','=','"Publish"']]);
        return ceil(count($all_articles)/5);
    }

    public function returnError($articles) {
        if ($articles == null) {
           return 'Error! There are no link like this.';
        } else {
           return '';
        }
    }

    public function limitFrom($id) {
        if ($id == '' || $id == 1) {
           return 0;
        } else {
           return ($id - 1) * 5;
        }
    }

    public function seeIfArticleSlugExist($slug) {
        $mysql = 'SELECT COUNT(*) FROM `articles` WHERE `slug` = '.$slug;
        return Database::raw($mysql);
    }

    public function uploadPhoto($image) {
        $image = uniqid('', true) . '-' .$_FILES['image']['name'];
        $file_destination = '.\postPhoto\\'.$image;
        move_uploaded_file($_FILES['image']['tmp_name'],$file_destination);
        return $image;
    }

    public function insertTag($tags,$slug) {
        foreach ($tags as $tag) {
           Database::insert(['articles_tag'],['tag_name','article_slug'],["'".$tag."'",$slug]);
        }
    }

    public function insertArticles($author,$title,$body,$slug,$category,$image) {
        Database::insert(['articles'],['author','title','body','slug','category','file_name'],
        ["'".$author."'","'".$title."'","'".$body."'",$slug,"'".$category."'","'".$image."'" ]);
    }
    // public function create($params) {
    //     Database::insert(['articles'],['author','title','body','slug','category','file_name'],
    //     ["'".$params[0]."'","'".$params[1]."'","'".$params[2]."'",$params[3],"'".$params[4]."'","'".$params[5]."'" ]);
    // }

    public function getArticleWithThisSlug($slug) {
        return Database::select(['*'],['articles'],[['slug','=',"'".$slug."'"]]);
    }

    public function seeIfArticleIsPublished($slug,$article) {
        $article_ispublish = Database::select(['*'],['articles'],[['slug','=',"'".$slug."'"],['AND'],['is_published','=','"Publish"']]);

        if (count($article_ispublish) == 0 && $article[0]['author'] !== $_SESSION['user']) {
            Controller::redirect('/post/index');
        }
    }

    public function articleAuthor($author) {
        return Database::select(['*'],['articles'],[['author','=',"'".$author."'"],['AND'],['is_published','=','"Publish"']],null,null,['10']);
    }

    public function tagsWithSameSlug($slug) {
        return Database::select(['*'],['articles_tag'],[['article_slug','=',"'".$slug."'"]]);
    }

    public function commentAccepted($article_id) {
        return Database::select(['*'],['comments'],[['article_id','=',"'".$article_id."'"],['AND'],['accepted','=','"Accepted"']]);
    }

    public function getArticlesWithThisAuthor($name,$limit_from) {
        return Database::select(['*'],['articles'],[['author','=',"'".$name."'"]],null,null,[$limit_from,'5']);
    }

    public function nrPageOfArticleWithThisAuthor($name) {
        $all_articles = Database::select(['*'],['articles'],[['author','=',"'".$name."'"]]);
        return ceil(count($all_articles)/5);
    }

    public function getArticlesWithThisAuthorPublished($name,$limit_from) {
        return Database::select(['*'],['articles'],[['author','=',"'".$name."'"],['AND'],['is_published','=','"Publish"']],null,null,[$limit_from,'5']);
    }

    public function nrPageOfArticleWithThisAuthorPublished($name) {
        $all_articles = Database::select(['*'],['articles'],[['author','=',"'".$name."'"],['AND'],['is_published','=','"Publish"']]);
        return ceil(count($all_articles)/5);
    }

    public function getArticlesWithThisCategoryPublished($category) {
        return Database::select(['*'],['articles'],[['category','=',"'".$category."'"],['AND'],['is_published','=',"'Publish'"]]);
    }

    public function category_articles($articles_category) {
        return Database::select(['*'],['articles'],[['category','=',"'".$articles_category."'"],['AND'],['is_published','=',"'Publish'"]],null,null,['5']);
    }

    public function getArticlesWhereTitleLike($search,$limit_from) {
        return Database::select(['*'],['articles'],[['title','LIKE','"%'.$search.'%"'],['AND'],['is_published','=','"Publish"']],null,null,[$limit_from,'5']);
    }

    public function getNrPageWhereTitleLike($search) {
        $all_articles = Database::select(['*'],['articles'],[['title','LIKE','"%'.$search.'%"'],['AND'],['is_published','=','"Publish"']]);
        return ceil(count($all_articles)/5);
    }

    public function getArticlesTag($tag) {
        return Database::select(['*'],['articles_tag'],[['tag_name','=',"'".$tag."'"]]);
    }

    public function getArticlesId($articles_slug) {
        return Database::select(['id'],['articles'],[['slug','=',"'".$articles_slug."'"],['AND'],['is_published','=','"Publish"']]);
    }

    public function getArticlesWithThisTag($articles_id) {
        return Database::select(['*'],['articles'],[['id'.'=',"'".$articles_id."'"]]);
    }

    public function getElement($articles_id) {
        return Database::select(['*'],['articles'],[['id'.'=',"'".$articles_id."'"]]);
    }

    public function create() {
        $slug = "'".Post::slug($_POST['slug'])."'";
        $data = Post::seeIfArticleSlugExist($slug);

        if ($data[0] == 1) {
            Message::setMsg('This slug exist,try different slug.','error');

            Data::setData($_POST['title'],'title');
            Data::setData(Post::slug($_POST['slug']),'slug');
            Data::setData($_POST['body-editor1'],'body');
            Data::setData($_POST['category'],'category');

            // echo "
            //     <script>
            //              window.history.go(-1);
            //      </script>
            //      ";

            // header("location:javascript://history.go(-1)");
        	// exit();
            Controller::redirect('/post/createpost');
        } else {
            $image = Post::uploadPhoto($_FILES['image']['name']);

            Post::insertTag($_POST['tags'],$slug);
            Database::insert(['articles'],['author','title','body','slug','category','file_name'],
            ["'".$_SESSION['user']."'","'".$_POST['title']."'","'".$_POST['body-editor1']."'",$slug,"'".$_POST['category']."'","'".$image."'" ]);

            Message::setMsg('You create the post,now admin need to accept that.','success');

            Controller::redirect('/post/index');
        }
    }

    public function delete() {
        if (isset($_SESSION['user']) && $_POST['author'] == $_SESSION['user']) {
            $id = $_POST['id'];
            $author = $_POST['author'];

            Database::delete(['articles'],[['id','=',"'".$id."'"]]);

            Message::setMsg('You deleted the post.','error');

            Controller::redirect('/post/user/'.$author);
        } else {
            Message::setMsg("Your're not authorized.",'error');

            Controller::redirect('/post/index');
        }

    }
}

?>
