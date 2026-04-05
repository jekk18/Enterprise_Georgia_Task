<?php

namespace App\Services;

use App\Actions\Admin\GetExportQueryAction;
use App\Exports\GenericExport;
use Maatwebsite\Excel\Facades\Excel; 
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportService
{
    public function __construct(
        protected GetExportQueryAction $queryAction
    ) {}

    public function exportToExcel(string $type, string $fileName, array $headings, \Closure $mapping, array $filters = []): BinaryFileResponse
    {
        $query = $this->queryAction->execute($type, $filters);

        return Excel::download(
            new GenericExport($query, $headings, $mapping),
            $fileName
        );
    }
}