<div style="width: 100%; height: 100vh;">
    <div id="main" style="width: 100%; height: 100%;"></div>
</div>
<?= $toolbar_cfg ?>
<script type="text/javascript">

    var config = {
        layout: {
            name: 'layout',
            padding: 0,
            margin: 10,
            panels: [
                {type: 'top', size: '34', resizable: false},
                {type: 'main', size: '100%', minSize: '100'}
            ]
        },


        ipra_foreign: {
            name: 'ipra_unassoc',
            url: '/api/statipraunassoclist',
            limit: 50,
            method: 'GET', // need this to avoid 412 error on Safari
            header: 'Нераспределенные по МО',
            med_org_id :0,
            show: {
                selectColumn: false,
                toolbarDelete: false,
                header: true,
                footer: true,
                toolbar: true,
                toolbarReload: false,   // indicates if toolbar reload button is visible
                toolbarColumns: false,   // indicates if toolbar columns button is visible
                toolbarSearch: false   // indicates if toolbar search controls are visible
            },
            multiSelect: false,

            toolbar: {
                items: [

                    {type: 'spacer'},
                    {type: 'button', id: 'csv', caption: 'Выгрузить CSV', img: 'icon-folder'}
                ],
                onClick: function (target, data) {

                    if (target == 'csv') {
                        var csv = 'Дата выдачи;Дата окончания;ФИО;Дата рождения;СНИЛС;№ ИПРА;Мед.организация;' + "\n";
                        for (i = 1; i <= w2ui.ipra_unassoc.records.length; i++) {
                            csv = csv + w2ui.ipra_unassoc.records[i - 1].prgdt + ';';
                            csv = csv + w2ui.ipra_unassoc.records[i - 1].prgenddt + ';';
                            csv = csv + w2ui.ipra_unassoc.records[i - 1].fio + ';';
                            csv = csv + w2ui.ipra_unassoc.records[i - 1].bdate + ';';
                            csv = csv + w2ui.ipra_unassoc.records[i - 1].snils.trim() + ';';
                            csv = csv + w2ui.ipra_unassoc.records[i - 1].prgnum.trim() + ';';
                            csv = csv + w2ui.ipra_unassoc.records[i - 1].med_org_txt.trim() + ';';
                            csv = csv + "\n";
                        }
                        csv = $('<div/>').html(csv).text();
                        saveTextAs('\ufeff' + csv, "Нераспределенные по МО.csv");
                    }

                }
            },
            columns: [
                {field: 'prgdt', caption: 'Дата выдачи', size: '16%', sortable: true},
                {field: 'prgenddt', caption: 'Дата окончания', size: '16%', sortable: true},
                {field: 'fio', caption: 'ФИО', size: '16%', sortable: false},
                {field: 'bdate', caption: 'Дата рождения', size: '16%', sortable: true},
                {field: 'snils', caption: 'СНИЛС', size: '16%', sortable: true},
                {field: 'prgnum', caption: 'Номер ИПРА', size: '16%', sortable: true},
                {field: 'med_org_txt', caption: 'Мед.орг.', size: '26%', sortable: false}
            ],
        onLoad: function(event) {
            event.onComplete = function(){
                console.log(this.med_org_id);
            };
            }
        }
    };
    $('#main').w2layout(config.layout);
    w2ui.layout.content('top', $().w2toolbar(toolbar_cfg));
    w2ui.layout.content('main', $().w2grid(config.ipra_foreign));


</script>