<?php 
namespace Acris\App\Controllers;

use Acris\App\Libs\Controller;

class HomeController extends Controller
{
    public function index(){
        $this->render('home.home');
    }
    
}
