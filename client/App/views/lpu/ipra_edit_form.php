<div class="w2ui-page page-0">
    <div class="w2ui-field w2ui-span10">
        <label>Тип мероприятия:</label>

        <div>
            <select name="typeid" style="width: 100%" disabled="disabled">
                <option value="0">(не определено)</option>
                <?php
                foreach ($rhb_type as $key => $value) {
                    ?>
                    <option value="<?= $key ?>"><?= trim($value) ?></option>
                <?php
                }
                ?>
            </select>
        </div>
    </div>
    <div class="w2ui-field w2ui-span10">
        <label>Подтип мероприятия:</label>

        <div>
            <select name="evntid" style="width: 100%"
                <?=($typeid==2)?'':'disabled="disabled"'?>
                >
                <option value="0" selected>(не определено)</option>
                <?php
                foreach ($rhb_evnt as $key => $value) {
                    ?>
                    <option value="<?= $key ?>"><?= trim($value) ?></option>
                <?php
                }
                ?>
            </select>
        </div>
    </div>
    <div class="w2ui-field w2ui-span10">
        <label>Мероприятие из справочника:</label>

        <div>
            <select name="dicid" style="width: 100%" disabled="disabled">
                <option value="0">(не определено)</option>
                <?php
                foreach ($rhb_dic as $key => $value) {
                    ?>
                    <option value="<?= $key ?>"><?= trim($value) ?></option>
                <?php
                }
                ?>
            </select>
        </div>
    </div>

    <div class="w2ui-field  w2ui-reset">
        Название мероприятия:<br/>
        <input name="name" type="text" style="width: 100%" disabled="disabled" />
    </div>
    <div class="w2ui-field w2ui-span10">
        <label>Дата выполнения мероприятия:</label>
        <div>
            <input name="dt_exc" type="text" maxlength="100" size="16"/>
        </div>
    </div>
    <div class="w2ui-field0  w2ui-reset">
        <div>

            Результат выполнения:<br>
            <?php
            foreach ($rhb_res as $key => $value) {
                ?>
                <div class="w2ui-group">
                    <label>
                        <input name="resid" type="radio" value="<?= $key ?>"/>
                        <?= trim($value) ?>
                    </label>
                </div>
                <br>
            <?php
            }
            ?>
        </div>
    </div>

    <div class="w2ui-group">
        <label>
            <input name="resid" type="radio" value="false"/>
            Если ничего из вариантов выше не подошло, то введите текстом:
        </label>
        <input name="result" type="text" style="width: 100%"/>

    </div>
    <div class="w2ui-field w2ui-span10">
        <label>Параметр 1:</label>

        <div>
            <input name="par1" type="text" style="width: 100%"/>
        </div>
    </div>
    <div class="w2ui-field w2ui-span10">
        <label>Параметр 2:</label>

        <div>
            <input name="par2" type="text" style="width: 100%"/>
        </div>
    </div>
    <div class="w2ui-field w2ui-span10">
        <label>Параметр 3:</label>

        <div>
            <input name="par3" type="text" style="width: 100%"/>
        </div>
    </div>
    <div class="w2ui-field  w2ui-reset">
        Примечание:<br>

        <textarea name="note" style="width: 100%; height: 80px; resize: none">
        </textarea>

    </div>
</div>

<div class="w2ui-buttons">
    <button class="btn" name="abort">Отмена</button>
        <button class="btn" name="save">Записать</button>
</div>
