<div style="width: 100%; height: 100vh;">
    <div id="main" style="width: 100%; height: 100%;"></div>
</div>
<?= $toolbar_cfg ?>
<script type="text/javascript">
    var med_org =
        [
            {id: 0, text: '(Все организации)'},
            <?php
 if(!empty($med_org)){
  foreach($med_org as $key=>$value){
     ?>
            {id: <?=$value['dicid']?>, name: '<?=htmlspecialchars(trim($value['name']))?>'},
            <?php
        }}
        ?>
        ];

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


        ipra_hot: {
            name: 'ipra_hot',
            url: '/api/statiprahotlist',
            limit: 50,
            method: 'GET', // need this to avoid 412 error on Safari
            header: 'Список истекающих и просроченных ИПРА',
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
                    {type: 'button', id: 'open', caption: 'Открыть', img: 'icon-folder'},
                    <?php
                  if(empty($current_lpu_only)){
                   ?>
                    {type: 'break', id: 'break_first'},
                    {
                        type: 'menu',
                        id: 'medorg',
                        caption: 'Организации',
                        icon: 'fa-table',
                        count: med_org.length - 1,
                        items: [
                            {id: '0', text: '(Все организации)', icon: 'icon-page'},
                            <?php
                             if(!empty($med_org)){
                             foreach($med_org as $value){
                            ?>
                            {
                                id: '<?=$value['dicid']?>',
                                text: '<?=htmlspecialchars(trim($value['name']))?>',
                                icon: 'icon-page',
//                              count: '<?//=$value['prgcomplete']?>//'
                            },
                            <?php
                        }}
                        ?>
                        ]
                    },
                    {type: 'break', id: 'break_before_name'},
                    {type: 'html', id: 'medorg_name', html: '(Все организации)'},
                    {type: 'break', id: 'break_after_name'},
<?php
}
?>
                    {type: 'spacer'},
                    {type: 'button', id: 'csv', caption: 'Выгрузить CSV', img: 'icon-folder'}
                ],
                onClick: function (target, data) {
                    if (target == 'open') {

                        var selected = w2ui.ipra_hot.getSelection();
                        var xhttp = new XMLHttpRequest();
                        var body = 'cmd=' + encodeURIComponent('get-records');
                        for (i = 0; i < selected.length; i++) {
                            body = body + '&search['+i+'][field]=prgdt&search['+i+'][value]=' + encodeURIComponent(w2ui.ipra_ready.get(selected[i]).prgnum.trim());
                        }
                        xhttp.open("GET", "/api/lpupersonlist" + '?' + body, false);
                        xhttp.onreadystatechange = function () {
                           location.href = 'ipra';
                        };
                        xhttp.send(body);
                    }

                    if (target == 'csv') {
                        var csv = 'Дата выдачи;Дата окончания;ФИО;Дата рождения;СНИЛС;№ ИПРА;Мед.организация;' + "\n";
                        for (i = 1; i <= w2ui.ipra_hot.records.length; i++) {
                            csv = csv + w2ui.ipra_hot.records[i - 1].prgdt + ';';
                            csv = csv + w2ui.ipra_hot.records[i - 1].prgenddt + ';';
                            csv = csv + w2ui.ipra_hot.records[i - 1].fio + ';';
                            csv = csv + w2ui.ipra_hot.records[i - 1].bdate + ';';
                            csv = csv + w2ui.ipra_hot.records[i - 1].snils.trim() + ';';
                            csv = csv + w2ui.ipra_hot.records[i - 1].prgnum.trim() + ';';
                            csv = csv + w2ui.ipra_hot.records[i - 1].med_org.trim() + ';';
                            csv = csv + "\n";
                        }
                        csv = $('<div/>').html(csv).text();
                        saveTextAs('\ufeff' + csv, "Срочные.csv");
                    }
                    if (target.substr(0, 7) == 'medorg:') {
                        console.log(target.substr(7, target.length - 7));
                        for (var i = 0; i < med_org.length; i++) {
                            if (med_org[i].id == target.substr(7, target.length - 7)) {
                                this.remove('medorg_name');
                                this.insert('break_after_name', {
                                    type: 'html',
                                    id: 'medorg_name',
                                    html: med_org[i].name
                                });
                                w2ui.ipra_hot.url = '/api/statiprahotlist' + '?search[0][field]=med_org_id&search[0][value]=' + med_org[i].id;
                                w2ui.ipra_hot.reload();
                                w2ui.ipra_hot.med_org_id = med_org[i].id;
                            }
                        }
                    }
                    <?php
                 if(empty($current_lpu_only)){
                  ?>

                    if (target == 'submit') {
                        var selected = w2ui.ipra_hot.getSelection();
                        var xhttp = new XMLHttpRequest();
                        var body = 'cmd=' + encodeURIComponent('submit-records');
                        for (i = 0; i < selected.length; i++) {
                            body = body + '&selected[]=' + selected[i];
                        }
                        xhttp.open("GET", "/api/statiprareadylist" + '?' + body, false);
//                        xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                        xhttp.onreadystatechange = function () {
                            w2ui.ipra_hot.reload();
                        };
                        xhttp.send(body);
                    }
<?php
}
?>
                }
            },
            columns: [
                {field: 'prgdt', caption: 'Дата выдачи', size: '16%', sortable: false},
                {field: 'prgenddt', caption: 'Дата окончания', size: '16%', sortable: false},
                {field: 'fio', caption: 'ФИО', size: '16%', sortable: false},
                {field: 'bdate', caption: 'Дата рождения', size: '16%', sortable: false},
                {field: 'snils', caption: 'СНИЛС', size: '16%', sortable: false},
                {field: 'prgnum', caption: 'Номер ИПРА', size: '16%', sortable: false},
                {field: 'med_org', caption: 'Мед.орг.', size: '26%', sortable: false}
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
    w2ui.layout.content('main', $().w2grid(config.ipra_hot));


</script>