<?php

class Comment extends Db_object
{
    public static $db_name = 'comments';
    public static $db_fields = array('user_id', 'post_id', 'comment');

    public $id;
    public $user_id;
    public $post_id;
    public $comment;
    public static function create_comment($post_id, $comment)
    {
        global $session;
        if (!empty($post_id) && !empty($comment)) {
            $comm = new Comment();
            $comm->user_id = $session->getUserId();
            $comm->post_id = $post_id;
            $comm->comment = $comment;
            if ($comm->save()) {
                return $comm;
            }
        }
        return false;
    }
    public static function find_by_post_id($id)
    {
        $sql = "SELECT * FROM " . static::$db_name . " WHERE post_id = :post_id ORDER BY id ASC";
        $result = self::find_by_query($sql, [':post_id' => $id]);
        return $result ?: [];
    }
}
