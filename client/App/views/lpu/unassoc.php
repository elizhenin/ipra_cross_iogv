<div style="width: 100%; height: 100vh;">
    <div id="main" style="width: 100%; height: 100%;"></div>
</div>
<script type="text/javascript">
    var config = {

        unassoc: {
            name: 'unassoc',
            url: '/api/lpupersonlist?unassoc=1',
            limit: 0,
            method: 'GET', // need this to avoid 412 error on Safari
            show: {
                header: false,
                toolbar: true,
                footer: true,
                toolbarSearch:false
            },
            toolbar:{
                items:[
                    {type: 'button',id:'assoc', caption: 'Это наш!'}

                ],
                onClick: function (target, data) {
                    if (target == 'assoc') {
                        console.log(w2ui.unassoc.crecid);
                        var xhttp = new XMLHttpRequest();
                        var body = 'id=' + encodeURIComponent(w2ui.unassoc.crecid) +
                            '&cmd=' + encodeURIComponent('save-record');
                        xhttp.open("POST", "/api/statipraassoclistcomplete", false);
                        xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                        xhttp.onreadystatechange = function () {
                            w2ui.unassoc.reload();
                        };
                        xhttp.send(body);
                    }

                    }
            },
            columns: [
                {field: 'prgdt', caption: 'Дата выдачи ИПРА', size: '10%'},
                {field: 'lname', caption: 'Фамилия', size: '10%'},
                {field: 'fname', caption: 'Имя', size: '10%'},
                {field: 'sname', caption: 'Отчество', size: '10%'},
                {field: 'bdate', caption: 'Дата рождения', size: '10%'},
                {field: 'prgnum', caption: 'Номер ИПРА', size: '10%'},
                {field: 'snils', caption: 'СНИЛС', size: '10%'},
                {field: 'med_org_txt', caption: 'МО-отправитель', size: '40%'}
            ],
            onClick: function (event) {
                w2ui.unassoc.crecid = event.recid;
//                console.log(event);
            }
        }
    };
    $('#main').w2grid(config.unassoc);

</script>