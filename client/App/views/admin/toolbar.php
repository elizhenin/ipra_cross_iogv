<script type="text/javascript">
    var toolbar_cfg =
    {
        name: 'toolbar',
        items: [
            {type: 'button', id: 'users', caption: 'Пользователи', img: 'icon-folder'},
            {type: 'button', id: 'med_org_dic', caption: 'Мед.организации', img: 'icon-folder'},
//            {type: 'button', id: 'journal_users', caption: 'Журнал входа', img: 'icon-folder'},
 //                   {type: 'html', id: 'recovery',
  //                    html:'<form method="post" enctype="multipart/form-data">'+
    //                  '<label>Загрузить prg0 CSV:'+
      //            '<input name="prg0" type="file" accept=".csv" onchange="this.form.submit()"/>'+
        //              '</label></form>'},
            {type: 'spacer'},
            {type: 'break', id: 'break_before_name'},
            {type: 'button', id: 'user_name', caption: '<?=$user['login']?>', hint: 'Сменить пароль'},
            {type: 'break', id: 'break_after_name'},
            {type: 'button', id: 'logout', caption: 'Сменить пользователя', hint: 'Завершить работу'}
        ],
        onClick: function (event) {
            console.log('Target: ' + event.target, event);
            if (event.target == 'med_org_dic') {
                location.href = '/admin/medorgedit';
            }
            if (event.target == 'users') {
                location.href = '/admin';
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
                    { field: 'email', type: 'email' }
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