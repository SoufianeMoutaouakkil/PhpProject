<?php

namespace Core\File;

use InvalidArgumentException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class Excel
{

    public Spreadsheet $wb;

    public function __construct(string $file = "")
    {
        if ($file ==="") {
            $this->wb = new Spreadsheet;
        } elseif (is_file($file)) {
            $ext = pathinfo($file, PATHINFO_EXTENSION);
            if (in_array($ext, $this->allowedExt())) {
                $readerName = $this->getReaderClassName($ext);
                $reader = new $readerName();
                $this->wb = $reader->load($file);
            } else {
                throw new InvalidArgumentException('Invalid file extention!');
            }
        } else {
            throw new InvalidArgumentException("Invalid file path!");
        }
    }

    private function allowedExt()
    {
        return [
            "xlsx",
            "xls",
            "csv",
        ];
    }

    public function getReaderClassName($ext)
    {
        return "PhpOffice\\PhpSpreadsheet\\Reader\\".ucfirst($ext);
    }

    public function getDataAsArray(string|int $sheetIndex = "")
    {
        if ($sheetIndex === "") {
            $data = $this->wb->getActiveSheet()->toArray();
        } elseif (is_int($sheetIndex)) {
            $data = $this->wb->getSheet($sheetIndex)->toArray();
        } elseif (is_string($sheetIndex)) {
            $data = $this->wb->getSheetByNameOrThrow($sheetIndex)->toArray();
        } else {
            throw new InvalidArgumentException("Invalid index type!");
        }
        $keys = $data[0];

        // $data = array_slice($data, 1);
        for ($j=1; $j < count($data); $j++) {
            $data[$j-1] = [];
            for ($i=0; $i < count($keys); $i++) {
                $data[$j-1][$keys[$i]] = $data[$j][$i];
            }
        }
        return $data;
    }
}
