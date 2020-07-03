$(document).ready(function () {
// Init DataTables
    var datatable = $('#table').DataTable({
        ajax: {
            url: '/ajax_getUsers',
        },
        responsive: true,
        processing: true,
        pageLength: 50,
        dom: 'Bflrtip',
        buttons: [
            {
                extend: 'csv',
                text: 'Exportar',
                charset: 'utf-8',
                extension: '.csv',
                fieldSeparator: ';',
                fieldBoundary: '',
                filename: 'Usuarios',
                bom: true,
            }
        ],
        order: [],
        columns: [
            {data: 'id'},
            {data: 'email'},
            {data: 'name'},
            {data: 'surnames'},
            {data: 'dni'},
            {data: 'birthday'},
            {data: 'dateRegister'},
            {data: 'tlf'},
            {data: 'panicAction'},
        ],
        // Translate
        language: {
            lengthMenu: "Mostradas _MENU_ filas por página",
            info: "Mostrando página _PAGE_ de _PAGES_ de _TOTAL_ resultados",
            infoEmpty: "Mostrando 0 registros",
            emptyTable: "No hay datos para mostrar",
            infoFiltered: "(de un total de _MAX_)",
            loadingRecords: "&nbsp;",
            processing: "<div class='spinner-border text-primary'></div>",
            search: "Buscar:",
            zeroRecords: "No se han encontrado registros",
            paginate: {
                first: "Primera",
                last: "Última",
                next: "Siguiente",
                previous: "Anterior"
            },
            aria: {
                sortAscending: ": activa para ordenar las columnas de manera ascendente",
                sortDescending: ": activa para ordenar las columnas de manera descendente"
            }
        }
    });

});
