<?php

namespace Acris\App\Libs;

class Controller
{
    protected function render(string $path, array $data = []): void
    {
        extract($data);

        if (str_contains($path, '.')) {
            $path = str_replace('.', '/', $path);
            // var_dump(__DIR__ . '/../../views/' . $path . '.phtml');
            // die();

            if ($this->verifyPath($path)) {
                require_once __DIR__ . '/../../views/' . $path . '.phtml';
            } 
        } else {
            if ($this->verifyPath($path)) {
                require_once __DIR__ . '/../../views/' . $path . '.phtml';
            } 
        }

        
    }

    private function verifyPath($path): bool
    {
        if(file_exists(__DIR__.'/../../views/' . $path . '.phtml')){
            return true;
        }else {
            throw new \Exception("No existe la vista que desea renderizar", 1);
            return false;
        }
    }
}
