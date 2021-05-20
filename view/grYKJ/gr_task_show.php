<!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0">
	<meta content="telephone=no" name="format-detection" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
    <title>{$title}</title>
    <link rel="stylesheet" href="/static/css/layui.css" media="all">
	<link rel="stylesheet" href="/static/css/buttonTancu.css">

	<?php echo @$addcss?>
    <script src="/static/layui.js"></script>
    <script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>

	<style>
		.td {
			padding: 25px;
			max-width: 200px;
			
		}	
	
		
	</style>
		
    </head>
    <body   style="background: -webkit-gradient(linear, left top, right top, from(red), to(cyan));  background-attachment: fixed;" >
		<table ><tbody><tr><?php echo $content?></tr></tbody></table>
		<?php echo $content1?>
		{include file="tpl/buttonTancu" /}
        
	</body>
	<script>

        
        
        
        
        
	function backstage_personal()
	{
		window.location.href="/BackstageController/backstage_personal";
    }
        
        
        
        
        
        
        
        
        
        
        
        
function delfile(path,filename)
	{

	
		layui.use(['jquery','layer'], function()
					{
								var $ = layui.jquery,
								layer = layui.layer;
							
								
								$.post("unlinkfunc_gr",{unlinkpath:path,filename:filename},function(result,status)
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
	}
	


	function delayed(time)
	{
	setTimeout(function () 
											{
												location.reload([true]);
											},time);     // {# 每3秒刷新一次页面，这里1000毫秒为1秒 #}
	}

	function rename(path,element)
	{
		layui.use(['jquery','layer'], function()
			{
					
				var $ = layui.jquery,
				layer = layui.layer;
			var oldname = document.getElementById(element).getAttribute('data-oldname');
							
								layer.prompt({title: '请修改文件名字', formType: 3, value: oldname }, function(rename, index)//弹出窗口获取学号
							{
								layer.close(index);//关闭窗口
								var dir = document.getElementById(element).getAttribute('data-dir');
								var newdir = dir+rename;
								$.post("renamefunc_gr",{path:path,newdir:newdir,newname:rename},function(result,status)
								{
									if(status=="success"&&result=="yes")
									{	
										layer.msg('已更名');
										delayed(2000);

									}
									else
									{
									layer.msg('失败');
									
									}


								});
							});
						});		
	
	
	
	}












function search_gr_file()
{
		layui.use(['jquery','layer'], function()
			{
					
				var $ = layui.jquery,
				layer = layui.layer;
				layer.prompt({title: '输入要搜索的文件名请加后缀名', formType: 3 }, function(filename, index)//弹出窗口获取学号
					{
					$.post('/taskShowController/search_gr_file',{filename:filename},function(res){
						var obj=JSON.parse(res);
						if(obj.code==0)
						{
						var file=obj.filename;
						var dir=obj.dir;
						var filepath=obj.filepath;
						var fileDownloadpath=obj.fileDownloadpath;
						$('#button').click();
							$('#content').empty();
							$('#content').html("  <span class=\"stars\"></span><div class=\"svg-wrapper\"><rect class=\"shape\" height=\"40\" width=\"150\" /><div  class=\"text\" ><a id=\"searchfile\" onclick=\"delfile('"+filepath+"','"+file+"')\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px; \"><span class=\"spot\"></span>删除</a> </div></div><br/><div class=\"svg-wrapper\"><rect class=\"shape\" height=\"40\" width=\"150\" /><div  class=\"text\" ><a id=\"searchfile\" onclick=\"rename2('"+filepath+"','"+file+"','"+dir+"')\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px; \"><span class=\"spot\"></span>改名</a> </div></div><br/><div class=\"svg-wrapper\"><rect class=\"shape\" height=\"40\" width=\"150\" /><div  class=\"text\" ><a href=\""+fileDownloadpath+"\" download=\""+file+"\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px; \"><span class=\"spot\"></span>下载</a> </div></div><br/><div class=\"svg-wrapper\"><rect class=\"shape\" height=\"40\" width=\"150\" /><div  class=\"text\" ><a onclick=\"searchfile()\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px; \"><span class=\"spot\"></span>重新搜索</a> </div></div><br/><div class=\"svg-wrapper\"><rect class=\"shape\" height=\"40\" width=\"150\" /><div  class=\"text\" ><a onclick=\"delayed(1000)\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px; \"><span class=\"spot\"></span>重置搜索</a> </div></div><br/>");
						
						layer.close(index);//关闭窗口
						}
						else
						{
							layer.msg(obj.msg);
						}
					});
		
					
					});	
			});
	
}


		//-------------------------------------左下角按钮---------------
	$('#button').click(function(){
  //var buttonId = $(this).attr('id');
  $('#modal-container').removeAttr('class').addClass('one');
  $('body').addClass('modal-active');
})
	
	
	$('#modal-container').click(function(){
  $(this).addClass('out');
  $('body').removeClass('modal-active');
});
//-------------------------------------左下角按钮---------------
		
		

function Authentication(func,path,element)
	{
	
		if("<?php echo @$_COOKIE['grxuehao']?>"&&"<?php echo @$_COOKIE['grpassword']?>")
		{
					
			layui.use(['jquery','layer'], function()
			{
				var $ = layui.jquery,
				layer = layui.layer;
					if(func=="delfile")
					{	
						delfile(path);
					}	

					
				if(func=="addzip")
				{	
					addzip();
				}
				
				if(func=="rename")
				{	
					rename(path,element);
				}
			});
		}
		else
		{
					
			layui.use(['jquery','layer'], function()
			{
				var $ = layui.jquery,
				layer = layui.layer;
				layer.msg('管理员权限，请先进行管理员登录');
			});
		}
		
	}
	function yulan(filepath,filename)
	{
	layui.use(['jquery','layer'],function()
	{
	var $ = layui.jquery,
	layer = layui.layer;   
	var w =($(window).width() * 1);
	var h = ($(window).height() * 1);
	layer.open({
	resize: false,
	title: filename+ '预览',
	shadeClose: true,
	fixed: false, //不固定
	maxmin: true,
	area: [w + 'px', h + 'px'],
	type: 2,
	content: "https://view.officeapps.live.com/op/view.aspx?src={$HOST_DIR}" +filepath});//https://api.idocv.com/view/url?url=
	});
	}
	
	function share()
	{
		  layui.use(['jquery','layer'], function()
							{
							 var $ = layui.jquery,
							layer = layui.layer;
		//先判断是否开通
		$.post('/taskShowController/sharemain',{task:3},function(res){
			var obj=JSON.parse(res);
			if(obj.code==0){
		
							window.location.href= "/taskShowController/share_task_show";
			
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
                        var obj = JSON.parse(res);
				if(obj.code==0)
				   {
					   layer.msg('开通成功');
				
							window.location.href= "/taskShowController/share_task_show";
						
					   
				   }
            else
            {
                layer.msg(obj.msg,function(){ layer.close(index);});
                
            }

					});
				});
				
			});
				
			}
		});
			
	});			
			
	}
	
function addzip()
{
layui.use(['jquery','layer'], function()
			{
				var $ = layui.jquery,
				layer = layui.layer;
var existenceORno="";
					$.post("dir_exist_file_func",{path:"{$zipdir}"},function(existenceORno1,status)//post方式把 “目标文件地址” 发送到数据处理文件
					{
					
					if(existenceORno1=="yes")
					{
							layer.msg('文件已经存在了');
							

					}
					if(existenceORno1=="no")
					{
						var zipTargetpath="{$dir}";
						$.post("addzip",
						{
							path:zipTargetpath,
							zipname:'{$zipallname}' ,
						},function(result,status)
							{
								if(status=="success"){layer.msg('已压缩');}
								delayed(2000);

							});

					}
					
					});
				});
						

}
	
	
	layui.use('upload', function(){
  var $ = layui.jquery
  ,upload = layui.upload;
  upload.render({
    elem: '#upload'
    ,url: '/mobileorpcController/grupload_file' //改成您自己的上传接口
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
							
							window.location.href= "/taskShowController/share_task_show";

								
					});
					 
				   }
                        else
                        {
                            layer.msg(obj.msg);
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
	
	
	</script>
	
	
	</html>