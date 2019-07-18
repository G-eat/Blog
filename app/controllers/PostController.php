<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Database\Database;
use App\Models\User;
use App\Models\Post;

/**
 * Post
 */
class PostController extends Controller {

     public function __construct($params = null) {
        $user = new User();

        $user->isSetRemmember_me();

        $this->params = $params;
        $this->model = 'App\Models\Post';
        parent::__construct($params);
     }

    public function index($order = '' , $id = 1) {
        $database = new Database();
        $post = new Post();

        if (isset($_POST['created_at'])) {
          $this->redirect('/post/index/created_at');
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

        $categories = $database->select(['*'],['categories']);
        $limit_from = $post->limitFrom($id);
        $articles = $post->getArticles($order,$by,$limit_from,'5');
        $nr_page = $post->nrPageOfArticle();
        $error = $post->returnError($articles);

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
        $database = new Database();

        $categories = $database->select(['*'],['categories']);
        $tags = $database->select(['*'],['tags']);

        $this->view('post\createpost',[
            'categories' => $categories,
            'tags' => $tags,
            'page' => 'CreatePost'
        ]);
        $this->view->render();
    }

    public function individual($slug) {
        $post = new Post();

        $article = $post->getArticleWithThisSlug($slug);

        if ($article == null) {
          Controller::redirect('/post/index');
        }

        $post->seeIfArticleIsPublished($slug,$article);
        $author_articles = $post->articleAuthor($article[0]['author']);
        $tags = $post->tagsWithSameSlug($slug);
        $comments = $post->commentAccepted($article[0]['id']);

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
        $post = new Post();

        $limit_from = $post->limitFrom($id);

        if (isset($_SESSION['user']) && $name == $_SESSION['user']) {
            $articles = $post->getArticlesWithThisAuthor($name,$limit_from);
            $nr_page = $post->nrPageOfArticleWithThisAuthor($name);
        } else {
            $articles = $post->getArticlesWithThisAuthorPublished($name,$limit_from);
            $nr_page = $post->nrPageOfArticleWithThisAuthorPublished($name);
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
        $post = new Post();
        $database = new Database();

        $articles = $post->getArticlesWithThisCategoryPublished($category);
        $categories = $database->select(['*'],['categories']);

        if (count($articles)) {
          $category_articles = $post->category_articles($articles[0]['category']);

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
        $post = new Post();

        if ($search !== '' && !isset($_POST['search'])) {
            $search = $search;
        } elseif (!isset($_POST['search']) || $_POST['search'] == '') {
            Controller::redirect('/post/index');
        } else {
            $search = $_POST['search'];
            Controller::redirect('/post/search/'.$search.'/1');
        }

        $limit_from = $post->limitFrom($id);
        $articles = $post->getArticlesWhereTitleLike($search,$limit_from);
        $nr_page = $post->getNrPageWhereTitleLike($search);

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
        $post = new Post();
        $database = new Database();

        $tag = '#'.$value;
        $articles_tag = $post->getArticlesTag($tag);

        foreach ($articles_tag as $article_tag) {
            $article_id = $post->getArticlesId($article_tag['article_slug']);
            $article_id == null ? '' : $articles_id[] = $article_id;
        }

        $articles = array();
        if (isset($articles_id)) {
            for ($i = 0; $i < count($articles_id); $i++) {
                $data = $post->getArticlesWithThisTag($articles_id[$i][0]['id']);
                $articles[] = $data;
            }
        }

        $categories = $database->select(['*'],['categories']);

        $this->view('post\tag',[
            'articles' => $articles,
            'categories' => $categories,
            'tag' => $tag
        ]);
        $this->view->render();
    }

}

?>
