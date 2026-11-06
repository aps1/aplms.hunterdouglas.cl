<?php

namespace App\Exports;

use App\Metrics\LeadsFilters;
use App\Repositories\LeadsRepository;
use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LeadExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    protected $user;

    public function __construct(LeadsFilters $filters, User $user)
    {
        $this->filters = $filters;
        $this->user = $user;
    }

    public function headings(): array
    {
        $headins = [
            'ID',
            __('Nombre'),
            __('Email'),
            __('Marca'),
            __('Tel'),
            __('Método de contacto'),
            __('Distribuidor'),
            __('KAM'),
            __('Vendedor'),
            __('Origen'),
            __('Fuente'),
            __('Categoría de Producto'),
            __('Región'),
            __('Comuna'),
            __('Dirección'),
            __('Estatus'),
            __('Motivo'),
            __('Valor Neto Cotizado'),
            __('Folios'),
            __('Estratégico'),
            __('Producto'),
            __('Página'),
            __('Fecha'),
            __('Dinámicos'),
            __('Mensaje'),
        ];
        // Sin distribuidor ni kam para el personal de distribuidor
        if ($this->user->isDealerPersonal()) {
            unset($headins[6]);
            unset($headins[7]);
        }

        return $headins;
    }

    public function map($lead): array
    {
        $folios = $lead->folios->reduce(function ($carry, $folio) {
            return $carry.' '.$folio->number;
        });
        $map = [
            $lead->web_identifier,
            $lead->name,
            $lead->email,
            $lead->brand,
            $lead->phone.' ',
            $lead->contact_method.' ',
            $lead->dealership ? $lead->dealership->name : '',
            optional($lead->dealership)->kam ? $lead->dealership->kam->name : '',
            $lead->user ? $lead->user->name : '',
            $lead->origin_description,
            $lead->source_description,
            $lead->product_category_description,
            $lead->geo_loc1,
            $lead->geo_loc2,
            $lead->address,
            $lead->status_description,
            $lead->status_reason_description,
            str_replace('.', '', $lead->net_value),
            $folios,
            $lead->isStrategic() ? 'Sí' : 'No',
            $lead->product,
            $lead->page_title,
            $lead->created_at->format('Y-m-d H:i'),
            $lead->dynamic_data_string,
            $lead->message,
        ];
        // Sin distribuidor ni kam para el personal de distribuidor
        if ($this->user->isDealerPersonal()) {
            unset($map[6]);
            unset($map[7]);
        }

        return $map;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $leadRepo = new LeadsRepository;

        return $leadRepo->getForUser($this->user, $this->filters);
    }
}
