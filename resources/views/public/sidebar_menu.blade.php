<!-- BEGIN SIDEBAR MENU -->
<ul style="padding-top: 20px" data-slide-speed="200"
    data-auto-scroll="true" data-keep-expanded="false" class="page-sidebar-menu  page-header-fixed">
    <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
    <li class="sidebar-toggler-wrapper hide">
        <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
        <div class="sidebar-toggler">
            <span></span>
        </div>
        <!-- END SIDEBAR TOGGLER BUTTON -->
    </li>
    <li class="sidebar-search-wrapper">
        <!-- BEGIN RESPONSIVE QUICK SEARCH FORM -->
        <form method="POST" action="#" class="sidebar-search">
            <a class="remove" href="javascript:;">
                <i class="icon-close"></i>
            </a>
            <div class="input-group">
                <input type="text" placeholder="搜索..." class="form-control">
                <span class="input-group-btn">
            <a class="btn submit" href="javascript:;">
                <i class="icon-magnifier"></i>
            </a>
        </span>
            </div>
        </form>
        <!-- END RESPONSIVE QUICK SEARCH FORM -->
    </li>
   @foreach( $menuData as $item )
   @if ( $item['name'] == '首页' )
    <li class="nav-item start">
        <a class="nav-link nav-toggle" href="/{{  $item['uri']  }}" data-uri="/{{  $item['uri']  }}">
            <i class="{{ $item['icon'] }}"></i>
            <span class="title">首页</span>
            <span class="selected"></span>
        </a>
    </li>
    @else
    <li class="nav-item  ">
        <a class="nav-link nav-toggle" href="javascript:;">
            <i class="{{ $item['icon'] }}"></i>
            <span class="title">{{ $item['name'] }}</span>
            <span class="arrow"></span>
        </a>
        @if( isset( $item['children'] ) )
        <ul class="sub-menu">
            @foreach( $item['children'] as $i )
            <li class="nav-item  ">
                <a class="nav-link " href="/{{  $i['uri']  }}" data-uri="/{{  $i['uri']  }}">
                    <span class="title">{{ $i['name'] }}</span>
                </a>
            </li>
            @endforeach
        </ul>
       @endif
    </li>
    @endif
   @endforeach
</ul>
<!-- END SIDEBAR MENU -->