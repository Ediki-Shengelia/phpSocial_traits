<?php

class Session
{
    public $user_id;
    public $signed_in = false;
    public function __construct()
    {
        session_start();
        $this->check_the_login();
    }
    public function login($user)
    {
        if ($user) {
            $this->user_id = $_SESSION['user_id'] = $user->id;
            $this->signed_in = true;
        }
    }
    public function check_the_login()
    {
        if (isset($_SESSION['user_id'])) {
            $this->signed_in = true;
            $this->user_id = $_SESSION['user_id'];
        } else {
            $this->signed_in = false;
            unset($this->user_id);
        }
    }
    public function getUserId()
    {
        return $_SESSION['user_id'];
    }
    public function is_signed_in()
    {
        return $this->signed_in;
    }
}
$session = new Session();
