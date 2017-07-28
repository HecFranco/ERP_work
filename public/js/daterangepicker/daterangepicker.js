 $(function() {
    let start = moment().startOf('month');
    let end = moment().endOf('month');

    function cb(start, end) {
        console.log(start);
        console.log($('#range')["0"].innerText);
        $('#daterangepicker').html($('#range')["0"].innerText);
    //        $('#daterangepicker').html(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
    }
    $('#daterangepicker').daterangepicker({
            autoplay:true,
            startDate: document.getElementById('firstdate').innerHTML,
            endDate: document.getElementById('seconddate').innerHTML,
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
    }, cb);
    cb(start, end);
});
$(document).ready(function(){
	$("#daterangepicker").change(function(){
        document.schedules_date_range_picker.submit();
    });
});