/**
 * SysMerchant JS
 *
 * @author Zix <zix2002@gmail.com>
 * @version 2.0 , 2016-09-28
 */

var SysMerchantDetail = {
  init : function () {
    //重新设置菜单
    if ( ! empty( Param.uri.menu ) ) {
      Layout.setSidebarMenuActiveLink( 'set' , 'a[data-uri="' + Param.uri.menu + '"]' );
    }
  }
};