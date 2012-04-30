$(document).ready(function () {
    $('#enlazar').click(function () {
        var op = $("input[name='op']:checked").val();
        var idpadre = $("#idpadre").val();
        var url = 'orden-compra-hijo.php?idpadre=' + idpadre + '&op=' + op;

        var params = 'location=0,status=0,scrollbars=0,width=960,height=700';
        window.open(url, 'orden-compra-hijo', params);
    });
});