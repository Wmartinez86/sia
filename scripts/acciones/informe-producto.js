$(document).ready(function() {
  $('#button').click(function() {
    $.ajax({
      type: "GET",
      url: "informe-producto.php",
      data: "producto=" + $('#producto').val(),
      success: function(msg){
        $('#results').empty();
        $('#results').append(msg);
      }
    });
  });
});
