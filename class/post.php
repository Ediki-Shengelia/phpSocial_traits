<?php

require_once __DIR__ . '/traits/fileUploading.php';
class Post extends Db_object
{
    use FileUploading;
    protected function upload_directory(): string
    {
        return "posts";
    }

    public static $db_name = 'posts';
    public static $db_fields = array('user_id', 'title', 'content', 'image');
    public $id;
    public $user_id;
    public $title;
    public $content;
}
