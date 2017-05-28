<div style="width: 100%; height: 100vh;">
    <div id="main" style="width: 100%; height: 100%;"></div>
</div>
<?= $toolbar_cfg ?>
<script type="text/javascript">
    var med_org =
        [
            {id: 0, text: '(не указано)'},
            <?php
if(!empty($med_org))
foreach($med_org as $key=>$value){
    ?>
            {id: <?=$value['dicid']?>, text: '<?=htmlspecialchars(trim($value['name']))?>'},
            <?php
        }
        ?>
        ];

    var config = {
        layout: {
            name: 'layout',
            padding: 0,
            margin: 10,
            panels: [
                {type: 'top', size: '34', resizable: false},
                {type: 'main', size: '10%', minSize: '100'},
                {type: 'preview', size: '90%'}
            ]
        },

        ipra_upload: {
            name: 'ipra_upload',
            header: 'Загрузка XML',
            formURL: '/api/statiprauploadform',
            url: '/api/xmlupload',
            fields: [
                {field: 'file', type: 'file'}
            ],
            onChange: function () {
                var obj = this;
                this.save({}, function (data) {
                    if (data.status == 'error') {
                        console.log('ERROR: ' + data.message);
                        return;
                    }
                    obj.clear();
                    w2ui.ipra_assoc.refresh();
                });
            }
        },

        ipra_assoc: {
            name: 'ipra_assoc',
            url: '/api/statipraassoclist',
            limit: 50,
            method: 'GET', // need this to avoid 412 error on Safari
            header: 'Список несопоставленных ИПРА',
            show: {
                header: true,

                footer: true,
                toolbar: true,
                toolbarReload: false,   // indicates if toolbar reload button is visible
                toolbarColumns: false,   // indicates if toolbar columns button is visible
                toolbarSearch: false   // indicates if toolbar search controls are visible
            },
            toolbar: {
                items: [
                    {type: 'button', id: 'csv', caption: 'Выгрузить в CSV', img: 'icon-folder'},
                    {type: 'spacer'},
                    {type: 'html', id: 'tfoms',
                        html:'<form method="post" enctype="multipart/form-data">'+
                        '<label>Загрузить сопоставление:'+
                    '<input name="tfoms" type="file" accept=".csv" onchange="this.form.submit()"/>'+
                        '</label></form>'}
                ],
                onClick: function (target, data) {
                    if (target == 'csv') {
                        var csv = 'RECID;Дата рождения;Фамилия;Имя;Отчество;СНИЛС;Мед.орг.;' + "\n";
                        for (i = 1; i <= w2ui.ipra_assoc.records.length; i++) {
//
                            {
                                csv = csv + w2ui.ipra_assoc.records[i - 1].recid + ';';
                                csv = csv + w2ui.ipra_assoc.records[i - 1].bdate + ';';
                                csv = csv + w2ui.ipra_assoc.records[i - 1].lname + ';';
                                csv = csv + w2ui.ipra_assoc.records[i - 1].fname + ';';
                                csv = csv + w2ui.ipra_assoc.records[i - 1].sname + ';';
                                csv = csv + w2ui.ipra_assoc.records[i - 1].snils + ';';
                                csv = csv + w2ui.ipra_assoc.records[i - 1].med_org_txt + ';';
                                csv = csv + "\n";
                            }
                        }
                        csv = $('<div/>').html(csv).text();
                        saveTextAs('\ufeff' + csv, "Для уточнения МО ИПРА.csv");
//
                    }
                }
            },
            columns: [
                {field: 'prgnum', caption: '№ ИПРА', size: '10%', sortable: true},
                {field: 'med_org_txt', caption: 'Мед.организация из XML', size: '40%', sortable: true}
            ],
            onDblClick: function (event) {
                console.log(event);
                console.log(this);
                if (('prgnum' == this.columns[event.column].field) ||
                    ('med_org_txt' == this.columns[event.column].field)) {
                    w2popup.open({
                        title: 'Детальный просмотр',
                        body: '<iframe src="/stat/upload/detail?id=' + event.recid + '" style="width: 100%;height: 100%;padding:0px;margin:0px"></iframe>',
                        width: 600,     // width in px
                        height: 400,     // height in px
                        showMax: true   // max button indicator
                    });
                }
            },
            onChange: function (event) {

                if ('med_org_id' == this.columns[event.column].field) {
                    w2ui.ipra_assoc.records[event.index].med_org_id = event.value_new;
                }
                if ('complete' == this.columns[event.column].field) {
                    if (event.value_new) {
                        var xhttp = new XMLHttpRequest();
                        var body = 'med_org_id=' + encodeURIComponent(w2ui.ipra_assoc.records[event.index].med_org_id) +
                            '&id=' + encodeURIComponent(event.recid) +
                            '&cmd=' + encodeURIComponent('save-record');
                        xhttp.open("POST", "/api/statipraassoclistcomplete", false);
                        xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                        xhttp.onreadystatechange = function () {
                            w2ui.ipra_assoc.reload();
                        };
                        xhttp.send(body);

                    }
                }
            }
        }
    };
    $('#main').w2layout(config.layout);
    //    w2ui.layout.content('top', $().w2toolbar(config.toolbar));
    w2ui.layout.content('top', $().w2toolbar(toolbar_cfg));
    w2ui.layout.content('main', $().w2form(config.ipra_upload));
    w2ui.layout.content('preview', $().w2grid(config.ipra_assoc));


</script>