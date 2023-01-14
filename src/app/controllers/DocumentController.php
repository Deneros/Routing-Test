<?php

namespace Acris\App\Controllers;

use Acris\App\Libs\Controller;
use \PhpOffice\PhpSpreadsheet\IOFactory;

class DocumentController extends Controller
{
    public function manageDocument(array $vars)
    {
        // $sheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($vars['document']['tmp_name']);
        $matrix = [];
        $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($vars['document']['tmp_name']);
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
        $sheets = $reader->load($vars['document']['tmp_name']);
        $sheet = $sheets->getSheet(0);
        $highestRow = $sheet->getHighestRow();

        for ($row = 2; $row <= $highestRow; $row++) {

            $cellCaja = $sheet->getCell('G' . $row);
            $caja = $cellCaja->getValue();

            $cellCarpeta = $sheet->getCell('H' . $row);
            $carpeta = $cellCarpeta->getValue();

            $cellCausacion = $sheet->getCell('D' . $row);
            $causacion = $cellCausacion->getValue();

            $cellFolio = $sheet->getCell('J' . $row);
            $folio = $cellFolio->getValue();

            $cellFecha = $sheet->getCell('B' . $row);
            $fecha = $cellFecha->getValue();

            if ($causacion != '' && $folio != '' && $caja != '' && $carpeta != '' && $fecha != '') {
                $matrix[] = [
                    'caja' => $caja,
                    'carpeta' => $carpeta,
                    'causaciones' => $causacion,
                    'folios' => $folio,
                    'fecha' => $fecha
                ];
            }
        }

        $this->render('document.document');
        // $this->generateFileLabels($matrix);
    }

    public function generateControlSheet(array $matrix)
    {
        list($caja, $carpeta, $fila, $contador) = [0, 0, 1, 1];
        $excel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $hoja = $excel->getSheet(0);


        foreach ($matrix as $datos) {


            if ($caja != $datos['caja']) {

                $hoja->setTitle("Caja {$caja}");
                $hoja = $excel->createSheet();
                $excel->setActiveSheetIndex($excel->getIndex($hoja));

                $fila = 1;
                $contador = 1;
                $carpeta = ((int)$datos['carpeta'] - 1);
            }
            if ($carpeta != $datos['carpeta']) {
                $hoja->setCellValue('A' . $fila, "Carpeta {$datos['carpeta']}");
                $hoja->insertNewRowBefore($fila + 1, 1);

                $fila++;
                $carpeta = $datos['carpeta'];
            }
            $hoja->setCellValue('A' . $fila, $fila);
            $hoja->setCellValue('B' . $fila, 'Causacion ' . $datos['causaciones']);
            $hoja->setCellValue('C' . $fila, 'Folios ' . $contador . ' a ' .  ((int)$datos['folios'] + $contador - 1));
            $fila++;
            $contador = $contador + ((int) $datos['folios']);
            $caja = $datos['caja'];
        }
        $excel->removeSheetByIndex(0);
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($excel);
        $writer->save('ControlSheet.xlsx');
    }

    public function generateTransferSheet(array $matrix)
    {
        list($caja, $carpeta, $fila, $folio, $causacion_inicial, $causacion_final, $fecha_inicial, $fecha_final) = [$matrix[0]['caja'], $matrix[0]['carpeta'], 1, 0, $matrix[0]['causaciones'], 0, $matrix[0]['fecha'], ''];
        $excel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $hoja = $excel->getSheet(0);

        foreach ($matrix as $key => $datos) {

            $folio = $folio + (int)$datos['folios'];

            if ($caja != $datos['caja']) {
                $caja = $datos['caja'];
            }
            if ($carpeta != $datos['carpeta'] || $matrix[$key]['carpeta'] = '') {
                $folio = $folio - ((int)$datos['folios']);

                $causacion_final = $matrix[$key - 1]['causaciones'];
                $fecha_final = $matrix[$key - 1]['fecha'];
                $hoja->setCellValue('A' . $fila, "Causaciones: {$causacion_inicial} • {$causacion_final}");
                $hoja->setCellValue('B' . $fila, $fecha_inicial);
                $hoja->setCellValue('C' . $fila, $fecha_final);
                $hoja->setCellValue('D' . $fila, $carpeta);
                $hoja->setCellValue('E' . $fila, $folio);
                $hoja->setCellValue('F' . $fila, $caja);
                $fila++;
                $folio = (int)$datos['folios'];
                $carpeta = $datos['carpeta'];
                $fecha_inicial = $datos['fecha'];
                $causacion_inicial = $datos['causaciones'];
            }
            if ($key == count($matrix) - 1) {
                $causacion_final = $datos['causaciones'];
                $fecha_final = $datos['fecha'];
                $hoja->setCellValue('A' . $fila, "Causaciones: {$causacion_inicial} • {$causacion_final}");
                $hoja->setCellValue('B' . $fila, $fecha_inicial);
                $hoja->setCellValue('C' . $fila, $fecha_final);
                $hoja->setCellValue('D' . $fila, $carpeta);
                $hoja->setCellValue('E' . $fila, $folio);
                $hoja->setCellValue('F' . $fila, $caja);
                $fila++;
            }
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($excel);
        $writer->save('TransferSheet.xlsx');
    }

    public function generateFileLabels(array $matrix)
    {
        list($carpeta, $fila, $folio, $causacion_inicial, $causacion_final, $fecha_inicial, $fecha_final) = [$matrix[0]['carpeta'], 1, 0, $matrix[0]['causaciones'], 0, $matrix[0]['fecha'], ''];
        $excel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $hoja = $excel->getSheet(0);

        $fecha = new \DateTime();


        foreach ($matrix as $key => $datos) {

            // var_dump(date('d/m/Y',$fecha_inicial));
            $fecha->setISODate(1900, 1, (int)$fecha_inicial);
            $año = explode('/',$fecha->format('d/m/Y'))[2];     
            // var_dump($año);       

            $folio = $folio + (int)$datos['folios'];

            if ($carpeta != $datos['carpeta']) {
                $folio = $folio - ((int)$datos['folios']);

                $causacion_final = $matrix[$key - 1]['causaciones'];
                $fecha_final = $matrix[$key - 1]['fecha'];
                $hoja->setCellValue('A' . $fila, $causacion_inicial);
                $hoja->setCellValue('B' . $fila, $causacion_final);
                $hoja->setCellValue('C' . $fila, $fecha_inicial);
                $hoja->setCellValue('D' . $fila, $fecha_final);
                $hoja->setCellValue('E' . $fila, $folio);
                $hoja->setCellValue('F' . $fila, $año);

                $fila++;
                $folio = (int)$datos['folios'];
                $carpeta = $datos['carpeta'];
                $fecha_inicial = $datos['fecha'];
                $causacion_inicial = $datos['causaciones'];
            }
            if ($key == count($matrix) - 1) {
                $causacion_final = $datos['causaciones'];
                $fecha_final = $datos['fecha'];
                $hoja->setCellValue('A' . $fila, $causacion_inicial);
                $hoja->setCellValue('B' . $fila, $causacion_final);
                $hoja->setCellValue('C' . $fila, $fecha_inicial);
                $hoja->setCellValue('D' . $fila, $fecha_final);
                $hoja->setCellValue('E' . $fila, $folio);
                $hoja->setCellValue('F' . $fila, $año);
                $fila++;
            }
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($excel);
        $writer->save('FileSheet.xlsx');
    }

    public function generateBoxLabels()
    {
    }
}
