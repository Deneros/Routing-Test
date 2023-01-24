<?php namespace Acris\App\Libs;

class Session
{
    private bool $flash = false;

    public function __construct()
    {   
        session_start();
    }

    public function get($key){

        return ($_SESSION[$key]) ?? false;
    }

    public function set($key, $data){
        $_SESSION[$key]=$data;
    }

    public function flash($data){
        $this->flash=true;
        $this->set('flash', $data);
    }


}
