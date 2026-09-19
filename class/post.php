<?php

require_once __DIR__ . '/traits/fileUploading.php';
class Post extends Db_object
{
    use FileUploading;
    public static $db_name = 'posts';
    public static $db_fields = array('user_id', 'title', 'content', 'image');
    public $id;
    public $title;
    public $content;
}
