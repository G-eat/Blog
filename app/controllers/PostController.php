<?php
/**
 * Post
 */
class PostController extends Controller {

     public function __construct() {
        User::isSetRemmember_me();

        // $this->model = 'PostController';
        // $request = trim( $_SERVER['REQUEST_URI'],'/' );
        // $url = explode( '/',$request );
        // $this->params = $url[1];
        // Controller::create($this->model,$this->params);
     }

    public function index($order = '' , $id = 1) {
      if (isset($_POST['created_at'])) {
         Controller::redirect('/post/index/created_at');
      }

      if ($order === '') {
        Controller::redirect('/post/index/position');
      }

      if ($order === 'created_at') {
         $order = 'created_at';
         $by = 'DESC';
      } else {
         $order = 'position';
         $by = 'ASC';
      }

      $categories = Post::getAll('categories');
      $limit_from = Post::limitFrom($id);
      $articles = Post::getArticles($order,$by,$limit_from,'5');
      $nr_page = Post::nrPageOfArticle();
      $error = Post::returnError($articles);

      $this->view('post\index',[
        'categories' => $categories,
        'articles' => $articles,
        'error' => $error,
        'nr_page' => $nr_page,
        'page_current' => $id,
        'order' => $order
      ]);
      $this->view->render();
    }

    public function createpost() {
      if (isset($_POST['submit'])) {
        $slug = "'".Post::slug($_POST['slug'])."'";
        $data = Post::seeIfArticleSlugExist($slug);

        if ($data[0] == 1) {
          $errrors = 'This slug is not available.';
          $categories = Post::getAll('categories');
          $tags = Post::getAll('tags');

          $this->view('post\createpost',[
            'categories' => $categories,
            'tags' => $tags,
            'errrors' => $errrors,
            'title' => $_POST['title'],
            'category' => $_POST['category'],
            'slug' => Post::slug($_POST['slug']),
            'body' => $_POST['body-editor1'],
            'page' => 'CreatePost'
          ]);
          $this->view->render();
        } else {
          $image = Post::uploadPhoto($_FILES['image']['name']);
          Post::insertTag($_POST['tags'],$slug);
          Post::insertArticles($_SESSION['user'],$_POST['title'],$_POST['body-editor1'],$slug,$_POST['category'],$image);
          // $post = new Controller;
          // $post->create('post',[$_SESSION['user'],$_POST['title'],$_POST['body-editor1'],$slug,$_POST['category'],$image]);

          Controller::redirect('/post/index');
        }
      } else {
        $categories = Post::getAll('categories');
        $tags = Post::getAll('tags');

        $this->view('post\createpost',[
          'categories' => $categories,
          'tags' => $tags,
          'page' => 'CreatePost'
        ]);
        $this->view->render();
      }
    }

    public function individual($slug) {
      $article = Post::getArticleWithThisSlug($slug);
      if ($article == null) {
          Controller::redirect('/post/index');
      }
      Post::seeIfArticleIsPublished($slug,$article);

      $author_articles = Post::articleAuthor($article[0]['author']);
      $tags = Post::tagsWithSameSlug($slug);
      $comments = Post::commentAccepted($article[0]['id']);

      $this->view('post\individual',[
        'article' => $article,
        'page' => 'Individual',
        'tags' => $tags,
        'comments' => $comments,
        'author_articles' => $author_articles
      ]);
      $this->view->render();
    }

    public function user($name , $id = 1) {
        $limit_from = Post::limitFrom($id);

        if (isset($_SESSION['user']) && $name == $_SESSION['user']) {
            $articles = Post::getArticlesWithThisAuthor($name,$limit_from);
            $nr_page = Post::nrPageOfArticleWithThisAuthor($name);
        } else {
            $articles = Post::getArticlesWithThisAuthorPublished($name,$limit_from);
            $nr_page = Post::nrPageOfArticleWithThisAuthorPublished($name);
        }

        if ($id > 1 && $id > $nr_page) {
            Controller::redirect('/post/user/'.$name);
        }

        $this->view('post\user',[
          'articles' => $articles,
          'author' => $name,
          'nr_page' => $nr_page,
          'page_current' => $id
        ]);
        $this->view->render();
    }

    public function category($category) {
      $articles = Post::getArticlesWithThisCategoryPublished($category);
      $categories = Post::getAll('categories');

      if (count($articles)) {
          $category_articles = Post::category_articles($articles[0]['category']);

          $this->view('post\category',[
            'articles' => $articles,
            'category_articles' => $category_articles,
            'category' => $category,
            'categories' => $categories
          ]);
          $this->view->render();
      } else {
          $this->view('post\category',[
              'articles' => $articles,
              'category' => $category,
              'categories' => $categories
          ]);
          $this->view->render();
      }
    }

    public function search($search='', $id = '') {
        if ($search !== '' && !isset($_POST['search'])) {
            $search = $search;
        } elseif (!isset($_POST['search']) || $_POST['search'] == '') {
            Controller::redirect('/post/index');
        } else {
            $search = $_POST['search'];
            Controller::redirect('/post/search/'.$search.'/1');
        }

        $limit_from = Post::limitFrom($id);
        $articles = Post::getArticlesWhereTitleLike($search,$limit_from);
        $nr_page = Post::getNrPageWhereTitleLike($search);

        if ($id > $nr_page && $nr_page > 0) {
            Controller::redirect('/post/search/'.$search.'/1');
        }

        $this->view('post\search',[
            'articles' => $articles,
            'search' => $search,
            'nr_page' => $nr_page,
            'page_current' => $id,
        ]);
        $this->view->render();
    }

    public function tag($value='') {
        $tag = '#'.$value;
        $articles_tag = Post::getArticlesTag($tag);


        foreach ($articles_tag as $article_tag) {
            $article_id = Post::getArticlesId($article_tag['article_slug']);
            $article_id == null ? '' : $articles_id[] = $article_id;
        }

        $articles = array();
        if (isset($articles_id)) {
            for ($i = 0; $i < count($articles_id); $i++) {
                $data = Post::getArticlesWithThisTag($articles_id[$i][0]['id']);
                $articles[] = $data;
            }
        }

        $categories = Post::getAll('categories');

        $this->view('post\tag',[
            'articles' => $articles,
            'categories' => $categories,
            'tag' => $tag
        ]);
        $this->view->render();
    }

}

?>
