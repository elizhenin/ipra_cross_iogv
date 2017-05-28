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
                {type: 'main', size: '90%', minSize: '100'}
            ]
        },
    med_org_list: {
        name: 'med_org_list',
            show: {
        toolbar: true,
            footer: true,
            toolbarAdd: true,
            toolbarDelete: true,
            toolbarSave: true,
                toolbarSearch: false
    },
        toolbar: {
            items: [
                {type: 'spacer'},
                {type: 'button', id: 'csv', caption: 'Выгрузить в CSV', img: 'icon-folder'}
            ],
            onClick: function (target, data) {
                if (target == 'csv') {
                    var csv = 'ID;Код СМО;Название;Логин;Пароль;' + "\n";
                    for (i = 1; i <= w2ui.med_org_list.records.length; i++) {
                           if ('0' == w2ui.med_org_list.records[i - 1].parentid)
                        {
                            csv = csv + w2ui.med_org_list.records[i - 1].recid + ';';
                            csv = csv + w2ui.med_org_list.records[i - 1].smo + ';';
                            csv = csv + w2ui.med_org_list.records[i - 1].name + ';';
                            csv = csv + w2ui.med_org_list.records[i - 1].login + ';';
                            csv = csv + '«'+w2ui.med_org_list.records[i - 1].password+'»' + ';';
                            csv = csv + "\n";
                        }
                    }
                    csv = $('<div/>').html(csv).text();
                    saveTextAs('\ufeff' + csv, "Данные для входа МО.csv");
//
                }
            }
        },
        url: '/api/medorglist',
        method: 'POST',
        limit: 0,

            columns: [
        { field: 'dicid', caption: 'ID', size: '5%', editable: {type: 'text'} },
                { field: 'smo', caption: 'Код СМО', size: '10%', editable: {type: 'text'} },
        { field: 'name', caption: 'Название', size: '55%', editable: {type: 'text'} },
                { field: 'login', caption: 'Логин', size: '15%'},
                { field: 'password', caption: 'Пароль', size: '15%'}
    ],
        onAdd: function (event) {
            var g = w2ui['med_org_list'].records.length;
            if(g > 0) {
                var c = w2ui['med_org_list'].records[g - 1];
                w2ui['med_org_list'].add({
                    recid: c.recid + 1,
                    dicid: c.dicid + 1,
                    name: '(введите название организации)'
                });
            }else{
                w2ui['med_org_list'].add({
                    recid: 1,
                    dicid: 1,
                    name: '(введите название организации)'
                });
            }

    },
        onDelete: function (event) {
        },
        onSubmit: function (event) {
//            location.reload();
        }
    }

    };
    $('#main').w2layout(config.layout);

    w2ui.layout.content('top', $().w2toolbar(toolbar_cfg));
    w2ui.layout.content('main', $().w2grid(config.med_org_list));
//    w2ui.layout.content('preview', $().w2grid(config.ipra_assoc));


</script>
