<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit31451a570693d0205db4a4af5fb7b12e
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
    );

    public static $classMap = array (
        'App\\Controllers\\AdminController' => __DIR__ . '/../..' . '/app/controllers/AdminController.php',
        'App\\Controllers\\CategoryController' => __DIR__ . '/../..' . '/app/controllers/CategoryController.php',
        'App\\Controllers\\CommentController' => __DIR__ . '/../..' . '/app/controllers/CommentController.php',
        'App\\Controllers\\PostController' => __DIR__ . '/../..' . '/app/controllers/PostController.php',
        'App\\Controllers\\TagController' => __DIR__ . '/../..' . '/app/controllers/TagController.php',
        'App\\Controllers\\UserController' => __DIR__ . '/../..' . '/app/controllers/UserController.php',
        'App\\Core\\Controller' => __DIR__ . '/../..' . '/app/core/Controller.php',
        'App\\Core\\DBInterface' => __DIR__ . '/../..' . '/app/core/DBInterface.php',
        'App\\Core\\Data' => __DIR__ . '/../..' . '/app/core/Data.php',
        'App\\Core\\Message' => __DIR__ . '/../..' . '/app/core/Message.php',
        'App\\Core\\Router' => __DIR__ . '/../..' . '/app/core/Router.php',
        'App\\Core\\Token' => __DIR__ . '/../..' . '/app/core/Token.php',
        'App\\Core\\View' => __DIR__ . '/../..' . '/app/core/View.php',
        'App\\Database\\Database' => __DIR__ . '/../..' . '/app/database/Database.php',
        'App\\Models\\Admin' => __DIR__ . '/../..' . '/app/models/Admin.php',
        'App\\Models\\Category' => __DIR__ . '/../..' . '/app/models/Category.php',
        'App\\Models\\Comment' => __DIR__ . '/../..' . '/app/models/Comment.php',
        'App\\Models\\Post' => __DIR__ . '/../..' . '/app/models/Post.php',
        'App\\Models\\Tag' => __DIR__ . '/../..' . '/app/models/Tag.php',
        'App\\Models\\User' => __DIR__ . '/../..' . '/app/models/User.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit31451a570693d0205db4a4af5fb7b12e::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit31451a570693d0205db4a4af5fb7b12e::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit31451a570693d0205db4a4af5fb7b12e::$classMap;

        }, null, ClassLoader::class);
    }
}