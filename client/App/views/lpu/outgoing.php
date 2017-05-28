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
                {type: 'main', size: '100%', minSize: 300}
            ]
        },
        person_list: {
            name: 'person_list',
            url: '/api/lpuoutgoinglist',
            limit: 50,
            method: 'GET', // need this to avoid 412 error on Safari
            header: 'Список направленных пациентов',
            show: {
                header: true,
                toolbar: true,
                footer: true,
                toolbarEdit: true,
                toolbarAdd: true,
                toolbarDelete: true
            },
            toolbar: {
                items: [
                    {type: 'spacer'},
                    {type: 'break', id: 'break_after_name'},
                    {type: 'button', id: 'csv', caption: 'Выгрузить CSV', img: 'icon-folder'}

                ],
                onClick: function(event){
                    console.log(event.target);
                    if (event.target == 'csv') {
                        var csv = 'Дата направления;Фамилия;Иия;Отчество;Дата рождения;СНИЛС;' + "\n";
                        for (i = 1; i <= w2ui.person_list.records.length; i++) {
                            csv = csv + w2ui.person_list.records[i - 1].dt + ';';
                            csv = csv + w2ui.person_list.records[i - 1].lname.trim() + ';';
                            csv = csv + w2ui.person_list.records[i - 1].fname.trim() + ';';
                            csv = csv + w2ui.person_list.records[i - 1].sname.trim() + ';';
                            csv = csv + w2ui.person_list.records[i - 1].bdate + ';';
                            csv = csv + w2ui.person_list.records[i - 1].snils.trim() + ';';
                            csv = csv + "\n";
                        }
                        csv = $('<div/>').html(csv).text();
                        saveTextAs('\ufeff' + csv, "Направленные <?=(!empty($medorg_name))?$medorg_name:''?>.csv");
                    }
                }
            },
            columns: [
                {field: 'dt', caption: 'Дата отправления', size: '16%', sortable: true},
                {field: 'lname', caption: 'Фамилия', size: '16%', sortable: true},
                {field: 'fname', caption: 'Имя', size: '16%', sortable: true},
                {field: 'sname', caption: 'Отчество', size: '16%', sortable: true},
                {field: 'bdate', caption: 'Дата рождения', size: '16%', sortable: true},
                {field: 'snils', caption: 'СНИЛС', size: '16%', sortable: true},
                {field: 'medorgexecutorid', caption: 'Мед.орг. исполнитель', size: '16%', sortable: true}
            ],
            onAdd: function (){
                w2popup.open({
                    title: 'Добавление нового пациента',
                    body: '<iframe src="/lpu/outgoingedit" style="width: 100%;height: 100%;padding:0px;margin:0px"></iframe>',
                    width: 500,     // width in px
                    height: 500     // height in px

                });

            },
            onEdit:function(event){
                console.log(event);
                w2popup.open({
                    title: 'Редактирование пациента',
                    body: '<iframe src="/lpu/outgoingedit?id=' + event.recid + '" style="width: 100%;height: 100%;padding:0px;margin:0px"></iframe>',
                    width: 500,     // width in px
                    height: 500     // height in px

                });
            },
            onDblClick: function (event) {

            }
        }
    };
    $('#main').w2layout(config.layout);
    w2ui.layout.content('top', $().w2toolbar(toolbar_cfg));
    w2ui.layout.content('main', $().w2grid(config.person_list));

</script>