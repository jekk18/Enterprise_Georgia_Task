<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Database\Eloquent\Builder;

class GenericExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize
{
    public function __construct(
        protected Builder $query,
        protected array $headings,
        protected \Closure $mapping
    ) {}

    public function query()
    {
        return $this->query; // იყენებს Chunking-ს ავტომატურად (მეხსიერების დაზოგვა)
    }

    public function headings(): array
    {
        return $this->headings;
    }

    public function map($row): array
    {
        return ($this->mapping)($row);
    }
}