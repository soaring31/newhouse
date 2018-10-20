/*!
 * @name {{ name }} - 通用工具类
 * @author {{ author }}
 * @date {{ now()|date('Y-m-d') }}
 */
// 兼容低版本ie的提示信息
if (!window.console) {
    window.console = {
        log: function(msg) {
            document.title += ' Log：' + msg;
        }, 
        warn: function(msg) {
            document.title += ' Warn：' + msg;
        }, 
        error: function(msg) {
            document.title += ' Error：' + msg;
        }
    }
}