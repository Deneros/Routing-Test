<?php

namespace Acris\App\Libs;

use Acris\App\Libs\Store;

class Redirect extends Store
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
