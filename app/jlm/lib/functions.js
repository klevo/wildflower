/**
 * Wrapper for Firebug's console.debug()
 * 
 * If the browser does not support it nothing happens.
 * 
 * @param object Anything to display in Firebug console
 */
function debug(object) {
	if (window['console']) {
		console.debug(object);
	}
}

/**
 * Wrapper for Firebug's console.log()
 * 
 * If the browser does not support it nothing happens.
 * 
 * @param object Anything to display in Firebug console
 */
function log(object) {
	if (window['console']) {
		console.log(object);
	}
}
