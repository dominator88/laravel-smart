/**
 * 模拟器文档 JS
 *
 * @author Zix
 * @version 2.0 ,  2016-05-06
 */

var SimulatorReadme = {
  init : function () {
    var self = this;

    //重新设置菜单
    if ( ! empty( Param.uri.menu ) ) {
      Layout.setSidebarMenuActiveLink( 'set' , 'a[data-uri="' + Param.uri.menu + '"]' );
    }

  }
};