$(document).ready(function () {
	$('#frmlogin').submit(function() {
		if ($('#anio').val() == '2012' ) {
			var locURL = window.location.href;
			var sub = locURL.split('.');
      var sub = sub[0];
			$(this).attr('action', sub.substr(0, sub.length-1) + '.abastecimiento.pe/login.php');
		}
	});
});