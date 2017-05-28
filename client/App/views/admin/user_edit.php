<div id="form" style="width: 100%; height: 100%; padding: 0px">
    <div class="w2ui-page page-0">
        <div class="w2ui-field w2ui-span10">
            <label>Логин:</label>

            <div>
                <input name="login" type="text" style="width: 100%"/>
            </div>
        </div>
        <div class="w2ui-field w2ui-span10">
            <label>Пароль:</label>

            <div>
                <input name="password" type="password" style="width: 100%"/>
            </div>
        </div>
        <div class="w2ui-field w2ui-span10">
            <label>Email:</label>

            <div>
                <input name="email" type="email" style="width: 100%"/>
            </div>
        </div>

        <div class="w2ui-field w2ui-span10">
            <label>Права:</label>

            <div>
                <select name="rights" style="width: 100%">
                    <option value="admin">Админ</option>
                    <option value="lpu">Мед.Орг.</option>
                    <option value="stat">Аналитика</option>
                </select>
            </div>
        </div>
        <div class="w2ui-field w2ui-span10">
            <label>Активен:</label>

            <div>
                <input name="active" type="checkbox" style="width: 100%;"/>
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
        user_edit: {
            name: 'user_edit',
            url: '/api/usersupdate',
            fields: [
                {name: 'login', type: 'text', required: true},
                {name: 'password', type: 'password', required: true},
                {name: 'email', type: 'email'},
                {name: 'rights', type: 'select'},
                {name: 'active', type: 'checkbox', html: {caption: 'Активен', attr: 'size="53"'}}
            ],
            record: {
                recid: '<?=(!empty($user['id']))?$user['id']:''?>',
                login: '<?=(!empty($user['login']))?$user['login']:''?>',
                password: '<?=(!empty($user['password']))?$user['password']:''?>',
                email: '<?=(!empty($user['email']))?$user['email']:''?>',
                rights: '<?=(!empty($user['rights']))?$user['rights']:''?>',
                active: '<?=(!empty($user['active']))?$user['active']:''?>'
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
                        parent.location.href = '/';
                    });
                }
            }
        }
    }
    $(function () {
        $('#form').w2form(config.user_edit);
    });
</script>