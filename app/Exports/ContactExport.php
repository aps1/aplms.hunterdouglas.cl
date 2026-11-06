<?php

namespace App\Exports;

use App\Contact;
use App\Metrics\ContactsFilters;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ContactExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct(ContactsFilters $filters)
    {
        $this->filters = $filters;
    }

    public function headings(): array
    {
        return [
            __('Nombre'),
            __('Email'),
            __('Tel'),
            __('Tel 2'),
            __('Método de contacto'),
            __('Tipo'),
            __('Distribuidor'),
            __('Fuente'),
            __('Región'),
            __('Comuna'),
            __('Dirección'),
            __('Aut. Mailing'),
            __('Descripción'),
            __('Fecha'),
        ];
    }

    public function map($contact): array
    {
        return [
            $contact->name,
            $contact->email,
            $contact->phone.' ',
            $contact->phone2.' ',
            $contact->contact_method,
            $contact->type_description,
            $contact->dealership->name,
            $contact->source_description,
            $contact->geo_loc1,
            $contact->geo_loc2,
            $contact->address,
            $contact->is_subscribed ? 'Sí' : 'No',
            $contact->description,
            $contact->created_at->format('Y-m-d H:i'),
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Contact::with('dealership')
            ->whereBetween('created_at', [$this->filters->start, $this->filters->end])
            ->when($this->filters->dealership_id, function ($query, $dealership_id) {
                return $query->where('dealership_id', $dealership_id);
            })
            ->when($this->filters->type, function ($query, $type) {
                return $query->where('type', $type);
            })
            ->when($this->filters->source, function ($query, $source) {
                return $query->where('source', $source);
            })
            ->get();
    }
}
