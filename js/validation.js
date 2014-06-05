/**
 * Validation
 * Handles form validation and the like
 */
var Validation = (function () {
	var me = this;
	
	function toggleClass(el, primCls, secCls) {
		if (el.hasClass(secCls)) {
			el.removeClass(secCls);
			el.addClass(primCls);
		}
		else {
			el.addClass(primCls);
		}
	}
	
	me.validateEmail = function (e) {
		var el = $(e.target);
		var val = el.val();
		if (/[a-z0-9!#$%&'*+/=?^_`{|}~.-]+@[a-z0-9-]+(\.[a-z0-9-]+)*/.test(val)) {
			toggleClass(el, 'valid', 'invalid');
		}
		else {
			toggleClass(el, 'invalid', 'valid');
		}
	};
	
	me.validateRequired = function (e) {
		var el = $(e.target);
		var val = el.val();
		
		if(el.attr('type') === 'email') {
			return false;
		}
		
		if(val && val.length > 0) {
			toggleClass(el, 'valid', 'invalid');
		}
		else {
			toggleClass(el, 'invalid', 'valid');
		}
	};
	
	return me;
}());