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
        $this->render('document.document');
    }


    public function ControlSheet()
    {
        $matrix = $this->get('matrix');
        Document::generateControlSheet($matrix);
        $this->route('/document');
    }

    public function TransferSheet()
    {
        $matrix = $this->get('matrix');
        Document::generateTransferSheet($matrix);
        $this->route('/document');
    }

    public function FolderSheet()
    {
        $matrix = $this->get('matrix');
        Document::generateFileLabels($matrix);
        $this->route('/document');
    }

    public function BoxSheet()
    {
    }


    // public function manageDocument(array $vars)
    // {
    //     // $sheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($vars['document']['tmp_name']);
    //     $matrix = [];
    //     $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($vars['document']['tmp_name']);
    //     $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
    //     $sheets = $reader->load($vars['document']['tmp_name']);
    //     $sheet = $sheets->getSheet(0);
    //     $highestRow = $sheet->getHighestRow();

    //     for ($row = 2; $row <= $highestRow; $row++) {

    //         $cellCaja = $sheet->getCell('G' . $row);
    //         $caja = $cellCaja->getValue();

    //         $cellCarpeta = $sheet->getCell('H' . $row);
    //         $carpeta = $cellCarpeta->getValue();

    //         $cellCausacion = $sheet->getCell('D' . $row);
    //         $causacion = $cellCausacion->getValue();

    //         $cellFolio = $sheet->getCell('J' . $row);
    //         $folio = $cellFolio->getValue();

    //         $cellFecha = $sheet->getCell('B' . $row);
    //         $fecha = $cellFecha->getValue();

    //         if ($causacion != '' && $folio != '' && $caja != '' && $carpeta != '' && $fecha != '') {
    //             $matrix[] = [
    //                 'caja' => $caja,
    //                 'carpeta' => $carpeta,
    //                 'causaciones' => $causacion,
    //                 'folios' => $folio,
    //                 'fecha' => $fecha
    //             ];
    //         }
    //     }

    //     $this->render('document.document');
    //     // $this->generateFileLabels($matrix);
    // }
}
