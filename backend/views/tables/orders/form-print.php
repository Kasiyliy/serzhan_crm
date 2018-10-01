<script type="text/javascript" src="/profile/files/js/plugins/notifications/sweet_alert.min.js"></script>
<script type="text/javascript" src="/profile/files/js/printThis .js"></script>

<?=$this->render("/layouts/header/_header")?>
<a id='printBtn' style="margin-left: 2em; margin-bottom: 2em; margin-top: 2em;"  class="btn btn-primary" type="button" ><b><i class="icon-plus2"></i></b> Печать <span class="legitRipple-ripple"></span></a>

<div class="content" >
    <div class="row">
        <div class="col-sm-12 col-lg-12">
            <div class="panel">
                <div class="panel-body"  id="printArea">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>№</th>
                            <th>Наименование товара</th>
                            <th>Количество шт.</th>
                            <th>Цена тг.</th>
                            <th>Сумма тг.</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#printBtn').click(function () {
            var divToPrint=document.getElementById("printArea");
            newWin= window.open("");
            newWin.document.write(divToPrint.outerHTML);
            newWin.print();
            newWin.close();
        });
    });

    $(function() {
        var token = $('meta[name=csrf-token]').attr("content");
        $.extend( $.fn.dataTable.defaults, {
            autoWidth: false,
            responsive: true,
            dom: '<"datatable-header"fl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
            language: {
                "emptyTable":       "Данные отсутствуют.",
                "info":             "Показано с _START_ по _END_, всего: _TOTAL_",
                "infoEmpty":        "Показано 0 из 0, всего 0",
                "infoFiltered":     "(отфильтровано из _MAX_)",
                "infoPostFix":      "",
                "lengthMenu":       "<span>Показано:</span> _MENU_",
                "loadingRecords":   "Загрузка...",
                "processing":       "Загрузка...",
                "zeroRecords":      "Данные отсутствуют.",
                "paginate": {
                    "first":        "Первая",
                    "previous":     "&larr;",
                    "next":         "&rarr;",
                    "last":         "Последняя"
                },
                "aria": {
                    "sortAscending":    ": activate to sort column ascending",
                    "sortDescending":   ": activate to sort column descending"
                },
                "decimal":          "",
                "thousands":        ","
            },


            drawCallback: function () {
                $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').addClass('dropup');
            },
            preDrawCallback: function() {
                $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').removeClass('dropup');
            }
        });
        $('.table').DataTable({
            "processing": true,
            "serverSide": true,

            "ajax":{
                url :"/profile/tables/get-new-table/",
                type: "GET",
                data: {"_csrf-backend":token, table:"orders_goods", name:"print", order_id: <?=$model->id?>}
            },
            "stateSave": true,
            "stateSaveCallback": function (settings, data) { //Сохраняем таблицу (Страница, Сортировка, Количество записей и т.д)
                $.ajax({
                    url: "/profile/tables/savestate/",
                    dataType: "json",
                    type: "POST",
                    data: data,
                    success: function (response) {
                        console.log('save>>>', response);
                    }
                });
            },
            "stateLoadCallback": function (settings, callback) { //Загружаем сохраненные настройки таблицы
                $.ajax( {
                    url: '/profile/tables/getstate/',
                    async: false,
                    dataType: 'json',
                    success: function (json) {
                        callback(json);
                    }
                } );
            },
            aoColumns: [
                {"mData": {},
                    "mRender": function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },

                {"mData": {},
                    "mRender": function (data, type, row) {
                        return '<label class="text-semibold">' + data.name + '</label>';
                    }
                },
                {"mData": {},
                    "mRender": function (data, type, row) {
                        return '<label class="text-semibold">' + data.amount + '</label>';
                    }
                },

                {"mData": {},
                    "mRender": function (data, type, row) {
                        return '<label class="text-semibold">'+ data.price+ '</label>';
                    }
                },
                {"mData": {},
                    "mRender": function (data, type, row) {
                        return '<label class="text-semibold">'+ (data.price * data.amount) + '</label>';
                    }
                },
            ]
        });

        $('.dataTables_length select').select2({
            minimumResultsForSearch: Infinity,
            width: 'auto'
        });
    });



</script>