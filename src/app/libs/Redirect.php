<?php

namespace Acris\App\Libs;

use Acris\App\Libs\Session;

class Redirect extends Session
{
    public function route($route)
    {
        header('location: '.$route);
        return $this;
    }

    protected function with($data)
    {
        $this->flash($data);
        return $this;
    }
}
