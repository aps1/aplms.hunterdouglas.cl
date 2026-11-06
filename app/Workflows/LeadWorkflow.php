<?php

namespace App\Workflows;

use App\Enums\ActivityTypes;
use App\Enums\LeadStatus;
use App\Lead;

class LeadWorkflow
{
    public function getActivitiesForLead(Lead $lead): array
    {
        if (Auth()->user()->isAdmin()) {
            return $this->getForAdmin();
        }
        // por defecto solo comentario
        $comentario = ActivityTypes::Comentario;
        $activities = [
            $comentario => ActivityTypes::getDescription($comentario),
        ];

        switch ($lead->status) {
            case LeadStatus::Creado:
                $activities = $this->getForCreado();
                break;

            case LeadStatus::Derivado:
                $activities = $this->getForDerivado();
                break;

            case LeadStatus::Asignado:
                $activities = $this->getForAsignado();
                break;

            case LeadStatus::Contactado:
                $activities = $this->getForContactado();
                break;

            case LeadStatus::Cotizado:
                $activities = $this->getForCotizado();
                break;

            case LeadStatus::Vendido:
                $activities = $this->getForVendido();
                break;

                /*   case LeadStatus::Foliado:
                       $activities = $this->getForFoliado();
                       break;
*/
            case LeadStatus::Perdido:
                $activities = $this->getForPerdido();
                break;
            case LeadStatus::Visitado:
                $activities = $this->getForVisitado();
                break;

            default:
                // code...
                break;
        }

        return $activities;
    }

    /**
     * Solo comentarios
     *
     * @return void
     */
    public function getForAdmin()
    {
        $comentario = ActivityTypes::Comentario;

        return [
            $comentario => ActivityTypes::getDescription($comentario),
        ];
    }

    /**
     * Activities para leads creados
     */
    public function getForCreado(): array
    {
        $comentario = ActivityTypes::Comentario;

        return [
            $comentario => ActivityTypes::getDescription($comentario),
        ];
    }

    /**
     * Activities para leads derivados
     */
    public function getForDerivado(): array
    {
        $comentario = ActivityTypes::Comentario;

        return [
            $comentario => ActivityTypes::getDescription($comentario),
        ];
    }

    /**
     * Activities para leads Asignado
     */
    public function getForAsignado(): array
    {
        $comentario = ActivityTypes::Comentario;
        $contacto = ActivityTypes::Contacto;
        $desiste = ActivityTypes::Desiste;

        return [
            $comentario => ActivityTypes::getDescription($comentario),
            $contacto => ActivityTypes::getDescription($contacto),
            $desiste => ActivityTypes::getDescription($desiste),
        ];
    }

    /**
     * Activities para leads contactados
     */
    public function getForContactado(): array
    {
        $comentario = ActivityTypes::Comentario;
        $cotizar = ActivityTypes::Cotizar;
        $desiste = ActivityTypes::Desiste;
        $visitar = ActivityTypes::Visitar;

        return [
            $comentario => ActivityTypes::getDescription($comentario),
            $cotizar => ActivityTypes::getDescription($cotizar),
            $desiste => ActivityTypes::getDescription($desiste),
            $visitar => ActivityTypes::getDescription($visitar),
        ];
    }

    /**
     * Activities para leads visitados
     */
    public function getForVisitado(): array
    {
        $comentario = ActivityTypes::Comentario;
        $cotizar = ActivityTypes::Cotizar;
        $desiste = ActivityTypes::Desiste;
        $visitar = ActivityTypes::Visitar;

        return [
            $comentario => ActivityTypes::getDescription($comentario),
            $cotizar => ActivityTypes::getDescription($cotizar),
            $desiste => ActivityTypes::getDescription($desiste),
            $visitar => ActivityTypes::getDescription($visitar),
        ];
    }

    /**
     * Activities para leads cotizados
     */
    public function getForCotizado(): array
    {
        $comentario = ActivityTypes::Comentario;
        $venta = ActivityTypes::Venta;
        $desiste = ActivityTypes::Desiste;

        return [
            $comentario => ActivityTypes::getDescription($comentario),
            $venta => ActivityTypes::getDescription($venta),
            $desiste => ActivityTypes::getDescription($desiste),
        ];
    }

    /**
     * Activities para leads vendido
     */
    public function getForVendido(): array
    {
        $comentario = ActivityTypes::Comentario;

        // $foliar = ActivityTypes::Foliar;
        return [
            $comentario => ActivityTypes::getDescription($comentario),
            //    $foliar => ActivityTypes::getDescription($foliar)
        ];
    }

    /**
     * Activities para leads vendido
     */
    public function getForFoliado(): array
    {
        $comentario = ActivityTypes::Comentario;
        $foliar = ActivityTypes::Foliar;

        return [
            $comentario => ActivityTypes::getDescription($comentario),
            $foliar => ActivityTypes::getDescription($foliar),
        ];
    }

    public function getForPerdido(): array
    {
        $comentario = ActivityTypes::Comentario;
        $contacto = ActivityTypes::Contacto;
        $cotizar = ActivityTypes::Cotizar;

        return [
            $comentario => ActivityTypes::getDescription($comentario),
            $contacto => ActivityTypes::getDescription($contacto),
            $cotizar => ActivityTypes::getDescription($cotizar),
        ];
    }
}
