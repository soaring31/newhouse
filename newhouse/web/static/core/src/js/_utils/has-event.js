/*!
 * @name {{ name }} - jquery 扩展
 * @author {{ author }}
 * @date {{ date }}
 */

$.fn.hasEvent = function(e) {
    var fmEvents = $.data(this[0],'events') || $._data(this[0],'events');
    return fmEvents && !!fmEvents[e] || false;
}