@extends('backend::public.layout')
@section('content')
<!-- BEGIN CONTENT BODY -->
<div class="page-content">

    <!-- BEGIN PAGE BAR -->
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li> <a href="<?= $param['uri']['base']?>">首页</a> <i class="fa fa-circle"></i> </li>
            <li> <span><?= $param['pageTitle'] ?></span> </li>
        </ul>
    </div>
    <!-- END PAGE BAR -->

    <!-- BEGIN PAGE CONTENT INNER -->
    <div class="row" id="gridPortlet" style="margin-top: 16px">
        <div class="col-md-12 col-md-12">
            <!-- BEGIN PORTLET-->
            <div class="portlet light">
                <div class="portlet-title">
                    <div class="caption caption-md">
                        <i class="icon-settings font-dark"></i>
                        <span class="caption-subject font-dark bold uppercase"><?= $param['pageTitle'] ?></span>
                        <!-- <span class="caption-helper hide">weekly stats...</span>-->
                    </div>
                    <div class="actions">

                    </div>
                </div>

                <div class="portlet-body">
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="nav nav-tabs" id="type_tabs">
                                <li class="active">
                                    <a data-toggle="tab" href="#system" data-type="system" aria-expanded="true">管理模块</a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#api" data-type="api" aria-expanded="false">API接口</a>
                                </li>
                            </ul>
                            <div class="tab-content" style="margin-top: 32px">
                                <div id="system" class="tab-pane fade active in">
                                    <form id="systemForm" class="form-horizontal">
                                        <div class="form-body">

                                            <!-- start item -->
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">模块</label>
                                                <div class="col-md-7">
                                                    {!! form_radios('module',$param['module']) !!}

                                                </div>
                                            </div><!-- end item -->

                                            <!-- start item -->
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">表名</label>
                                                <div class="col-md-7">
                                                    <select name="tableName" class="form-control input-inline input-medium">
                                                        <?= form_options_rows( $tables , 'tableName', 'tableName' ) ?>
                                                    </select>

                                                    <div class="help-inline">
                                                        <button class="btn default" id="getSystemInfoBtn" type="button">
                                                            <i class="fa fa-search"></i> 查询
                                                        </button>
                                                    </div>
                                                </div>
                                            </div><!-- end item -->
                                        </div>
                                    </form>

                                    <form id="systemComponentsForm" class="form-horizontal">
                                        {!! csrf_field() !!}
                                        <div class="form-body">
                                            <hr>
                                            <h5><i class="fa fa-cubes"></i> 选择组件</h5>
                                            <!-- start item -->
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">表类型</label>
                                                <div class="col-md-7">
                                                    <?= form_radios("tableType" , $param['tableType'] ) ?>
                                                </div>
                                            </div><!-- end item -->

                                            <!-- start item -->
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">视图类型</label>
                                                <div class="col-md-7">
                                                    <?= form_radios('viewType' , $param['viewType']) ?>
                                                </div>
                                            </div><!-- end item -->

                                            <!-- start item -->
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">上传字段</label>
                                                <div class="col-md-7">
                                                    <select name="upload" class="form-control select2" multiple
                                                            data-placeholder="选择上传字段" ></select>
                                                </div>
                                            </div><!-- end item -->

                                            <!-- start item -->
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">编辑框字段</label>
                                                <div class="col-md-7">
                                                    <select name="editor" class="form-control select2" multiple
                                                            data-placeholder="选择编辑框字段" style="width: 100%"></select>
                                                </div>
                                            </div><!-- end item -->

                                            <!-- start item -->
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">select2字段</label>
                                                <div class="col-md-7">
                                                    <select name="select2" class="form-control select2" multiple
                                                            data-placeholder="选择select2字段字段" style="width: 100%"></select>
                                                </div>
                                            </div><!-- end item -->

                                            <div class="form-actions" style="margin-bottom: 16px">
                                                <div class="row">
                                                    <div class="col-md-offset-3 col-md-7">
                                                        <button class="btn red createSystemBtn" data-temp="all" type="button">
                                                            <i class="fa fa-code"></i> 生成全部
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            <hr>
                                            <h5><i class="fa fa-code"></i> 单个生成</h5>
                                            <!-- start item -->
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">模型(Model)</label>
                                                <div class="col-md-7">
                                                    <button class="btn default createSystemBtn" data-temp="model" disabled>
                                                        <i class="fa fa-cog"></i> 生成
                                                    </button>
                                                    <button class="btn red deleteSystemBtn" data-temp="model" disabled>
                                                        <i class="fa fa-trash"></i> 删除
                                                    </button>
                                                </div>
                                            </div><!-- end item -->

                                            <!-- start item -->
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">服务(Service)</label>
                                                <div class="col-md-7">
                                                    <button class="btn default createSystemBtn" data-temp="service" disabled>
                                                        <i class="fa fa-cog"></i> 生成
                                                    </button>
                                                    <button class="btn red deleteSystemBtn" data-temp="service" disabled>
                                                        <i class="fa fa-trash"></i> 删除
                                                    </button>
                                                </div>
                                            </div><!-- end item -->

                                            <!-- start item -->
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">控制器(Controller)</label>
                                                <div class="col-md-7">
                                                    <button class="btn default createSystemBtn" data-temp="controller" disabled>
                                                        <i class="fa fa-cog"></i> 生成
                                                    </button>
                                                    <button class="btn red deleteSystemBtn" data-temp="controller" disabled>
                                                        <i class="fa fa-trash"></i> 删除
                                                    </button>
                                                </div>
                                            </div><!-- end item -->

                                            <!-- start item -->
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">视图(View)</label>
                                                <div class="col-md-7">
                                                    <button class="btn default createSystemBtn" data-temp="view" disabled>
                                                        <i class="fa fa-cog"></i> 生成
                                                    </button>
                                                    <button class="btn red deleteSystemBtn" data-temp="view" disabled>
                                                        <i class="fa fa-trash"></i> 删除
                                                    </button>
                                                </div>
                                            </div><!-- end item -->

                                            <!-- start item -->
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Javascript</label>
                                                <div class="col-md-7">
                                                    <button class="btn default createSystemBtn" data-temp="js" disabled>
                                                        <i class="fa fa-cog"></i> 生成
                                                    </button>
                                                    <button class="btn red deleteSystemBtn" data-temp="js" disabled>
                                                        <i class="fa fa-trash"></i> 删除
                                                    </button>
                                                </div>
                                            </div><!-- end item -->

                                        </div>
                                    </form><!-- end sys_form -->
                                </div>
                                <div id="api" class="tab-pane fade">
                                    <!-- start api form -->
                                    <form id="apiForm" class="form-horizontal">
                                        <div class="form-body">

                                            <!-- start item -->
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">描述</label>
                                                <div class="col-md-7">
                                                    <input type="text" name="desc" placeholder="描述" class="form-control input-inline">
                                                    <span class="help-inline">如: 用户登录 </span>
                                                </div>
                                            </div><!-- end item -->

                                            <!-- start item -->
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">目录</label>
                                                <div class="col-md-7">
                                                    <input type="text" name="directory" placeholder="目录" class="form-control input-inline">
                                                    <span class="help-inline">如: auth </span>
                                                </div>
                                            </div><!-- end item -->
                                            <!-- start item -->
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">名称</label>
                                                <div class="col-md-7">
                                                    <input type="text" name="name" placeholder="名称" class="form-control input-inline">
                                                    <span class="help-inline">如: Login </span>
                                                </div>
                                            </div><!-- end item -->
                                            <!-- start item -->
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">接口版本</label>
                                                <div class="col-md-7">
                                                    <?= form_radios('apiVersion' , $param['apiVer']) ?>
                                                </div>
                                            </div><!-- end item -->
                                            <!-- start item -->
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">是否验证用户</label>
                                                <div class="col-md-7">
                                                    <?= form_radios( 'authUser' , $param['apiAuthUser'] ) ?>
                                                </div>
                                            </div><!-- end item -->
                                            <!-- start item -->
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">参数</label>
                                                <div class="col-md-7">
                                                    <?php foreach( $param['apiParams'] as $key => $item ) :?>
                                                    <div class="checkbox-list">
                                                        <label>
                                                            <input type="checkbox" name="params[]" value="<?= $key ?>">
                                                            <?= $item ?>
                                                        </label>
                                                    </div>
                                                    <?php endforeach ; ?>
                                                </div>
                                            </div><!-- end item -->
                                            <hr>

                                            <div class="form-actions" style="margin-bottom: 60px;">
                                                <div class="row">
                                                    <div class="col-md-offset-3 col-md-7">
                                                        <button class="btn red btn-lg" id="createApiBtn" type="button">
                                                            <i class="fa fa-code"></i> 创建
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form><!-- end api form -->
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- END PORTLET-->
        </div>
    </div>
    <!-- END PAGE CONTENT INNER -->
</div><!-- END CONTENT BODY -->
    @stop