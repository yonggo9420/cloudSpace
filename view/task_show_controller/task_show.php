<!DOCTYPE html>
    <html>
    <head>
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0">
	<meta content="telephone=no" name="format-detection" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
    <title>{$title}</title>
    <link rel="stylesheet" href="/static/css/layui.css" media="all">
	<link rel="stylesheet" href="/static/css/buttonTancu.css">
		
		<?php echo @$addcss?>
	<script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="/static/layui.js"></script>
	
		
	<style>
		.td {
			padding: 25px;
		}	
		
	</style>
		
    </head>
    <body   style="background: -webkit-gradient(linear, left top, right top, from(red), to(cyan));  background-attachment: fixed;" >
	<?php echo $content1?>
	<table ><tbody><tr><?php echo $content?></tr></tbody></table>
		
		{include file="tpl/buttonTancu" /}
	</body>
	<script>  
function setfile(){
    layui.use(['jquery','layer'], function()
			{
					
				var $ = layui.jquery,
				layer = layui.layer;
    
    $.post("searchfile",{type:'number',task:1,content:content},function(result,status)
						{
                    
							
						var obj =JSON.parse(result);
							if(obj.code==0){
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
									layer.msg('该学生没有上交文件哦');
									layer.close(index);//关闭窗口
								}
						});
						});
    
    
    
}


function searchfile()
		{
				layui.use(['jquery','layer'], function()
			{
					
				var $ = layui.jquery,
				layer = layui.layer;
				layer.prompt({title: '输入要搜索的学号或姓名', formType: 3 }, function(content, index)//弹出窗口获取学号
					{
					if(!isNaN(content))
					{
					    $.post("searchfile",{type:'number',task:2,content:content},function(result,status)
						{
                        var obj =JSON.parse(result);
                    if(obj.code==0)
                       {
                        layer.close(index);
                             //自定页
                        layer.open({
                          type: 1,
                          title:'文件:'+obj.filenumber+'个',
                          skin: 'layui-layer-demo', //样式类名
                          anim: 4,
                          shadeClose: true, //开启遮罩关闭
                          content: obj.msg
                        });
                       }else
                        {
                            layer.msg(obj.msg);
                        }
                       

                            
                        });
					
					 }
					else
					{
						 $.post("searchfile",{type:'name',task:2,content:content},function(result,status)
						{
                        var obj =JSON.parse(result);
                         if(obj.code==0)
                       {
                        layer.close(index);
                             //自定页
                        layer.open({
                          type: 1,
                             title:'文件:'+obj.filenumber+'个',
                          skin: 'layui-layer-demo', //样式类名
                          anim: 4,
                          shadeClose: true, //开启遮罩关闭
                          content: obj.msg
                        });
                       }else
                        {
                            layer.msg(obj.msg);
                        }

                            
                        });
					
					}
						
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
	
		if("<?php echo @$_COOKIE['xuehao']?>"&&"<?php echo @$_COOKIE['password']?>")
		{
					
			layui.use(['jquery','layer'], function()
			{
				var $ = layui.jquery,
				layer = layui.layer;
					if(func=="delfile")
					{	
						delfile(path,element);
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
	

        
         function setfile(file,filepath,fileDownloadpath,dir){
         layui.use(['jquery','layer'], function()
                {

				var $ = layui.jquery,
				layer = layui.layer;
						 //自定页
                layer.open({
                  type: 1,
                  skin: 'layui-layer-demo', //样式类名
                  anim: 4,
                  shadeClose: true, //开启遮罩关闭
                  content: " <div style=\"text-align:center\"> <a  onclick=\"delfile('"+filepath+"','"+file+"')\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px; \">删除</a><br/><a  onclick=\"rename2('"+filepath+"','"+file+"','"+dir+"')\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px; \">改名</a> <br/><a href=\""+fileDownloadpath+"\" download=\""+file+"\" >下载</a></div>"
							
						 
        });
         });
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
								$.post("renamefunc",{path:path,newdir:newdir,newname:rename},function(result,status)
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



function delfile(path,filename)
	{

	
		layui.use(['jquery','layer'], function()
					{
								var $ = layui.jquery,
								layer = layui.layer;
							
								
								$.post("unlinkfunc",{unlinkpath:path,filename:filename},function(result,status)
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





	
		function rename2(path,oldname,dir)
	{
		layui.use(['jquery','layer'], function()
			{
					
				var $ = layui.jquery,
				layer = layui.layer;
		
							
								layer.prompt({title: '请修改文件名字', formType: 3, value: oldname }, function(rename, index)//弹出窗口获取学号
							{
								layer.close(index);//关闭窗口
								
									var newdir = dir+rename;
						
								$.post("renamefunc",{path:path,newdir:newdir,newname:rename},function(result,status)
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
	
		

	</script>
	
	
	</html>