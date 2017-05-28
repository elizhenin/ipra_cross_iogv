<div id="form" style="width: 100%; height: 100vh; padding: 0px">
    <input name="id" type="hidden"/>

    <div class="w2ui-page page-0">
        <div class="w2ui-field w2ui-span10">
            <label>Дата отправления:</label>

            <div>
                <input name="dt" type="text" style="width: 100%"/>
            </div>
        </div>
        <div class="w2ui-field w2ui-span10">
            <label>Фамилия:</label>

            <div>
                <input name="lname" type="text" style="width: 100%"/>
            </div>
        </div>
        <div class="w2ui-field w2ui-span10">
            <label>Имя:</label>

            <div>
                <input name="fname" type="text" style="width: 100%"/>
            </div>
        </div>
        <div class="w2ui-field w2ui-span10">
            <label>Отчество:</label>

            <div>
                <input name="sname" type="text" style="width: 100%"/>
            </div>
        </div>
        <div class="w2ui-field w2ui-span10">
            <label>Дата рождения:</label>

            <div>
                <input name="bdate" type="text" style="width: 100%"/>
            </div>
        </div>
        <div class="w2ui-field w2ui-span10">
            <label>СНИЛС:</label>

            <div>
                <input name="snils" type="text" style="width: 100%"/>
            </div>
        </div>
        <div class="w2ui-field w2ui-span10">
            <label>Медорганизация:</label>

            <div>
                <select name="medorgexecutorid" style="width: 100%">
                    <option value="0">(не определено)</option>
                    <?php
                    foreach ($medorg as $key => $value) {
                        ?>
                        <option value="<?= $key ?>"><?= trim($value) ?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
        </div>

    </div>
    <div class="w2ui-buttons">
        <button class="btn" name="reset">Очистить</button>
        <button class="btn" name="save">Записать</button>
    </div>
</div>
<script type="application/javascript">
    var config = {
        outgoing_edit: {
            name: 'outgoing_edit',
            url: '/api/lpuoutgoingupdate',
            fields: [
                {name: 'dt', type: 'date', options: {format: 'yyyy-mm-dd'}, required: true},
                {name: 'lname', type: 'text', required: true},
                {name: 'fname', type: 'text', required: true},
                {name: 'sname', type: 'text'},
                {name: 'bdate', type: 'date', options: {format: 'yyyy-mm-dd'}, required: true},
                {name: 'snils', type: 'text', required: true},
                {name: 'medorgexecutorid', type: 'select'}
            ],
            record: {
                id: '<?=(!empty($item['id']))?trim($item['id']):''?>',
                dt: '<?=(!empty($item['dt']))?trim($item['dt']):''?>',
                lname: '<?=(!empty($item['lname']))?trim($item['lname']):''?>',
                fname: '<?=(!empty($item['fname']))?trim($item['fname']):''?>',
                sname: '<?=(!empty($item['sname']))?trim($item['sname']):''?>',
                bdate: '<?=(!empty($item['bdate']))?trim($item['bdate']):''?>',
                snils: '<?=(!empty($item['snils']))?trim($item['snils']):''?>',
                medorgexecutorid: '<?=(!empty($item['medorgexecutorid']))?trim($item['medorgexecutorid']):''?>'
            },
            actions: {
                "reset": function () {
                    this.clear();
                },
                "save": function () {
                    console.log(this.record);
                    var errors = this.validate();
                    if (errors.length > 0) return;
                    this.save({}, function (data) {
                        if (data.status == 'error') {
                            console.log('ERROR: ' + data.message);
                            return;
                        }
                        parent.location.href = '/lpu/outgoing';
                    });
                }
            }
        }
    }
    $(function () {
        $('#form').w2form(config.outgoing_edit);
    });
</script>