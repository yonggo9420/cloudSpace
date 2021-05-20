function setshare(filepath,type)
{
	layui.use(['jquery','layer'], function()
			{
					
				var $ = layui.jquery,
				layer = layui.layer;
	if(type=='set')//设置共享
	{
		$.post('setshare',{task:1,filepath:filepath},function(res)
			   {
			var obj =JSON.parse(res);
			layer.msg(obj.msg);
			
				
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

/*
function quxiaoshare(filepath)
		{
				layui.use(['jquery','layer'],function()
				{
					var $ = layui.jquery,
					layer = layui.layer;
					$.post('taskShowController/sharemain',{task:5},function(res){
						if(res=="ok")
						{
							$.post('taskShowController/sharemain',{task:6},function(re){
								if(re=="ok")
								 {
								   layer.msg('取消共享成功');
									 setTimeout(function(){
										location.reload([true]); 
									 },2000);
								 }
								else
								{
									layer.msg(re);
								}
								
							});
						}
						else
						{
							if(res=="您还未登录哦，请先登录")
							{
								layer.msg(res,function(
							layer.prompt({title:'请输入您的学号',formType:3},function(xuehao,index)//弹出窗口获取学号
						{
							layer.close(index);//关闭窗口
							layer.prompt({title:'请输入登录密码',formType:3},function(password,index)
								{
									layer.close(index);
									$.post("/taskShowController/Index",{xuehao:xuehao,password:password},function(result)
									{
									 if(result=="ok")
										{
											$.post('taskShowController/sharemain',{task:5},function(res){
											if(res=="ok")
											{
												$.post('taskShowController/sharemain',{task:6},function(re){
													if(re=="ok")
													 {
													   layer.msg('取消共享成功');
														 setTimeout(function(){
															location.reload([true]); 
														 },2000);
													 }
													else
													{
														layer.msg(re);
													}
								
													});
											}
										});
											
											
										}
									  	else
										{
											layer.msg(result);
										}
				  
			  						});
			  
			  					}); 
						});	 
										 
										 ));
							}
							else
							{
								layer.msg(res);
							}
							
						}
						
						
					});
					
				});
			
			
		}
		


*/






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
					   	
					$.post("searchfile",{type:'number',content:content},function(result,status)
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
					 }
					else
					{
						$.post("searchfile",{content:content},function(result,status)
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
					
					}
						
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





	function rename(path,element)
	{
		layui.use(['jquery','layer'], function()
			{
					
				var $ = layui.jquery,
				layer = layui.layer;
			var oldname = document.getElementById(element).getAttribute('data-oldname');
							
								layer.prompt({title: '请修改文件名, formType: 3, value: oldname }, function(rename, index)//弹出窗口获取学号
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


	function delayed(time)
	{
	setTimeout(function () 
											{
												location.reload([true]);
											},time);     // {# 每3秒刷新一次页面，这里1000毫秒为1秒 #}
	}