    var start ;
    var end ;
$('#daterangepicker').daterangepicker({
    "showDropdowns": false,
    "showWeekNumbers": true,
    "showISOWeekNumbers": true,
    "timePicker24Hour": true,
    "autoApply": true,
    "alwaysShowCalendars": true,
    "startDate": start,
    "endDate": end,
    "dateLimit": {
        "days": 30
    },
    "ranges": {
        'Hoy': [moment(), moment()],
        'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
        'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
        'Este mes': [moment().startOf('month'), moment().endOf('month')],
        'El mes pasado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    },
    "locale": {
        "format": "DD/MM/YYYY",
        "separator": " - ",
        "applyLabel": "Aplicar",
        "cancelLabel": "Cancel",
        "fromLabel": "From",
        "toLabel": "To",
        "customRangeLabel": "Personalizado",
        "weekLabel": "W",
        "daysOfWeek": [
            "Do",
            "Lu",
            "Ma",
            "Mi",
            "Ju",
            "Vi",
            "Sá"
        ],
        "monthNames": [
            "Enero",
            "Febrero",
            "Marzo",
            "Abril",
            "Mayo",
            "Junio",
            "Julio",
            "Agosto",
            "Septiembre",
            "Octubre",
            "Noviembre",
            "Deciembre"
        ],
        "firstDay": 1
    }

},     function(start, end, label) {
    $('#daterangepicker').html(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
    document.schedules_date_range_picker.submit();
    
}); 