<div style="width: 100%; height: 100vh;">
    <div id="main" style="width: 100%; height: 100%;"></div>
</div>
<?= $toolbar_cfg ?>
<script type="text/javascript">
<?php
if(!empty($medorg_change)){
?>
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
<?php
}
 ?>
    var search_applied = false;
    var sort_applied = false;
    var config = {
        layout: {
            name: 'layout',
            padding: 0,
            margin: 10,
            panels: [
                {type: 'top', size: '34', resizable: false},
                {type: 'main', minSize: 300},
                {type: 'right', size: '40%', minSize: 300}
            ]
        },
        card_layout: {
            name: 'card_layout',
            padding: 0,
            margin: 10,
            panels: [
                {type: 'top', minSize: 250<?=(empty($medorg_change))?'':'+38'?>}, //for toolbar, when in 'stat' role
                {type: 'main', minSize: 300}
            ]
        },

        person_list: {
            name: 'person_list',
            url: '/api/lpupersonlist',
            limit: 50,
            method: 'GET', // need this to avoid 412 error on Safari
            header: 'Список пациентов',
            show: {
                header: true,
                toolbar: true,
                footer: true
            },
            toolbar: {
                items: [
                    {type: 'spacer'},
                    {type: 'button', id: 'csv', caption: 'Выгрузить в CSV', img: 'icon-folder'},
                    {type: 'button', id: 'unassoc', caption: 'Несопоставленные', img: 'icon-page'}
                ],
                onClick: function (event) {
                    if (event.target == 'unassoc') {
                        w2popup.open({
                            title: 'Список несопоставленных',
                            body: '<iframe src="/<?=$toolbar_cfg->user['rights']?>/ipraunassoc" scrolling="no" style="overflow:hidden;width: 100%;height: 100%;padding:0px;margin:0px"></iframe>',
                            width: 1200,     // width in px
                            height: 600     // height in px

                        });
                    }
                    if (event.target == 'csv') {
                        var csv = 'Дата выдачи;Дата окончания;Фамилия;Иия;Отчество;Дата рождения;№ ИПРА;СНИЛС;Мед.организация;Направившая МО;' + "\n";
                        for (i = 1; i <= w2ui.person_list.records.length; i++) {
                            csv = csv + w2ui.person_list.records[i - 1].prgdt + ';';
                            csv = csv + w2ui.person_list.records[i - 1].prgenddt + ';';
                            csv = csv + w2ui.person_list.records[i - 1].lname.trim() + ';';
                            csv = csv + w2ui.person_list.records[i - 1].fname.trim() + ';';
                            csv = csv + w2ui.person_list.records[i - 1].sname.trim() + ';';
                            csv = csv + w2ui.person_list.records[i - 1].bdate + ';';
                            csv = csv + w2ui.person_list.records[i - 1].prgnum + ';';
                            csv = csv + w2ui.person_list.records[i - 1].snils.trim() + ';';
                            csv = csv + w2ui.person_list.records[i - 1].medorg.trim() + ';';
                            csv = csv + w2ui.person_list.records[i - 1].medorg_sender.trim() + ';';
                            csv = csv + "\n";
                        }
                        csv = $('<div/>').html(csv).text();
                        saveTextAs('\ufeff' + csv, "ИПРА <?=(!empty($medorg_name))?$medorg_name:''?>.csv");
                    }
                }
            },
            columns: [
                {field: 'prgdt', caption: 'Дата выдачи', size: '16%', sortable: true},
                {field: 'prgenddt', caption: 'Дата окончания', size: '16%', sortable: true},
                {field: 'lname', caption: 'Фамилия', size: '16%', sortable: true},
                {field: 'fname', caption: 'Имя', size: '16%', sortable: true},
                {field: 'sname', caption: 'Отчество', size: '16%', sortable: true},
                {field: 'prgnum', caption: 'Номер ИПРА', size: '16%', sortable: true},
                {field: 'snils', caption: 'СНИЛС', size: '16%', sortable: true}
            ],
            onSearch: function(event) {

            },
            onLoad: function(event) {
                var search_text = '';
                <?php
              if(!empty($search)){
              ?>
                    search_text = "<?=$search[0]['value']?>";
                    console.log(search_text);

                if(search_applied == false) {
                    search_applied = true;
                    w2ui['person_list'].search(w2ui['person_list'].last.field, search_text);
                }
                <?php
              }
              ?>
            },
            onClick: function (event) {

                w2ui['person_detail'].clear();
                var record = this.get(event.recid);
                <?php
if(!empty($medorg_change)){
?>
                w2ui['person_detail'].toolbar.items[0].caption = '[!]  ' + record.medorg_sender;
                var medorg_id = record.medorg_id;
                for (var i = 0; i < med_org.length; i++) {
                    if (-1 < record.medorg_sender.indexOf(med_org[i].name)) {
                        medorg_id = med_org[i].id;
                        w2ui['person_detail'].toolbar.items[0].caption = med_org[i].name;
                    }
                }
                w2ui['person_detail'].toolbar.items[1].medorg_id = medorg_id;
                w2ui['person_detail'].toolbar.items[1].prg_id = record.recid;
                <?php
                }
                ?>
                                w2ui['person_detail'].add([
                                    {recid: 1, name: 'СНИЛС:', value: record.snils},
                                    {recid: 2, name: 'ФИО:', value: record.lname+' '+record.fname+' '+record.sname},
                                    {recid: 3, name: 'Дата рождения:', value: record.bdate},
                                    {recid: 4, name: 'Пол:', value: record.gndr},
                                    {recid: 5, name: 'Номер протокола:', value: record.docnum},
                                    {recid: 6, name: 'Номер карты ИПРА:', value: record.prgnum},
                                    {recid: 7, name: 'Дата выдачи:', value: record.prgdt},
                                    {recid: 8, name: 'Дата окончания:', value: record.prgenddt},
                                    {recid: 9, name: 'Прикрепление МО:', value: record.medorg}
                                ]);
                                w2ui['ipra_list'].clear();
                                w2ui['ipra_list'].add(record.ipra_list);
                            }
                        },
                        person_detail: {
                            header: 'Детали',
                            show: {header: true, columnHeaders: false, toolbar: <?=(empty($medorg_change))?'false':'true'?>,
                toolbarEdit: false,
                toolbarReload: false,   // indicates if toolbar reload button is visible
                toolbarColumns: false,  // indicates if toolbar columns button is visible
                toolbarSearch: false    // indicates if toolbar search controls are visible
            },
            name: 'person_detail',
            toolbar: {
                items: [
                    {
                        type: 'menu',
                        id: 'medorg',
                        caption: '(не распределено)',
                        icon: 'fa-table',
                        count: <?=(!empty($med_org))?count($med_org):'0'?>,
                        items: [
                            {id: '0', text: '(не распределено)', icon: 'icon-page'},
                            <?php
                             if(!empty($med_org)){
                             foreach($med_org as $value){
                            ?>
                            {
                                id: '<?=$value['dicid']?>',
                                text: '<?=htmlspecialchars(trim($value['name']))?>',
                                icon: 'icon-page'
                            },
                            <?php
                        }}
                        ?>
                        ]
                    },
                    {type: 'button', id: 'set_mo', caption: 'Применить'}
                ],
                onClick: function (event) {

                    if (event.target == 'set_mo') {
                        console.log(event.item.medorg_id);
                       //sending ajax for re-assign person to medorg
                        var xhttp = new XMLHttpRequest();
                        var params = 'cmd=assign_medorg&prgid=' + event.item.prg_id + '&medorgid='+event.item.medorg_id;
                        xhttp.open("POST", "/api/statpersonmedorgassign", true);
                        xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                        xhttp.onreadystatechange = function () {
                            w2ui['person_detail'].records[9].value = w2ui['person_detail'].toolbar.items[0].caption;
                            w2ui['person_detail'].render();
                        };
                        xhttp.send(params);
                    }
                    if (event.target.substr(0, 7) == 'medorg:') {
                        for (var i = 0; i < med_org.length; i++) {
                            if (med_org[i].id == event.target.substr(7, event.target.length - 7)) {
                                w2ui['person_detail'].toolbar.items[0].caption = med_org[i].name;
                                w2ui['person_detail'].toolbar.items[1].medorg_id = med_org[i].id;
                                w2ui['person_detail'].render();
                            }
                        }
                    }
                }
            },
            columns: [
                {
                    field: 'name',
                    caption: 'Name',
                    size: '120px',
                    style: 'background-color: #efefef; border-bottom: 1px solid white; padding-right: 0px;',
                    attr: "align=right"
                },
                {field: 'value', caption: 'Value', size: '100%', editable: {type: 'text'}}
            ],
            onClick: function (event) {

            }
        },
        ipra_list: {
            name: 'ipra_list',
            limit: 50,
            header: 'Программа',
            show: {
                header: true,
                toolbar: true,
                toolbarEdit: true,
                toolbarReload: false,   // indicates if toolbar reload button is visible
                toolbarColumns: false,   // indicates if toolbar columns button is visible
                toolbarSearch: false,   // indicates if toolbar search controls are visible
                footer: true
            },
            columns: [
                {field: 'type', caption: 'Тип', size: '16%', sortable: false},
                {field: 'event', caption: 'Подтип', size: '16%', sortable: false},
                {field: 'date', caption: 'Дата', size: '16%', sortable: false},
                {field: 'result', caption: 'Результат', size: '16%', sortable: false},
            ],

            onEdit: function (event) {
                var record = this.get(event.recid);
                window.location.href = "/<?=$toolbar_cfg->user['rights']?>/ipra/" + record.recid;
            }
        }
    };
    $('#main').w2layout(config.layout);
    w2ui.layout.content('top', $().w2toolbar(toolbar_cfg));
    w2ui.layout.content('main', $().w2grid(config.person_list));
    w2ui.layout.content('right', $().w2layout(config.card_layout));
    w2ui.card_layout.content('top', $().w2grid(config.person_detail));
    w2ui.card_layout.content('main', $().w2grid(config.ipra_list));

</script>
