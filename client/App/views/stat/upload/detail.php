<div style="width: 100%; height: 100vh;">
    <div id="main" style="width: 100%; height: 100%;"></div>
</div>

<script type="text/javascript">
    var config = {
        person_detail: {
            show: {columnHeaders: false},
            name: 'person_detail',
            columns: [
                {
                    field: 'name',
                    caption: 'Name',
                    size: '150px',
                    style: 'background-color: #efefef; border-bottom: 1px solid white; padding-right: 0px;',
                    attr: "align=right"
                },
                {field: 'value', caption: 'Value', size: '100%', editable: {type: 'text'}}
            ],
            records: [

                {recid: 0, name: 'Округ:', value: "<?=$person['okrug']?>"},
                {recid: 1, name: 'Регион:', value: "<?=$person['region']?>"},
                {recid: 2, name: 'Дата актуальности:', value: "<?=$person['dt']?>"},
                {recid: 3, name: 'СНИЛС:', value: "<?=$person['snils']?>"},
                {recid: 4, name: 'Фамилия:', value: "<?=$person['lname']?>"},
                {recid: 5, name: 'Имя:', value: "<?=$person['fname']?>"},
                {recid: 6, name: 'Отчество:', value: "<?=$person['sname']?>"},
                {recid: 7, name: 'Дата рождения:', value: "<?=$person['bdate']?>"},
                {recid: 8, name: 'Пол:', value: "<?=$person['gndr']?>"},
                {recid: 9, name: 'Номер протокола:', value: "<?=$person['docnum']?>"},
                {recid: 10, name: 'Дата проведения МСЭ:', value: "<?=$person['docdt']?>"},
                {recid: 11, name: 'Программа:', value: "<?=$person['prg']?>"},
                {recid: 12, name: 'Номер карты ИПР/ПРП:', value: "<?=$person['prgnum']?>"},
                {recid: 13, name: 'Дата выдачи ИПР/ПРП:', value: "<?=$person['prgdt']?>"}
            ]
        }
    };
    $('#main').w2grid(config.person_detail);
</script>