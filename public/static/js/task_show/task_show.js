function setshare(filepath,type)
{
	layui.use(['jquery','layer'], function()
			{
					
				var $ = layui.jquery,
				layer = layui.layer;
	if(type=='set')//���ù���
	{
		$.post('setshare',{task:1,filepath:filepath},function(res)
			   {
			var obj =JSON.parse(res);
			layer.msg(obj.msg);
			
				
		});
	}
	else//ȡ������
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
								   layer.msg('ȡ������ɹ�');
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
							if(res=="����δ��¼Ŷ�����ȵ�¼")
							{
								layer.msg(res,function(
							layer.prompt({title:'����������ѧ��',formType:3},function(xuehao,index)//�������ڻ�ȡѧ��
						{
							layer.close(index);//�رմ���
							layer.prompt({title:'�������¼����',formType:3},function(password,index)
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
													   layer.msg('ȡ������ɹ�');
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
				layer.prompt({title: '����Ҫ�������ļ�����Ӻ�׺��', formType: 3 }, function(filename, index)//�������ڻ�ȡѧ��
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
							$('#content').html("  <span class=\"stars\"></span><div class=\"svg-wrapper\"><rect class=\"shape\" height=\"40\" width=\"150\" /><div  class=\"text\" ><a id=\"searchfile\" onclick=\"delfile('"+filepath+"','"+file+"')\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px; \"><span class=\"spot\"></span>ɾ��</a> </div></div><br/><div class=\"svg-wrapper\"><rect class=\"shape\" height=\"40\" width=\"150\" /><div  class=\"text\" ><a id=\"searchfile\" onclick=\"rename2('"+filepath+"','"+file+"','"+dir+"')\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px; \"><span class=\"spot\"></span>����</a> </div></div><br/><div class=\"svg-wrapper\"><rect class=\"shape\" height=\"40\" width=\"150\" /><div  class=\"text\" ><a href=\""+fileDownloadpath+"\" download=\""+file+"\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px; \"><span class=\"spot\"></span>����</a> </div></div><br/><div class=\"svg-wrapper\"><rect class=\"shape\" height=\"40\" width=\"150\" /><div  class=\"text\" ><a onclick=\"searchfile()\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px; \"><span class=\"spot\"></span>��������</a> </div></div><br/><div class=\"svg-wrapper\"><rect class=\"shape\" height=\"40\" width=\"150\" /><div  class=\"text\" ><a onclick=\"delayed(1000)\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px; \"><span class=\"spot\"></span>��������</a> </div></div><br/>");
						
						layer.close(index);//�رմ���
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
				layer.prompt({title: '����Ҫ������ѧ�Ż�����', formType: 3 }, function(content, index)//�������ڻ�ȡѧ��
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
							$('#content').html("  <span class=\"stars\"></span><div class=\"svg-wrapper\"><rect class=\"shape\" height=\"40\" width=\"150\" /><div  class=\"text\" ><a id=\"searchfile\" onclick=\"delfile('"+filepath+"','"+file+"')\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px; \"><span class=\"spot\"></span>ɾ��</a> </div></div><br/><div class=\"svg-wrapper\"><rect class=\"shape\" height=\"40\" width=\"150\" /><div  class=\"text\" ><a id=\"searchfile\" onclick=\"rename2('"+filepath+"','"+file+"','"+dir+"')\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px; \"><span class=\"spot\"></span>����</a> </div></div><br/><div class=\"svg-wrapper\"><rect class=\"shape\" height=\"40\" width=\"150\" /><div  class=\"text\" ><a href=\""+fileDownloadpath+"\" download=\""+file+"\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px; \"><span class=\"spot\"></span>����</a> </div></div><br/><div class=\"svg-wrapper\"><rect class=\"shape\" height=\"40\" width=\"150\" /><div  class=\"text\" ><a onclick=\"searchfile()\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px; \"><span class=\"spot\"></span>��������</a> </div></div><br/><div class=\"svg-wrapper\"><rect class=\"shape\" height=\"40\" width=\"150\" /><div  class=\"text\" ><a onclick=\"delayed(1000)\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px; \"><span class=\"spot\"></span>��������</a> </div></div><br/>");
						
						layer.close(index);//�رմ���
						}
							else
								{
									layer.msg('��ѧ��û���Ͻ��ļ�Ŷ');
									layer.close(index);//�رմ���
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
							$('#content').html("  <span class=\"stars\"></span><div class=\"svg-wrapper\"><rect class=\"shape\" height=\"40\" width=\"150\" /><div  class=\"text\" ><a id=\"searchfile\" onclick=\"delfile('"+filepath+"','"+file+"')\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px; \"><span class=\"spot\"></span>ɾ��</a> </div></div><br/><div class=\"svg-wrapper\"><rect class=\"shape\" height=\"40\" width=\"150\" /><div  class=\"text\" ><a id=\"searchfile\" onclick=\"rename2('"+filepath+"','"+file+"','"+dir+"')\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px; \"><span class=\"spot\"></span>����</a> </div></div><br/><div class=\"svg-wrapper\"><rect class=\"shape\" height=\"40\" width=\"150\" /><div  class=\"text\" ><a href=\""+fileDownloadpath+"\" download=\""+file+"\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px; \"><span class=\"spot\"></span>����</a> </div></div><br/><div class=\"svg-wrapper\"><rect class=\"shape\" height=\"40\" width=\"150\" /><div  class=\"text\" ><a onclick=\"searchfile()\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px; \"><span class=\"spot\"></span>��������</a> </div></div><br/><div class=\"svg-wrapper\"><rect class=\"shape\" height=\"40\" width=\"150\" /><div  class=\"text\" ><a onclick=\"delayed(1000)\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px; \"><span class=\"spot\"></span>��������</a> </div></div><br/>");
						
						layer.close(index);//�رմ���
						}
							else
								{
									layer.msg('��ѧ��û���Ͻ��ļ�Ŷ');
										layer.close(index);//�رմ���
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
										layer.msg('��ɾ��');
										delayed(2000);

									}
									else
									{
									layer.msg('�ļ�������');
									
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
		
							
								layer.prompt({title: '���޸��ļ�����', formType: 3, value: oldname }, function(rename, index)//�������ڻ�ȡѧ��
							{
								layer.close(index);//�رմ���
								
									var newdir = dir+rename;
						
								$.post("renamefunc",{path:path,newdir:newdir,newname:rename},function(result,status)
								{
									if(status=="success"&&result=="yes")
									{	
										layer.msg('�Ѹ���');
										delayed(2000);

									}
									else
									{
									layer.msg('ʧ��');
									
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
							
								layer.prompt({title: '���޸��ļ���, formType: 3, value: oldname }, function(rename, index)//�������ڻ�ȡѧ��
							{
								layer.close(index);//�رմ���
								var dir = document.getElementById(element).getAttribute('data-dir');
								var newdir = dir+rename;
								$.post("renamefunc",{path:path,newdir:newdir,newname:rename},function(result,status)
								{
									if(status=="success"&&result=="yes")
									{	
										layer.msg('�Ѹ���');
										delayed(2000);

									}
									else
									{
									layer.msg('ʧ��');
									
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
											},time);     // {# ÿ3��ˢ��һ��ҳ�棬����1000����Ϊ1�� #}
	}