<?php

namespace App\Filters;

use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

class ExcelColumnFilter implements IReadFilter
{
    private string $startColumn;
    private string $endColumn;

    public function __construct(string $startColumn, string $endColumn)
    {
        $this->startColumn = $startColumn;
        $this->endColumn = $endColumn;
    }

    public function readCell($column, $row, $worksheetName = ''): bool
    {
        if (in_array($column, range($this->startColumn, $this->endColumn), true)) {
            return true;
        }

        return false;
    }

}
