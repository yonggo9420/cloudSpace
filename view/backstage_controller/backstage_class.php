<!doctype html>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="/static/css/layui.css" media="all">
<script src="/static/layui.js"></script>
<link rel="stylesheet" href="/static/css/backstage/bootstrap.min.css">
<link rel="stylesheet" href="/static/css/backstage/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="/static/css/backstage/backstage.css">
<script src="/static/js/jquery-1.10.2.min.js"></script>
<script src="/static/js/backstage/bootstrap.min.js"></script>
    <style type="text/css">
    table.imagetable {
        font-family: verdana,arial,sans-serif;
        font-size:11px;
        color:#333333;
        border-width: 1px;
        border-color: #999999;
        border-collapse: collapse;
    }
    table.imagetable th {
        background:#b5cfd2 url('cell-blue.jpg');
        border-width: 1px;
        padding: 8px;
        border-style: solid;
        border-color: #999999;
    }
    table.imagetable td {
        background:#dcddc0 url('cell-grey.jpg');
        border-width: 1px;
        padding: 8px;
        border-style: solid;
        border-color: #999999;
    }
    </style>
<script>

	
function addzip()
{
layui.use(['jquery','layer'], function()
			{
				var $ = layui.jquery,
				layer = layui.layer;
var existenceORno="";
					$.post("/taskShowController/dir_exist_file_func",{path:"{$zipdir}"},function(existenceORno1,status)//post方式把 “目标文件地址” 发送到数据处理文件
					{
					
					if(existenceORno1=="yes")
					{
							layer.msg('文件已经存在了');
							

					}
					if(existenceORno1=="no")
					{
						var zipTargetpath="{$dir}";
						$.post("/taskShowController/addzip",
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
	
	
	
	
//--------------------------修改信息方法----------------
	function update(id,name)
	{
		
		 layui.use(['jquery','layer'], function()
			{
					
				var $ = layui.jquery,
				layer = layui.layer;
             if($('#'+id).val())
                {
                 var val=$('#'+id).val();//获取input的值
                $.post("update",{name:name,val:val,task:1},function(res){
                    var obj =JSON.parse(res);
                    if(obj.code==0)
                    {
                        layer.msg("修改成功");
                        setTimeout(function () 
                        {
                            location.reload([true]);
                        },2000);     // {# 每3秒刷新一次页面，这里1000毫秒为1秒 #}
                    }else
                    {
                        layer.msg(obj.msg);
                    }
                    }
                );
                }
			});
					
	}
//------------------------------------------------------------------------------------------------
	//--------------------------修改学生信息方法-----------------------------
	function userinfoupdate(id,xuehao,name)
	{
		
		 layui.use(['jquery','layer'], function()
			{
					
					
				var $ = layui.jquery,
				layer = layui.layer;
				layer.prompt({title: '学号', formType: 3, value: xuehao }, function(newxuehao, index)//弹出窗口获取学号
					{
						layer.close(index);//关闭窗口
					layer.prompt({title: '姓名', formType: 3, value: name }, function(newname, index)//弹出窗口获取学号
					{
						
			$.post("userinfoupdate",{id:id,name:newname,xuehao:newxuehao},function(res){
				if(res=="ok")
				{
					layer.close(index);//关闭窗口
					layer.msg("修改成功");
					setTimeout(function () 
					{
						location.reload([true]);
					},2000);     // {# 每3秒刷新一次页面，这里1000毫秒为1秒 #}
				}
				else
				{
					layer.close(index);//关闭窗口
					layer.msg("修改失败");
				}
				}
				)
				});
			});
		 });
					
	}
//------------------------------------------------------------------------------------------------
//----------------------------------------手动移除方法--------------------------------------
	function yichu(type,xuehao)
	{
		
		 layui.use(['jquery','layer'], function()
			{
					
				var $ = layui.jquery,
				layer = layui.layer;
             		layer.alert('确定删除该学生的所有文件吗？', {
			  skin: 'layui-layer-molv' //样式类名
			  ,closeBtn: 1
				,anim: 4 //动画类型
			}, function(){
			$.post("yichu",{xuehao:xuehao,type:type},function(res){
                var obj = JSON.parse(res);
				if(obj.code==0)
				{
					layer.msg(obj.msg);
					setTimeout(function () 
					{
						location.reload([true]);
					},2000);     // {# 每3秒刷新一次页面，这里1000毫秒为1秒 #}
				}
				else
				{
					layer.msg(obj.msg);
				}
				});
             
                    });
         });
					
	}
//------------------------------------------------------------------------------------------------
	//----------------------------------------重置所有方法--------------------------------------
	function reset_all()
	{
		
		 layui.use(['jquery','layer'], function()
			{
					
				var $ = layui.jquery,
				layer = layui.layer;
		//墨绿深蓝风
			layer.alert('注意！这个操作会删除班级学生本次作业提交记录和所有已提交文件，但是会在删除所有文件之前自动将所有文件打包备份至压缩包区，可供管理员下载，该操作用于新作业提交任务开始前的清理。', {
			  skin: 'layui-layer-molv' //样式类名
			  ,closeBtn: 1
				,anim: 4 //动画类型
			}, function(){
			$.post("reset_all",function(res){
				if(res=="ok")
				{
					layer.msg("重置成功");
					setTimeout(function () 
					{
						location.reload([true]);
					},2000);     // {# 每3秒刷新一次页面，这里1000毫秒为1秒 #}
				}
				else
				{
					layer.msg("操作失败");
				}
				}
			);
			});
			});
					
	}
    
//--------------------------------------------------------------------------------------------------
    function showfile(xuehao)
    {
        	 layui.use(['jquery','layer'], function()
			{
					
				var $ = layui.jquery,
				layer = layui.layer;
        $.post("showfile",{xuehao:xuehao},function(res){ 
            var obj=JSON.parse(res);
            if(obj.code==0)
            {
                //自定页
                layer.open({
                  type: 1,
                  skin: 'layui-layer-demo', //样式类名
                  anim: 4,
                  shadeClose: true, //开启遮罩关闭
                  content: obj.msg
                });

               }
            else
        {
            layer.msg(obj,msg);
        }
            
        });
             
        });
        
    }
    
    
    
//------------------------------------------------------------------------------------------------
    
//------------------------------------------------------------------------------------------------
    
function delfile(path,filename)
	{

	
		layui.use(['jquery','layer'], function()
					{
								var $ = layui.jquery,
								layer = layui.layer;
							var existenceORno="";
					$.post("/taskShowController/dir_exist_file_func",{path:"{$zipdir}"},function(existenceORno1,status)//post方式把 “目标文件地址” 发送到数据处理文件
					{
					
					if(existenceORno1=="yes")
					{
							$.post("/taskShowController/unlinkfunc",{unlinkpath:path,filename:filename},function(result,status)
								{
									if(status=="success"&&result=="yes")
									{	
										layer.msg('已删除');
										delayed(2000);

									}


								});
							

					}
            if(existenceORno1=="no")
					{
							layer.msg('请先进行压缩哦');
							

					}
                    });
								
								
					});
	}
	
    
    
    
    
    
    
    
    
    
    
//------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------
    
    
    
    
	function delayed(time)
	{
	setTimeout(function () 
											{
												location.reload([true]);
											},time);     // {# 每3秒刷新一次页面，这里1000毫秒为1秒 #}
	}
    
    
    
    
    
    
    
    
    
    
    
//------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------
    
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
						
								$.post("/taskShowController/renamefunc",{path:path,newdir:newdir,newname:rename},function(result,status)
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

    
    
    
    
    
//------------------------------------------------------------------------------------------------
    function setfile(file,filepath,fileDownloadpath,dir){
         layui.use(['jquery','layer'], function()
                {

				var $ = layui.jquery,
				layer = layui.layer;
						 //自定页
                layer.open({
                  type: 1,
                  title:'文件列表',
                  skin: 'layui-layer-demo', //样式类名
                  anim: 4,
                  shadeClose: true, //开启遮罩关闭
                  content: " <div style=\"text-align:center\"> <a  onclick=\"delfile('"+filepath+"','"+file+"')\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px; \">删除</a><br/><a  onclick=\"rename2('"+filepath+"','"+file+"','"+dir+"')\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px; \">改名</a> <br/><a href=\""+fileDownloadpath+"\" download=\""+file+"\" >下载</a></div>"
							
						 
        });
         });
    }
    	
    
    
    
    function downloadfile()
    {
        layui.use(['jquery','layer'], function()
			{
				var $ = layui.jquery,
				layer = layui.layer;
var existenceORno="";
					$.post("/taskShowController/dir_exist_file_func",{path:"{$zipdir}"},function(existenceORno1,status)//post方式把 “目标文件地址” 发送到数据处理文件
					{
					
					if(existenceORno1=="yes")
					{
							window.location.href='{$zipDownloadAllName}';
							

					}
            if(existenceORno1=="no")
					{
							layer.msg('请先进行压缩哦');
							

					}
                    });
        });
        
        
        
    }
    
    
    
    
    
    
//------------------------------------------------------------------------------------------------
	

	layui.use('upload', function(){
		  var $ = layui.jquery
		  ,upload = layui.upload;

				 upload.render({
    			elem: '#upload'
    			,url: 'upload_file'
	 			,accept: 'file'
    			,done: function(res)
					 {
					layer.msg(res.msg);
						 setTimeout(function () 
					{
						location.reload([true]);
					},2000); 
					}
			  }); 
		 });
    

	
//----------------------------日期时间选择器---------------------------------------------------------
			layui.use('laydate', function(){
  			var laydate = layui.laydate;
			var $ = layui.jquery,
			layer = layui.layer;
				  laydate.render({
					elem: '#test5'
					,type: 'datetime'
				  });  
			 });
//-------------------------------------------------------------------------------------
</script>
</head>
	
<body style="background: -webkit-gradient(linear, left top, right top, from(red), to(cyan));  background-attachment: fixed;">
<div class="demo" >
	<div class="container" >
		 <div class="row" >
		     <div  >
		        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true" >
		           <div class="panel panel-default" >
		             <div class="panel-heading" role="tab" id="headingOne">
		                <h4 class="panel-title">
		                  <a role="button" data-toggle="collapse" data-parent="#accordion" href="#001" aria-expanded="true" aria-controls="collapseOne">
		                         班级名称：{$banji}
		                  </a>
		                </h4>
		             </div>
		           <div id="001" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
		           <div class="panel-body">
					   
		             <input id="1"  type="text" /><button onClick="update('1','banji')">修改</button>
		           </div>
		           </div>
		           </div>

		           <div class="panel panel-default">
		              <div class="panel-heading" role="tab" id="headingTwo">
		                  <h4 class="panel-title">
		                     <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#002" aria-expanded="false" aria-controls="collapseTwo">
		                         本次作业名称：{$zyname}
		                     </a>
		                  </h4>
		              </div>
		           <div id="002" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
		               <div class="panel-body">
		                  <input id="2"  type="text" /><button onClick="update('2','zyname')">修改</button>
		               </div>
		           </div>
		           </div>
					 <div class="panel panel-default">
		               <div class="panel-heading" role="tab" id="headingThree">
		                 <h4 class="panel-title">
		                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#003" aria-expanded="false" aria-controls="collapseThree">
		                       本次作业截止日期：{$task_time}
		                     </a>
		                  </h4>
		               </div>
		              <div id="003" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
		              <div class="panel-body">
		                 <div class="layui-inline">
							<div class="layui-input-inline">
								<input type="text"  class="layui-input" id="test5" placeholder="yyyy-MM-dd HH:mm:ss">
								</div>
								<button onClick="update('test5','task_time')">修改</button>
		                 </div>
		                </div>
					   </div>
		              </div>	
					<div class="panel panel-default">
		               <div class="panel-heading" role="tab" id="headingFour">
		                 <h4 class="panel-title">
		                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#004" aria-expanded="false" aria-controls="collapseFour">
		                       班级人员：{$usernum}人
		                     </a>
		                  </h4>
		               </div>
		              <div id="004" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
		              <div class="panel-body" style="margin-left:  0%; text-align: center;background-color: aliceblue;">
		            
  						<?php echo $usertable?>
  
		                </div>
					   </div>
		              </div>
					<div class="panel panel-default">
		               <div class="panel-heading"  role="tab" id="heading5">
		                 <h4 class="panel-title">
		                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#005" aria-expanded="false" aria-controls="collapse5">
		                       未交：{$weijiaousernum}人--------------------------已交：<span style="color: red">{$yijiaousernum}</span>人--------------------------总文件数:<span style="color: red">{$allfilenumber}</span>
		                     </a>
		                  </h4>
		               </div >
		              <div id="005" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading5">
		              <div class="panel-body" style="margin-left:  0%; text-align: center;background-color: aliceblue;">
		           <?php echo $weijiaousertable?>
  						<?php echo $yijiaousertable?>
		                </div>
						 
					
					   </div>
		              </div>
					 <div class="panel panel-default">
		              <div class="panel-heading" role="tab" id="headingTwo">
		                  <h4 class="panel-title">
		                     <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="" aria-expanded="false" aria-controls="collapseTwo">
		                        文件总操作：<button onclick="addzip()">压缩所有作业</button><button onclick="delfile('{$zipallname}')">删除压缩包</button><button onclick="downloadfile()">下载压缩包</button> <button onclick="reset_all()">重置所有</button>
		                     </a>
		                  </h4>
		              </div>
		        
		           </div>
					 <div class="panel panel-default">
		              <div class="panel-heading" role="tab" id="headingTwo">
		                  <h4 class="panel-title">
		                     <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#006" aria-expanded="false" aria-controls="collapseTwo">
		                         您的密码是：{$password}
		                     </a>
		                  </h4>
		              </div>
		           <div id="006" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
		               <div class="panel-body">
		                  <input id="3"  data-password=\"{$password}\" type="text" /><button onClick="update(3,'password')">修改</button>
		               </div>
		           </div>
		           </div>
                    <div class="panel panel-default">
		              <div class="panel-heading" role="tab" id="headingTwo">
		                  <h4 class="panel-title">
		                     <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#007" aria-expanded="false" aria-controls="collapseTwo">
		                         本班级的班级口令是：{$banjikey}
		                     </a>
		                  </h4>
		              </div>
		           <div id="007" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
		               <div class="panel-body">
		                  <input id="4"   type="text" /><button onClick="update(4,'banjikey')">修改</button>
		               </div>
		           </div>
		           </div>
                    <div class="panel panel-default">
		              <div class="panel-heading" role="tab" id="headingTwo">
		                  <h4 >
		                     <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="" aria-expanded="false" aria-controls="collapseTwo">
		                        <i style="font-size: 30px">您的唯一ID：</i> <span style="font-size: 30px;color: red">{$ID}</span>(用于找回密码，证明身份，请勿丢失！)
		                     </a>
		                  </h4>
		              </div>
		         
		           </div>
		              </div>
		             </div>
		            </div>
		        </div>
		    </div>
    
    
</body>


		 
		 
	 
		

</html>
