jQuery(document).ready(function(){
	jQuery('.squeen-opts-checkbox-hide-below').each(function(){
		var amount = jQuery(this).data('amount');
		if(!jQuery(this).is(':checked')){
			jQuery(this).closest('tr').nextAll('tr').slice(0,amount).hide();
		}
	});
	jQuery('.squeen-opts-checkbox-hide-below').click(function(){
		var amount = jQuery(this).data('amount');
		jQuery(this).closest('tr').nextAll('tr').slice(0,amount).fadeToggle('slow');
	});
});
