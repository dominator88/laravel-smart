var Index = {
	init : function(){
		// 指定图表的配置项和数据
		var usersOption = {
			tooltip : {
				trigger : 'axis'
			} ,
			legend : {
				data : ['用户注册']
			} ,
			grid : {
				left : '3%' ,
				right : '4%' ,
				bottom : '3%' ,
				containLabel : true
			} ,
			xAxis : [
				{
					type : 'category' ,
					boundaryGap : true ,
					data : Param.charts.users.period
				}
			] ,
			yAxis : [
				{
					type : 'value'
				}
			] ,
			series : [
				{
					name : '注册人数' ,
					type : 'line' ,
					data : Param.charts.users.data
				}
			]
		};

		var apiOption = {
			color: ['#3398DB'],
			tooltip : {
				trigger: 'axis',
				axisPointer : {            // 坐标轴指示器，坐标轴触发有效
					type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
				}
			},
			grid : {
				left : '3%' ,
				right : '4%' ,
				bottom : '3%' ,
				containLabel : true
			} ,
			xAxis : [
				{
					type : 'category' ,
					data : Param.charts.api.period,
					axisTick: {
						alignWithLabel: true
					}
				}
			] ,
			yAxis : [
				{
					type : 'value'
				}
			] ,
			series : [
				{
					name : '访问量' ,
					type : 'bar' ,
					barWidth: '60%',
					label: {
						normal: {
							show: true,
							position: 'top'
						}
					},
					data : Param.charts.api.data
				}
			]
		};

		var userChart = echarts.init(document.getElementById('userChart'));
		userChart.setOption(usersOption);

		var apiChart = echarts.init(document.getElementById('apiChart'));
		apiChart.setOption(apiOption);
	}
};