<div style="width: 100vw; height: 100vh;">
    <div id="main" style="width: 100%; height: 100%;"></div>
</div>
<script type="text/javascript">
    function openPopup() {
        w2alert('Неправильный запрос.<br><br>Нажмите [ОК] для перехода на главный экран', 'API ERROR',function () {
            document.location.href = '/';
        });
    }

    openPopup();

</script>