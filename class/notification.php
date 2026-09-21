<?php

class Notification extends Db_object
{
    public static $db_name = 'notifications';
    public static $db_fields = array('user_id', 'type', 'notifiable_type', 'notifiable_id', 'data', 'read_at');

    public $id;
    public $user_id;
    public $type;
    public $notifiable_type;
    public $notifiable_id;
    public $data;
    public $read_at;
    public function __construct($notifiable_type = null, $notifiable_id = null)
    {
        if ($notifiable_type !== null) {
            $this->notifiable_type = $notifiable_type;
        }
        if ($notifiable_id !== null) {
            $this->notifiable_id = $notifiable_id;
        }
    }
    public function notifyType($type)
    {
        $this->type = $type;
    }
    public function notifyData($data)
    {
        $this->data = $data;
    }
    public function create_notification()
    {
        if (!empty($this->data) && !empty($this->type)) {
            return $this->create();
        }
        return false;
    }
    public static function markAsAllRead()
    {
        global $database;
        $sql = "UPDATE " . static::$db_name . " SET ";
        $sql .= "read_at= :read_at";
        $sql .= " WHERE read_at IS NULL OR read_at = '0000-00-00 00:00:00'";
        $stmt = $database->prepare($sql, [':read_at' => date("Y-m-d H:i:s")]);
        return $stmt->rowCount();
    }
    public static function markAsRead($id)
    {
        global $database;
        $id = (int) $id;
        $sql = "UPDATE " . static::$db_name . " SET ";
        $sql .= " read_at= :read_at";
        $sql .= " WHERE id= :id";
        $stmt = $database->prepare($sql, [
            ":read_at" =>  date("Y-m-d H:i:s"),
            ":id" => $id
        ]);
        return $stmt->rowCount();
    }
    public function unreadNotifications($notifiable_type)
    {
        $sql = "SELECT * FROM " . static::$db_name;
        $sql .= " WHERE read_at IS NULL";
        $sql .= " AND notifiable_type=:notifiable_type";
        $result = self::find_by_query($sql, [':notifiable_type' => $notifiable_type]);
        return $result;
    }
    public function readNotification($notifiable_type)
    {
        $sql = "SELECT * FROM " . static::$db_name;
        $sql .= " WHERE read_at IS NOT NULL";
        $sql .= " AND notifiable_type=:notifiable_type";
        $result = self::find_by_query($sql, [':notifiable_type' => $notifiable_type]);
        return $result;
    }
    public static function getNotificationForOwnerUser($user_id)
    {
        $sql = "SELECT * FROM " . static::$db_name . " WHERE user_id=:user_id";
        $result = self::find_by_query($sql, [":user_id" => $user_id]);
        return $result;
    }
}
