@extends('layouts.app')

@push('styles')
<link href="{{ asset('css/seller-dashboard.css') }}" rel="stylesheet">
@endpush
@section('content')
<div id="app" v-cloak>
    {{-- LEADS --}}
    <div class="row">
        <div class="col-lg-12">
            <div >
                <h2>
                    @lang('Prospectos abiertos')
                    <span class="badge badge-primary">@{{open_leads.length}}</span>
                </h2>
                @include('seller.dashboard._leads')
            </div>
            <div>
                <h2>
                    @lang('Prospectos en curso')
                    <span class="badge badge-warning">@{{taken_leads.length}}</span>
                </h2>
                @include('seller.dashboard._taken_leads')
            </div>
        </div>
    </div>
    
    @include('seller.dashboard._modal_take_lead')
</div>
@endsection
@push('scripts')
<script>
    var open_leads = @json($open_leads);
    var taken_leads = @json($taken_leads);
    
    var app = new Vue({
        el: '#app',
        data: {
            seller: @json($seller),
            leads: open_leads.concat(taken_leads),
            open_leads: open_leads,
            taken_leads: taken_leads,
            lead_to_edit_id: '',
            base_route: "{{route('base')}}",
            show_route: "{{route('seller.leads')}}/",
        },
        computed: {
            take_lead_route: function () {
                return this.base_route + '/seller/leads/take/' + this.lead_to_edit_id;
            },
        },
        methods: {
            setLeadToEdit: setLeadToEdit,
            formatChange: formatChange,
            formatMillions: formatMillions //en app.blade.php
        }
    });

    // Formatea el procentaje de cambio
    function formatChange(change_value) {
        if(change_value == null || change_value == 0){
            return '<div class="stat-percent font-bold text-primary">' + 0 + '% </div>'
        }
        change_value = parseFloat(change_value);
        if(change_value > 0){
            return '<div class="stat-percent font-bold text-success">' + change_value + '% <i class="fa fa-level-up"></i></div>'
        }
        if(change_value < 0){
            return '<div class="stat-percent font-bold text-danger">' + change_value + '% <i class="fa fa-level-down"></i></div>'
        }
    }

    function setLeadToEdit(id) {
        this.lead_to_edit_id = id;
    }
</script>
@endpush
