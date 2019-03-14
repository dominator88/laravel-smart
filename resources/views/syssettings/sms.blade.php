@extends('Backend::public.layout')
@section('content')
<!-- BEGIN CONTENT BODY -->
{!! csrf_field() !!}
<div class="page-content">
	<!-- BEGIN PAGE BAR -->
	<div class="page-bar">
		<ul class="page-breadcrumb">
			<li> <a href="<?=$param['uri']['module']?>">首页</a> <i class="fa fa-circle"></i> </li>
			<li> <span><?=$param['pageTitle']?></span> </li>
		</ul>
	</div>
	<!-- END PAGE BAR -->
<div class="row" style="margin-top: 16px">
    <!-- Main Portlet Start -->
    <div class="portlet light bordered" id="tablePortlet">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-settings"></i>
                <span class="caption-subject uppercase"><?=$param['pageTitle']?></span>
            </div>
            <div class="actions">
                <a href="" class="btn btn-circle btn-sm btn-primary"><i class="fa fa-file"></i>
                    接口说明</a>
            </div>
        </div>

        <div class="portlet-body">
            <!-- uri start -->




        </div>
    </div> <!-- Main Portlet Start -->
</div>

</div>


<!-- END CONTENT BODY -->
@stop