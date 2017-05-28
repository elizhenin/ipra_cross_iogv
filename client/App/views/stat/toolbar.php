<script type="text/javascript">
    var toolbar_cfg =
    {
        name: 'toolbar',
        items: [
            {type: 'button', id: 'ipra', caption: 'ИПРА', img: 'icon-page'},
            {
                type: 'menu',
                id: 'journals',
                caption: 'Журналы',
                icon: 'fa-table',
                count: 5,
                items: [
//                    {id:0,text:''},
                    {id: 'hot', text: 'Срочные', icon: 'icon-page'},
                    {id: 'unapproved', text: 'Неутвержденные', icon: 'icon-page'},
                    {id: 'approved', text: 'Отгруженные', icon: 'icon-page'},
                    {id: 'foreign', text: 'Неподведомственные', icon: 'icon-page'},
                    {id: 'unassoc', text: 'Несопоставленные', icon: 'icon-page'}

                ]
            },
            {type: 'button', id: 'medorgcount', caption: 'Статистика по организациям', img: 'icon-page'},
            {type: 'button', id: 'upload', caption: 'Импорт XML', img: 'icon-page'},
            {type: 'button', id: 'medorg', caption: 'Мед.организации', img: 'icon-page'},
            {type: 'button', id: 'outgoing', caption: 'Направленные', img: 'icon-page'},
            {type: 'spacer'},
            {type: 'break', id: 'break_before_name'},
            {type: 'button', id: 'user_name', caption: '<?=$user['login']?>', hint: 'Сменить пароль'},
            {type: 'break', id: 'break_after_name'},
            {type: 'button', id: 'logout', caption: 'Сменить пользователя', hint: 'Завершить работу'}
        ],
        onClick: function (event) {
            console.log('Target: ' + event.target, event);
            if (event.target == 'ipra') {
                location.href = '/stat/ipra';
            }

            if (event.target.substr(0, 9) == 'journals:') {
                location.href = '/stat/'+event.target.substr(9, event.target.length - 9);
            }

            if (event.target == 'upload') {
                location.href = '/stat/upload';
            }
            if (event.target == 'medorgcount') {
                location.href = '/stat/medorgcount';
            }
            if (event.target == 'medorg') {
                location.href = '/stat/medorg';
            }
            if (event.target == 'outgoing') {
                location.href = '/stat/outgoing';
            }
            if (event.target == 'user_name') {
                openPopup();
            }

            if (event.target == 'logout') {
                var xhttp = new XMLHttpRequest();
                xhttp.open("POST", "/users/logout", false);
                xhttp.send();
                location.href = '/';
            }

        }
    };
</script>
<script type="application/javascript">
    function openPopup(){
        if (!w2ui.userprofile_popup) {
            $().w2form({
                name: 'userprofileform',
                style: 'border: 0px; background-color: transparent;',
                formURL: '/api/userprofileform',
                url: '/api/userprofileformcomplete',
                fields: [
                    { field: 'id', type: 'hidden', required: true },
                    { field: 'login', type: 'text', required: true },
                    { field: 'password', type: 'text', required: true },
                    { field: 'email', type: 'email' },
                ],
                record: {
                    id            : '<?=$user['id']?>',
                    login    : '<?=$user['login']?>',
                    password     : '<?=$user['password']?>',
                    email         : '<?=$user['email']?>'
                },
                actions: {
                    "save": function () { this.save(); },
                    "reset": function () { this.clear(); },
                }
            });
        }
        $().w2popup('open', {
            title   : 'Настройки профиля',
            body : '<div id="form" style="width: 100%; height: 100%;"></div>',
            style   : 'padding: 15px 0px 0px 0px',
            width   : 500,
            height  : 300,
            showMax : true,
            onToggle: function (event) {
                $(w2ui.userprofile_popup.box).hide();
                event.onComplete = function () {
                    $(w2ui.userprofile_popup.box).show();
                    w2ui.userprofile_popup.resize();
                }
            },
            onOpen: function (event) {
                event.onComplete = function () {
                    // specifying an onOpen handler instead is equivalent to specifying an onBeforeOpen handler, which would make this code execute too early and hence not deliver.
                    $('#w2ui-popup #form').w2render('userprofileform');
                }
            }
        });
    }
</script>