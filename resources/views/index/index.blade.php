@extends('backend::public.layout')

@section('content')


<!-- BEGIN CONTENT BODY -->
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <!-- BEGIN PAGE BAR -->
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li><a href="{{ $param['uri']['module'] }}">首页</a> <i class="fa fa-circle"></i></li>
            <li>
                <span>控制台 & 统计</span>
            </li>
        </ul>
    </div>
    <!-- END PAGE BAR -->

    <!-- BEGIN PAGE TITLE-->
    <h3 class="page-title"> 首页  
        <small>控制台 & 统计</small>
    
    
    </h3>
    <!-- END PAGE TITLE-->

    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a href="#" class="dashboard-stat dashboard-stat-v2 blue">
                <div class="visual">
                    <i class="fa fa-file-text"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span data-counter="counterup">{{ isset($stat['articles']) ?: 0 }}</span> 篇
                    </div>
                    <div class="desc"> 文章</div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a href="#" class="dashboard-stat dashboard-stat-v2 red">
                <div class="visual">
                    <i class="fa fa-users"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span data-counter="counterup">{{ isset($stat['users']) ?: 0 }}</span> 个
                    </div>
                    <div class="desc"> 注册用户</div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a href="#" class="dashboard-stat dashboard-stat-v2 green">
                <div class="visual">
                    <i class="fa fa-wifi"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span data-counter="counterup">{{ isset($stat['api']) ?: 0 }}</span> 次
                    </div>
                    <div class="desc"> 今日访问</div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a href="#" class="dashboard-stat dashboard-stat-v2 purple">
                <div class="visual">
                    <i class="fa fa-globe"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span data-counter="counterup">{{ isset($stat['download']) ?: 0 }}</span> 次
                    </div>
                    <div class="desc"> 下载</div>
                </div>
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-xs-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-graph font-dark"></i>
                        <span class="caption-subject font-dark bold uppercase">用户统计</span>
                        <span class="caption-helper">每日注册</span>
                    </div>
                    <div class="actions"></div>
                </div>
                <div class="portlet-body">
                    <div id="userChart" style="width: 100% ; height: 400px"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xs-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-bar-chart font-dark"></i>
                        <span class="caption-subject font-dark bold uppercase">接口访问</span>
                        <span class="caption-helper">每日</span>
                    </div>
                    <div class="actions"></div>
                </div>
                <div class="portlet-body">
                    <div id="apiChart" style="width: 100% ; height: 400px"></div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- END CONTENT BODY -->
@stop
