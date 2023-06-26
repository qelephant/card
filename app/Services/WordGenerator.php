<?php

namespace App\Services;

use PhpOffice\PhpWord\Element\Cell;
use PhpOffice\PhpWord\Element\Row;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\PhpWord;


class WordGenerator
{
    protected $templatePath;

    public function __construct($templatePath)
    {
        $this->templatePath = $templatePath;
    }

    public function generate($data, $outputPath)
    {
        $templateProcessor = new TemplateProcessor($this->templatePath);
        $templateProcessor->setValues($data);
        $templateProcessor->saveAs($outputPath);
    }

    // function createAndDownloadWordDocument($data, $filename)
    // {
    //     // Создаем объект PhpWord
    //     $phpWord = new PhpWord();

    //     // Создаем новый документ Word
    //     $document = $phpWord->createDocument();

    //     // Создаем таблицу с определенным количеством строк и столбцов
    //     $table = new Table(count($data), count($data[0]));

    //     // Заполняем таблицу данными из массива
    //     foreach ($data as $rowData) {
    //         $tableRow = new Row();

    //         foreach ($rowData as $cellData) {
    //             $tableCell = new Cell();
    //             $tableCell->addText($cellData);
    //             $tableRow->addCell($tableCell);
    //         }

    //         $table->addRow($tableRow);
    //     }

    //     // Добавляем таблицу в документ Word
    //     $document->addTable($table);

    //     // Сохраняем документ
    //     $tempFilePath = 'path/to/temporary/' . $filename;
    //     $document->save($tempFilePath);

    //     // Отправляем заголовки для скачивания файла
    //     header('Content-Description: File Transfer');
    //     header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
    //     header('Content-Disposition: attachment; filename="' . $filename . '"');
    //     header('Content-Length: ' . filesize($tempFilePath));
    //     header('Pragma: no-cache');
    //     header('Expires: 0');

    //     // Скачиваем файл
    //     readfile($tempFilePath);

    //     // Удаляем временный файл
    //     unlink($tempFilePath);
    // }
}
