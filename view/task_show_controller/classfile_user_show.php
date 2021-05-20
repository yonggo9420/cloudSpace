<!DOCTYPE html>
    <html>
    <head>
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0">
	<meta content="telephone=no" name="format-detection" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
    <lik rel="stylesheet" href="/static/css/layui.css" media="all">
		<link rel="stylesheet" href="/static/css/buttonTancu.css">
		<?php echo @$addcss?>
		    <script src="/static/js/task_show/task_show.js"></script>
    <script src="/static/layui.js"></script>
    

		
    </head>
    <body  style="background: -webkit-gradient(linear, left top, right top, from(red), to(cyan));  background-attachment: fixed;"  >
		<table ><tbody><tr><?php echo $content?></tr></tbody></table>
		<?php echo @$content1?>
	</body>
	<script>
		

	function setshare(filepath,type)
{
	layui.use(['jquery','layer'], function()
			{
					
				var $ = layui.jquery,
				layer = layui.layer;
	if(type=='set')//设置共享
	{
		$.post('sharemain',{task:3},function(res)//先判断是否开通了共享空间
			   {
			var obj =JSON.parse(res);
			if(obj.code==0)//如果开通了
			{
				$.post('setshare',{task:1,filepath:filepath},function(res)
				   {
				var obj =JSON.parse(res);
				layer.msg(obj.msg);


					});	
			}
			else
				{
					//墨绿深蓝风
				layer.alert('检测到您还没有开通共享空间，点击确定后为您开通', {
				  skin: 'layui-layer-molv' //样式类名
				  ,closeBtn: 1
					,anim: 4
				}, function(){
				layer.prompt({title: '请设置共享口令', formType: 1}, function(sharekey, index)
					{
					
					$.post('/taskShowController/sharemain',{task:1,sharekey:sharekey},function(res){
						var obj =JSON.parse(res);
				if(obj.code==0)
				   {
					   layer.msg('开通成功');
					    layer.close(index);
					   	$.post('setshare',{task:1,filepath:filepath},function(res)
				   {
							var w = ($(window).width() * 1);
						var h = ($(window).height() * 1);
						layer.open({
								resize: false,
								title: '共享空间',
								shadeClose: true,
								offset: 'b',
								fixed: false, //不固定
								maxmin: true,
								area: [w + 'px', h + 'px'],
								type: 2,
								content: "/taskShowController/share_task_show",
								});

								
					});
					 
				   }else
                {
                    layer.msg(obj.msg,function(){ layer.close(index);});
                }

					});
				});
				
			});
				}
			
			   });
	}
	else//取消共享
	{
		$.post('setshare',{task:2,filepath:filepath},function(res)
			   {
				var obj =JSON.parse(res);
				layer.msg(obj.msg);
				delayed(2000);
			
				});
	}
	});
	
}




		
		layui.use('upload', function(){
  var $ = layui.jquery
  ,upload = layui.upload;
  upload.render({
    elem: '#upload'
    ,url: '/mobileorpcController/yaoqiu_upload_file' //改成您自己的上传接口
	 ,accept: 'file'
    ,done: function(res){
		if(res.code==0)
		   {
                    delayed(2000);
		    layer.msg(res.filenewname+res.msg,{icon: 6});
		   }
		else
		{
			 layer.msg(res.filenewname+res.msg,{icon: 5});
		}
		
     
		/*//iframe关闭自身
    var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
						parent.layer.close(index);
						*/
    }
  });	


});
		
		
		
		
		
		function delefile()
		{
			layui.use(['jquery','layer'],function()
			{
				var $ = layui.jquery,
				layer = layui.layer;
				layer.prompt({title: '请输入文件ID', formType: 3 }, function(element, index)//弹出窗口获取学号
					{
						layer.close(index);//关闭窗口
						var filepath = document.getElementById(element).getAttribute('data-filepath');
					$.post("unlinkfunc",{unlinkpath:filepath},function(result,status)
								{
									if(status=="success"&&result=="yes")
									{	
										layer.msg('已删除');
										delayed(2000);

									}
									else
									{
									layer.msg('文件不存在');
									
									}
				
					});
			});
		});
			
			
		}
					  
		function delayed(time)
		{
			setTimeout(function () 
			{
				location.reload([true]);
			},time);     // {# 每3秒刷新一次页面，这里1000毫秒为1秒 #}
		}
		
		
		
		
		
function yulan(filepath,filename)
	{
	layui.use(['jquery','layer'],function()
	{
	var $ = layui.jquery,
	layer = layui.layer;
	var w =($(parent.window).width() * 1);
	var h = ($(parent.window).height() * 1);
	parent.layer.open({
	resize: false,
	title: filename+ '预览',
	shadeClose: true,
	fixed: false, //不固定
	maxmin: true,
	area: [w + 'px', h + 'px'],
	type: 2,
	content: "https://view.officeapps.live.com/op/view.aspx?src=" +filepath});
	});
	}
	
</script>
	
	
	</html>