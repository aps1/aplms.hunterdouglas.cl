<h3 v-if="open_leads.length == 0" class="text-center">@lang('No tienes prospectos abiertos')</h3>
<div v-else>
    <div class="ibox d-none d-md-block d-lg-block d-xl-block">
        <div class="ibox-content">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <td>ID</td>
                        <td>Nombre</td>
                        <td>Estado</td>
                        <td></td>
                        <td>Email</td>
                        <td></td>
                        <td>Tel</td>
                        <td>Origen</td>
                        <td>Fecha</td>
                        <td></td>
                    </thead>
                    <tbody>
                        <tr v-for="lead in open_leads">
                            <td>
                                <a :href="show_route + lead.web_identifier" class="client-link">@{{lead.web_identifier}}</a>
                            </td>
                            <td>
                                <a :href="show_route + lead.web_identifier" class="client-link">
                                    @{{lead.name}}
                                    <span v-if="lead.is_strategic" title="{{__('Prospecto estratégico')}}" class="text-warning fa fa-star"></span>
                                </a>
                            </td>
                            <td>
                                <span :class="'label label-' + lead.status_color">
                                    @{{lead.status_description}}
                                </span>
                            </td>
                            <td class="contact-type"><i class="fa fa-envelope"></i></td>
                            <td>
                                <a :href="'mailto:' + lead.email" style="color:inherit">@{{lead.email}}</a>
                            </td>
                            <td class="contact-type"><i class="fa fa-phone"></i></td>
                            <td>
                                <a :href="'tel:' + lead.phone" style="color:inherit">@{{lead.phone}}</a>
                            </td>
                            <td>
                                @{{lead.origin_description}}
                            </td>
                            <td>
                                @{{lead.created_at}}
                            </td>
                            <td v-if="lead.is_taken">
                                <a :href="show_route + lead.web_identifier" class="btn btn-default btn-sm">Actualizar</a>
                            </td>
                            <td v-else>
                                <a href="#"
                                data-toggle="modal" 
                                data-target="#modal-take-lead" 
                                class="btn btn-primary btn-sm"
                                v-on:click="setLeadToEdit(lead.web_identifier)"
                                >
                                    Tomar
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{-- CELL --}}
    <div class="row d-block d-sm-none">
        <div v-for="lead in open_leads" class="col-sm-12 col-md-6 col-lg-3">
            <div class="contact-box center-version">
                <a :href="show_route + lead.web_identifier" style="padding: 12px;">
                    <h3 class="m-b-xs"><strong>@{{lead.name}}</strong></h3>

                    <div class="font-bold"></div>
                    <address class="m-t-md">
                        @{{lead.email}}<br>
                        @{{lead.phone}}<br>
                    </address>
                </a>
                <div class="row" style="padding-bottom: 12px;">
                    <div class="col text-center">
                        <div class="btn-group">
                            <a :href="'tel:' + lead.phone"  class="btn btn-xs btn-white"><i class="fa fa-phone"></i> Llamar </a>
                            <a :href="'mailto:' + lead.email"  class="btn btn-xs btn-white"><i class="fa fa-envelope"></i> Email</a>
                        </div>
                    </div>
                </div>
                <div class="contact-box-footer">
                    <div class="row">
                        <div class="col">
                            <a v-if="lead.is_taken" :href="show_route + lead.web_identifier" class="btn btn-default btn-sm">Actualizar</a>
                            <a v-else href="#"
                            data-toggle="modal" 
                            data-target="#modal-take-lead" 
                            class="btn btn-primary btn-sm"
                            v-on:click="setLeadToEdit(lead.id)"
                            >
                                Tomar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
