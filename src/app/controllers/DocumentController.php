<?php

namespace Acris\App\Controllers;

use Acris\App\Libs\Controller;
use Acris\App\Models\Document;

class DocumentController extends Controller
{
    public function setInfo(array $vars){
        $matrix = Document::bringInfo($vars);
        $this->set('matrix', $matrix);
        $this->route('/document');
    }

    public function index()
    {
        if(empty($this->get('matrix'))){
            $this->route('/');
            exit;
        }
        $this->render('document.document');
    }


    public function ControlSheet()
    {
        if(empty($this->get('matrix'))){
            $this->route('/');
            exit;
        }
        $matrix = $this->get('matrix');
        Document::generateControlSheet($matrix);
        $this->route('/document');
    }

    public function TransferSheet()
    {
        if(empty($this->get('matrix'))){
            $this->route('/');
            exit;
        }
        $matrix = $this->get('matrix');
        Document::generateTransferSheet($matrix);
        $this->route('/document');
    }

    public function FolderSheet()
    {
        if(empty($this->get('matrix'))){
            $this->route('/');
            exit;
        }
        $matrix = $this->get('matrix');
        Document::generateFileLabels($matrix);
        $this->route('/document');
    }

    public function BoxSheet()
    {
        if(empty($this->get('matrix'))){
            $this->route('/');
            exit;
        }
        $matrix = $this->get('matrix');
        Document::generateBoxLabels($matrix);
        $this->route('/document');
    }

}
