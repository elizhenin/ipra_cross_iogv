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
                lineNumbers: true,
                toolbar: true,
                footer: true,
                toolbarSearch: false
            },
            url: '/api/medorgcount?<?=(!empty($from))?'from='.$from.'&':''?><?=(!empty($to))?'to='.$to:''?>',
            method: 'POST',
            limit: 0,
            toolbar: {
                items: [
                    {type: 'html', id: 'calendar', html: '<form id="period_form" method="get">С:<input id="from" name="from" type="text" value="<?=(!empty($from))?$from:''?>"/> По:<input id="to" name="to" type="text" value="<?=(!empty($to))?$to:''?>"/></from>'},
                    {type: 'button', id: 'form-submit', caption: 'Задать период'},
                    {type: 'break',id:'after_form'},
                    {type: 'spacer'},
                    {type: 'button', id: 'csv', caption: 'Выгрузить CSV', img: 'icon-folder'}
                ],
                onRefresh: function(event){
                    $('#from').w2field('date',{format: 'yyyy-mm-dd'});
                    $('#to').w2field('date',{format: 'yyyy-mm-dd'});
                    $('#date_submit').w2field('button');
                },
                onClick: function (event) {
                    console.log('Target: ' + event.target, event);
                    if (event.target == 'form-submit') {
                        $('#period_form').submit();
                    }
                    if (event.target == 'csv') {
                        var csv = 'Мед.организация;ИПРА всего;ИПРА исполненных;ИПРА частично исполненных;' + "\n";
                        for (i = 1; i <= w2ui.med_org_list.records.length; i++) {
                            csv = csv + w2ui.med_org_list.records[i - 1].name.trim() + ';';
                            csv = csv + w2ui.med_org_list.records[i - 1].persons + ';';
                            csv = csv + w2ui.med_org_list.records[i - 1].persons_ready + ';';
                            csv = csv + w2ui.med_org_list.records[i - 1].persons_partially + ';';
                            csv = csv + "\n";
                        }
                        csv = $('<div/>').html(csv).text();
                        saveTextAs('\ufeff' + csv, "Свод.csv");
                    }
                 }
                },

                columns: [
                {field: 'name', caption: 'Мед. организации', size: '40%', editable: {type: 'text'}},
                {field: 'persons', caption: 'ИПРА всего', size: '20%', editable: {type: 'text'}},
                {field: 'persons_ready', caption: 'ИПРА исполненных', size: '20%', editable: {type: 'text'}},
                {field: 'persons_partially', caption: 'ИПРА частично исполненных', size: '20%', editable: {type: 'text'}}
            ]
        }
    };
    $('#main').w2layout(config.layout);

    w2ui.layout.content('top', $().w2toolbar(toolbar_cfg));
    w2ui.layout.content('main', $().w2grid(config.med_org_list));
</script>