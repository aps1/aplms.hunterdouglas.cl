<?php

namespace App\Metrics;

use App\Enums\LeadStatus;
use App\Lead;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Request;

class Metrics
{
    /**
     * @var array
     */
    protected $filters = [];

    /**
     * Consigue la suma de los leads por estado segun filtros del request
     *
     * @param  Request  $request
     */
    public function getLeadsCountByStatus($filters): array
    {
        if ($filters instanceof Request) {
            $this->setFiltersByRequest($filters);
        } else {
            $this->setFiltersByArray($filters);
        }

        return [
            'todos' => $this->leadsCount(),
            'creados' => $this->leadsCount(LeadStatus::Creado),
            'derivados' => $this->leadsCount(LeadStatus::Derivado),
            'asignados' => $this->leadsCount(LeadStatus::Asignado),
            'contactados' => $this->leadsCount(LeadStatus::Contactado),
            'cotizados' => $this->leadsCount(LeadStatus::Cotizado),
            'perdidos' => $this->leadsCount(LeadStatus::Perdido),
            'vendidos' => $this->leadsCount(LeadStatus::Vendido),
        ];
    }

    /**
     * formate la respuesta para que traiga los actuales los previos y el cambio
     *
     * @param  int|null  $status
     */
    public function leadsCount($status = null): array
    {
        if ($status) {
            $this->filters['status'] = $status;
        }
        $actual = $this->getLeadCountByQuery();
        $previus = $this->getLeadCountByQuery($previus_period = true);
        $change = $this->calculateChange($actual ?? 0, $previus ?? 0);

        return [
            'actual' => $actual,
            'previus' => $previus,
            'change' => $change,
            'by_months' => $this->getLeadsCountByMonth($status),
        ];
    }

    /**
     * Consigue la suma de leads via query
     *
     * @param  bool  $previus_period
     */
    protected function getLeadCountByQuery($previus_period = false): int
    {
        $status = $this->filters['status'];
        $dealership_id = $this->filters['dealership_id'];
        $seller_id = $this->filters['seller_id'];
        $start = $previus_period ? $this->filters['previus_start'] : $this->filters['start'];
        $end = $previus_period ? $this->filters['previus_end'] : $this->filters['end'];

        return Lead::whereBetween('created_at', [$start, $end])
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($dealership_id, function ($query, $dealership_id) {
                return $query->where('dealership_id', $dealership_id);
            })
            ->when($seller_id, function ($query, $seller_id) {
                return $query->where('user_id', $seller_id);
            })
            ->count();
    }

    public function getLeadsCountByMonth($status = null)
    {
        $dealership_id = $this->filters['dealership_id'];
        $seller_id = $this->filters['seller_id'];
        $localization = session('localization');

        $response = DB::table('leads')
            ->select(DB::raw('(COUNT(*)) as total'), DB::raw("DATE_FORMAT(created_at, '%Y%m') as mes"))
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($dealership_id, function ($query, $dealership_id) {
                return $query->where('dealership_id', $dealership_id);
            })
            ->when($seller_id, function ($query, $seller_id) {
                return $query->where('user_id', $seller_id);
            })
            ->when($localization, function ($query, $localization) {
                return $query->where('localization', $localization);
            })
            ->orderBy('mes', 'desc')
            ->groupBy('mes')
            ->get();

        $data = $response->pluck('total', 'mes');
        $previuos_months = [];
        for ($i = 0; $i < 12; $i++) {
            $previuos_months[] = date('Ym', strtotime(date('Y-m-01')." -$i months"));
        }
        // dd($previuos_months);
        foreach ($previuos_months as $month) {
            if (! isset($data[$month])) {
                $data[$month] = 0;
            }
        }

        return $data->sortKeysDesc();
    }

    /**
     * Consigue los ultimos meses en esp.
     *
     * @return void
     */
    public function getLastMonths()
    {
        $es_months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        $previuos_months = [];
        for ($i = 0; $i < 12; $i++) {
            $previuos_months[] = date('n', strtotime(date('Y-m-01')." -$i months"));
        }
        foreach ($previuos_months as $month) {
            $months[] = $es_months[$month - 1];
        }

        return array_reverse($months);
    }

    /**
     * Setea los filtros
     *
     * @return void
     */
    protected function setFiltersByRequest(Request $request)
    {
        $this->filters['status'] = $request->get('status');
        $this->filters['dealership_id'] = $request->get('dealership_id');
        $this->filters['seller_id'] = $request->get('seller_id');
        $this->setDatesFilters($request->get('from'), $request->get('to'));
    }

    /**
     * Setea los filtros
     *
     * @param  array  $request
     * @return void
     */
    protected function setFiltersByArray(array $filters)
    {
        $this->filters['status'] = $filters['status'] ?? null;
        $this->filters['dealership_id'] = $filters['dealership_id'] ?? null;
        $this->filters['seller_id'] = $filters['seller_id'] ?? null;
        $from = $filters['from'] ?? null;
        $to = $filters['to'] ?? null;
        $this->setDatesFilters($from, $to);
    }

    /**
     * Set the date range for the metrics. por defecto ultimos 30
     *
     * @param [type] $from
     * @param [type] $to
     * @return void
     */
    protected function setDatesFilters($from = null, $to = null)
    {
        $this->filters['start'] = $from ?
            Carbon::createFromFormat('d-m-Y', $from)->startOfDay() :
            Carbon::now()->subDays(90)->startOfDay();
        $this->filters['end'] = $to ?
            Carbon::createFromFormat('d-m-Y', $to)->endOfDay() :
            Carbon::now()->endOfDay();

        $diff_in_days = $this->filters['start']->diffInDays($this->filters['end']);

        $this->filters['previus_start'] = $from ?
            Carbon::createFromFormat('d-m-Y', $from)->subDay($diff_in_days)->startOfDay() :
            Carbon::now()->subDays(180)->startOfDay();
        $this->filters['previus_end'] = $to ?
            Carbon::createFromFormat('d-m-Y', $to)->subDay($diff_in_days)->endOfDay() :
            Carbon::now()->subDays(90)->endOfDay();
    }

    /**
     * Calculate the change percent
     *
     * @param  int  $current
     */
    protected function calculateChange(int $actual, int $previus)
    {
        if ($previus == 0) {
            return 0;
        }
        $diff = $actual - $previus;
        $percent = ($diff / $previus) * 100;

        return number_format($percent, 1, ',', '.');
    }
}
