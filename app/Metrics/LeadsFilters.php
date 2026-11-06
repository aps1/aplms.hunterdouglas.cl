<?php

namespace App\Metrics;

use Illuminate\Support\Carbon;

class LeadsFilters
{
    public $start;

    public $end;

    public $previus_start;

    public $previus_end;

    public $origin;

    public $brand;

    public $product_category;

    public $status;

    public $seller;

    public $kam_id;

    public $dealership_id;

    public function __construct(?array $filters = null)
    {
        if (is_array($filters)) {
            $this->setFiltersFromArray($filters);
            $from = array_key_exists('from', $filters) ? $filters['from'] : null;
            $to = array_key_exists('to', $filters) ? $filters['to'] : null;
            $this->setDataRange($from, $to);

        } else {
            $this->setDataRange(null, null);
        }
    }

    /**
     * Set the date range for the metrics. por defecto ultimos 90
     *
     * @param [type] $from
     * @param [type] $to
     * @return void
     */
    protected function setDataRange($from, $to)
    {
        $this->start = $from ?
            Carbon::createFromFormat('d-m-Y', $from)->startOfDay() :
            Carbon::now()->subDays(90)->startOfDay();
        $this->end = $to ?
            Carbon::createFromFormat('d-m-Y', $to)->endOfDay() :
            Carbon::now()->endOfDay();

        $diff_in_days = $this->start->diffInDays($this->end);

        $this->previus_start = $from ?
            Carbon::createFromFormat('d-m-Y', $from)->subDay($diff_in_days)->startOfDay() :
            Carbon::now()->subDays(180)->startOfDay();
        $this->previus_end = $to ?
            Carbon::createFromFormat('d-m-Y', $to)->subDay($diff_in_days)->endOfDay() :
            Carbon::now()->subDays(90)->endOfDay();
    }

    /**
     * Setea los filtros desde un array
     *
     * @return void
     */
    protected function setFiltersFromArray(array $filters)
    {
        foreach ($filters as $filter_name => $filter_value) {
            $this->{$filter_name} = $filter_value;
        }
    }
}
