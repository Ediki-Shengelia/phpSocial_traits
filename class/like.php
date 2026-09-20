<?php

class Like extends Db_object
{
    public static $db_name = 'likes';
    public static $db_fields = array('user_id', 'post_id');

    public $id;
    public $user_id;
    public $post_id;
    public $total;
    public static function count_by_post_id($post_id)
    {
        $sql = "SELECT COUNT(*) AS total FROM " . static::$db_name . " WHERE post_id=:post_id";
        $result = static::find_by_query($sql, [":post_id" => $post_id]);
        return $result ? (int)array_shift($result)->total : 0;
    }
    public static function find_by_user_post($user_id, $post_id)
    {
        $sql = "SELECT * FROM " . static::$db_name . " WHERE user_id = :user_id AND post_id = :post_id LIMIT 1";
        $result = static::find_by_query($sql, [
            ":user_id" => $user_id,
            ":post_id" => $post_id,
        ]);

        return !empty($result) ? array_shift($result) : false;
    }
}
