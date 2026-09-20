<?php

require_once __DIR__ . '/traits/fileUploading.php';
class User extends Db_object
{
    use FileUploading;
    public static $db_name = 'users';
    protected function upload_directory(): string
    {
        return "posts";
    }

    public static $db_fields = array('name', 'email', 'password', 'role', 'image');
    public $id;
    public $name;
    public $email;
    public $password;
    public $role;
    #[Override]
    public function create()
    {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
        return parent::create();
    }
    #[Override]
    public function update()
    {
        if (!empty($this->password)) {
            $this->password = password_hash($this->password, PASSWORD_BCRYPT);
        }
        return parent::update();
    }
    public static function verify_user($email, $password)
    {
        $sql = "SELECT * FROM " . self::$db_name . " WHERE email=:email LIMIT 1";
        $result = self::find_by_query($sql, [':email' => $email]);
        $the_user = array_shift($result);
        if (!$the_user) {
            return false;
        }
        return password_verify($password, $the_user->password) ? $the_user : false;
    }
}
