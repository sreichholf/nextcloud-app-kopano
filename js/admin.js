function setKopanoConfigValue(setting, value) {
	OC.msg.startSaving('#user-kopano-save-indicator');
	OC.AppConfig.setValue('kopano', setting, value);
	OC.msg.finishedSaving('#user-kopano-save-indicator', {status: 'success', data: {message: t('user_kopano', 'Saved')}});
}

$(function() {
	$('#user-kopano input[type="text"], #user-kopano input[type="password"]').change(function(e) {
		var el = $(this);
		$.when(el.focusout()).then(function() {
			var key = $(this).attr('name');
			setKopanoConfigValue(key, $(this).val());
		});
		if (e.keyCode === 13) {
			var key = $(this).attr('name');
			setKopanoConfigValue(key, $(this).val());
		}
	});
});
