<div style="width: 100%; height: 100vh;">
    <div id="main" style="width: 100%; height: 100%;"></div>
</div>
<?= $toolbar_cfg ?>
<script type="text/javascript">
    var med_org =
        [
            {id: 0, text: '(не указано)'},
            <?php
if(!empty($medorg)){
foreach($medorg as $key=>$value){
    ?>
            {id: <?=$key?>, text: '<?=htmlspecialchars(trim($value))?>'},
            <?php
        }}
        ?>
        ];

    // widget configuration
    var config = {
        layout: {
            name: 'layout',
            padding: 0,
            margin: 10,
            panels: [
                {type: 'top', size: '34', resizable: false},
                {type: 'main', minSize: 300}
            ]
        },
        users_layout: {
            name: 'users_layout',
            padding: 10,
            panels: [
                {type: 'left', size: '50%', resizable: true, minSize: 300},
                {type: 'main', minSize: 300}
            ]
        },
        users_list: {
            name: 'users_list',
            header: 'Список пользователей',
            url: '/api/userlist',
            limit:50,
            method: 'GET',
            show: {
                header: true,
                toolbar: true,
                toolbarAdd: true
//                toolbarDelete    : true
            },
            columns: [
                {field: 'login', caption: 'Имя для входа', size: '20%', sortable: true},
                {field: 'rights', caption: 'Права', size: '10%', sortable: true},
                {field: 'med_org_name', caption: 'Мед.организация', size: '30%', sortable: true},
                {field: 'email', caption: 'Email', size: '30%'},
                {field: 'active', caption: 'Активен', size: '50px',style:'font-size:18px;text-align:center'}
            ],

            onDblClick: function (event) {

                if (!('active' == this.columns[event.column].field)) {
                    w2popup.open({
                        title: 'Редактирование профиля',
                        body: '<iframe src="/admin/useredit?id=' + event.recid + '" style="width: 100%;height: 100%;padding:0px;margin:0px"></iframe>',
                        width: 600,     // width in px
                        height: 300     // height in px

                    });
                }
            },
            onClick: function (event) {

                if ('active' == this.columns[event.column].field) {
                    var xhttp = new XMLHttpRequest();
                    var body = 'recid=' + encodeURIComponent(event.recid) +
                        '&cmd=' + encodeURIComponent('switch-active');
                    xhttp.open("POST", "/api/usersupdate", false);
                    xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhttp.onreadystatechange = function () {
                        w2ui.users_list.reload();
                        w2ui.users_assoc.reload();
                    };
                    xhttp.send(body);
                }
            },
            onAdd: function (event) {
                console.log(event);
                w2popup.open({
                    title: 'Добавление нового профиля',
                    body: '<iframe src="/admin/useredit" style="width: 100%;height: 100%;padding:0px;margin:0px"></iframe>',
                    width: 600,     // width in px
                    height: 300     // height in px

                });
            }
        },
        users_assoc: {
            name: 'users_assoc',
            url: '/api/adminusersassoclist',
            limit: 50,
            method: 'GET', // need this to avoid 412 error on Safari
            header: 'Соответствие мед.организациям',
            show: {
                header: true,
                footer: true
            },
            columns: [
                {field: 'login', caption: 'Логин', size: '10%', sortable: true},
                {
                    field: 'med_org_id', caption: 'Мед.организация из справочника', size: '40%', sortable: true,
                    editable: {type: 'select', items: med_org}
                    ,
                    render: function (record, index, col_index) {
                        var html = '';
                        for (var p in med_org) {
                            if (med_org[p].id == this.getCellValue(index, col_index)) html = med_org[p].text;
                        }
                        return html;
                    }
                }
            ],
            onChange: function (event) {

                if ('med_org_id' == this.columns[event.column].field) {
                    if (event.value_new) {
                    w2ui.users_assoc.records[event.index].med_org_id = event.value_new;

                        var xhttp = new XMLHttpRequest();
                        var body = 'med_org_id=' + encodeURIComponent(w2ui.users_assoc.records[event.index].med_org_id) +
                            '&id=' + encodeURIComponent(event.recid) +
                            '&cmd=' + encodeURIComponent('assoc-medorg');
                        xhttp.open("POST", "/api/adminusersassoclistcomplete", false);
                        xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                        xhttp.onreadystatechange = function () {
                            w2ui.users_list.reload();
                            w2ui.users_assoc.reload();
                        };
                        xhttp.send(body);

                    }
                }
            }
        }
    }

    $(function () {
        // initialization
        $('#main').w2layout(config.layout);
        w2ui.layout.content('top', $().w2toolbar(toolbar_cfg));
        w2ui.layout.content('main', $().w2layout(config.users_layout));
        w2ui.users_layout.content('left', $().w2grid(config.users_list));
        w2ui.users_layout.content('main', $().w2grid(config.users_assoc));

    });

</script>