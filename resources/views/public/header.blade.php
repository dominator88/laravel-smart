
<div class="page-header navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner ">
        <!-- BEGIN LOGO -->
        <div class="page-logo">
            <a href="#">
                <img class="logo-default" alt="logo" src="static/themes/global/img/logo.png"> </a>
            <div class="menu-toggler sidebar-toggler">
                <span></span>
            </div>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a data-target=".navbar-collapse" data-toggle="collapse" class="menu-toggler responsive-toggler" href="javascript:;">
            <span></span>
        </a>
        <!-- END RESPONSIVE MENU TOGGLER -->
        <!-- BEGIN TOP NAVIGATION MENU -->
        <div class="top-menu">
            <ul class="nav navbar-nav pull-right">
                <!-- BEGIN USER LOGIN DROPDOWN -->
                <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                <li class="dropdown dropdown-user">
                    <a data-close-others="true" data-hover="dropdown" data-toggle="dropdown" class="dropdown-toggle" href="javascript:;">
                        <img src="{{ isset($user['icon']) ? $user['icon'] : '无图标' }}" class="img-circle" alt="">
                        <span class="username username-hide-on-mobile"> {{ isset($user['username']) ? $user['username'] : '游客' }}</span>
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-default">
                        <li>
                            <a href="javascript:;">
                                <i class="icon-user"></i> {{ isset($user['roles']['role_name']) ? $user['roles']['role_name'] : '角色名' }}
                            </a>
                        </li>
                        <li>
                            <a href="javascript:;" id="chPwdBtn">
                                <i class="icon-lock"></i> 修改密码 </a>
                        </li>
                        <li class="divider"> </li>
                        <li>
                            <a href="{{ full_uri('backend/logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                <i class="icon-logout"></i> 退出 </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    </ul>
                </li>
                <!-- END USER LOGIN DROPDOWN -->

            </ul>
        </div>
        <!-- END TOP NAVIGATION MENU -->
    </div>
    <!-- END HEADER INNER -->
</div>
<div class="clearfix"> </div>
