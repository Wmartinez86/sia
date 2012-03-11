$(document).ready(function () {
    $('.show-branch').click(function () {
        $(this).parent().next().toggle();
    });
});