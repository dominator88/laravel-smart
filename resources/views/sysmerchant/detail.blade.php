@extends('backend::public.layout')
@section('content')
<div class="page-content">
    <!-- BEGIN PAGE BAR -->
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li><a href="<?= $param['uri']['module'] ?>">首页</a> <i class="fa fa-circle"></i></li>
            <li><span><?= $param['pageTitle'] ?></span></li>
        </ul>
    </div>
    <!-- END PAGE BAR -->

    <div class="row" style="margin-top: 16px">
        <!-- Main Portlet Start -->
        <div class="portlet light bordered" id="tablePortlet">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-settings"></i>
                    <span class="caption-subject uppercase"><?= $param['pageTitle'] ?></span>
                </div>
                <div class="actions">

                </div>
            </div>
            <div class="portlet-body">
                <div class="tiles">
                    <a href="<?= full_uri('backend/Mersysuser/index' , ['merId' => $merId ]) ?>">
                        <div class="tile bg-blue-hoki">
                            <div class="tile-body">
                                <i class="fa fa-users"></i>
                            </div>
                            <div class="tile-object">
                                <div class="name">
                                    系统用户
                                </div>
                            </div>
                        </div>
                    </a>
                    <!--
                    <div class="tile bg-red-sunglo">
                        <div class="tile-body">
                            <i class="fa fa-shopping-bag"></i>
                        </div>
                        <div class="tile-object">
                            <div class="name">
                                订单
                            </div>
                        </div>
                    </div>
                    <div class="tile bg-green-turquoise">
                        <div class="tile-body">
                            <i class="fa fa-wechat"></i>
                        </div>
                        <div class="tile-object">
                            <div class="name">
                                微信设置
                            </div>
                        </div>
                    </div>
                    -->
                </div>

                <div class="back-btn-row">
                    <hr>
                    <a href="javascript:history.go(-1);" class="btn btn-lg default "><i class="icon-arrow-left"></i> 返回</a>
                </div>
            </div>
        </div> <!-- Main Portlet Start -->
    </div>

</div><!-- END CONTENT -->

@stop