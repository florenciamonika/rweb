backend_dialog = {};

backend_dialog.show_dialog_confirm = function(msg, yes_callback){
	var dialog_confirm = jQuery('#dialog-confirm'),
		confirm_msg = jQuery('.confirm-msg', dialog_confirm),
		btn_confirm = jQuery('.btn-primary', dialog_confirm);
	
	confirm_msg.text(msg);
	
	btn_confirm.unbind('click');
	dialog_confirm.modal('show');
	btn_confirm.click(function(e){
		yes_callback(e);
	});
};