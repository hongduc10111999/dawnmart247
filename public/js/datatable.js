function createDataTable(tableId, url, option) {
    var dataTable = $('#' + tableId).DataTable($.extend({
        responsive: true,
        lengthChange: true,
        autoWidth: false,
        processing: true,
        serverSide: true,
        ajax: {
            url: url,
            type: 'GET',
            dataType: 'json'
        },
        // language: {
        //     url: 'https://cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Japanese.json'
        // },
        drawCallback: function () {
            var api = this.api();
            var startIndex = api.context[0]._iDisplayStart;
            api.column(0, {
                search: 'applied',
                order: 'applied'
            }).nodes().each(function (cell, i) {
                cell.innerHTML = startIndex + i + 1;
            });
        },
    }, option));
    return dataTable;
}

function renderActions(routeDel, routeEdit, data ,csrfToken) {
    return `
      <div class="d-flex justify-content-center">
        <a href="${routeEdit}" class="btn btn-warning btn-sm mr-2" style="
        margin-right: 10px;
        ">
            <i class='bx bxs-edit'></i>
        </a>
        <form action="${routeDel}" id="form-delete-${data}" class="ml-1">
            <input type="hidden" name="_token" value="${csrfToken}">
            <a href="javascript:;" data-id="${data}" class="btn btn-danger btn-sm delete-action">
            <i class='bx bxs-trash'></i>
            </a>
        </form>
      </div>
    `;
}