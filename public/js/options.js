var report_range_options = {
    format: 'DD-MM-YYYY',
    startDate: moment().subtract(90, 'days'),
    endDate: moment(),
    showDropdowns: true,
    showWeekNumbers: true,
    timePicker: false,
    timePickerIncrement: 1,
    timePicker12Hour: true,
    ranges: {
        'Últimos 90 días': [moment().subtract(90, 'days'), moment()],
        'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
        'Últimos 30 días': [moment().subtract(30, 'days'), moment()],
        'Este mes': [moment().startOf('month'), moment().endOf('month')],
        'Último trimestre': [moment().subtract(3, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
        'Último semestre': [moment().subtract(6, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
        'Año': [moment().startOf('year'), moment()],
        'Año Pasado': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')],
        'Histórico': [moment("20190601", "YYYYMMDD"), moment()],
    },
    opens: 'left',
    drops: 'down',
    buttonClasses: ['btn', 'btn-sm'],
    applyClass: 'btn-primary',
    cancelClass: 'btn-default',
    separator: ' al ',
    locale: {
        applyLabel: 'Aplicar',
        cancelLabel: 'Cancelar',
        fromLabel: 'Desde',
        toLabel: 'Hasta',
        customRangeLabel: 'Custom',
        daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr','Sa'],
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Deciembre'],
        firstDay: 1
    }
};

var toastr_options = {
    closeButton: true,
    debug: false,
    progressBar: true,
    preventDuplicates: false,
    positionClass: "toast-top-right",
    onclick: null,
    showDuration: "400",
    hideDuration: "1000",
    timeOut: "6000",
    extendedTimeOut: "1000",
    showEasing: "swing",
    hideEasing: "linear",
    showMethod: "fadeIn",
    hideMethod: "fadeOut"
};

var datatables_options = {
    pageLength: 25,
    responsive: true,
    dom: '<"html5buttons"B>lTfgitp',
    language: {
        "sProcessing":     "Procesando...",
        "sLengthMenu":     "Mostrar _MENU_ registros",
        "sZeroRecords":    "No se encontraron resultados",
        "sEmptyTable":     "Ningún dato disponible en esta tabla",
        "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
        "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix":    "",
        "sSearch":         "Buscar:",
        "sUrl":            "",
        "sInfoThousands":  ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
            "sFirst":    "Primero",
            "sLast":     "Último",
            "sNext":     "Siguiente",
            "sPrevious": "Anterior"
        },
        "oAria": {
            "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        }      
    },
    buttons: [
        {extend: 'copy'},
        {extend: 'csv'},
        {extend: 'excel', title: 'excel'},
        {extend: 'pdf', title: 'pdf'},

        {extend: 'print',
            customize: function (win){
                $(win.document.body).addClass('white-bg');
                $(win.document.body).css('font-size', '10px');

                $(win.document.body).find('table')
                    .addClass('compact')
                    .css('font-size', 'inherit');
            }
        }
    ]
};