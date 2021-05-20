
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
    	//------------------------------------------关闭共享空间---------------------------------
    
    function closeshare()
    {
     layui.use(['jquery','layer'], function()
		{
					
        var $ = layui.jquery,
        layer = layui.layer;
        layer.msg('功能维护中。。。。。');
                
                
                
       });
        
        
    }
    
    
    
    

    //------------------------------------------修改密码---------------------------------
	function updatepassword(oldpassword)
	{
		layui.use(['jquery','layer'], function()
		{
					
                var $ = layui.jquery,
                layer = layui.layer;
			         if($('#2').val())
                {
                   var newpassword=$('#2').val();
				        $.post('index',{task:1,oldpassword:oldpassword,newpassword:newpassword},function(res){
                            var obj=JSON.parse(res);
                            if(obj.code==0)
                               {
                               layer.msg(obj.msg,function(){location.reload([true]);});
                               }
                            else
                            {
                                layer.msg(obj.msg);
                            }
                            
                            
                        });
                    
			
		 
		
		
	}
	
	  });
    }
	
	
	
//--------------------------修改信息方法----------------
	function update(id)
	{
		
		 layui.use(['jquery','layer'], function()
			{
					
				var $ = layui.jquery,
				layer = layui.layer;
			 var val=$('#'+id).val();//获取input的值
			 var name=document.getElementById(id).getAttribute('data-name');//获取字段
			$.post("update",{name:name,val:val,task:1},function(res){
				if(res=="ok")
				{
					layer.msg("修改成功");
					setTimeout(function () 
					{
						location.reload([true]);
					},2000);     // {# 每3秒刷新一次页面，这里1000毫秒为1秒 #}
				}
				}
			)});
					
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
			layer.alert('注意！这个操作会删除所有已提交文件，但是会在删除所有文件之前自动将所有文件打包备份至压缩包区', {
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
//------------------------------------------------------------------------------------------------
	

//----------------------------------------开通共享空间-----------------------------------
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
                        
				var obj=JSON.parse(res);
			if(obj.code==0){
					   layer.msg(obj.msg,function(){
                           layer.close(index);
                           location.reload([true]);
                       });
					    
				   }else
            {
                  layer.msg(obj.msg,function(){
                           layer.close(index);
                           location.reload([true]);
                       }); 
            }

					});
				});
				
			});
				
			}
		});
			
	});			
			
	}
    
    //----------------------------------------修改共享口令---------------------------------------------------
	function change_sharekey()
    {
         layui.use(['jquery','layer'], function()
							{
							 var $ = layui.jquery,
							layer = layui.layer;
             if($('#1').val())
                {
                 var newsharekey=$('#1').val();
                    $.post('/taskShowController/sharemain',{task:4,sharekey:newsharekey},function(result){
                        var obj=JSON.parse(result);
                        if(obj.code==0)
                           {
                               layer.msg(obj.msg,function(){
                                location.reload([true]);
                               });

                               
                           }else
                            {
                                layer.msg(obj.msg);
                            }
                        
                        
                    });
                    
                    
                    
                    
                    
                    
                }
            
//-----------------------------------------------------------------------------------------------------------------
             
             
             
             
             
             
             
             
         });
    }
	

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
		                         空间基本信息
		                  </a>
		                </h4>
		             </div>
		           <div id="001" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
		           <div class="panel-body">
					   <?php echo @$Basic_information?>
		            
		           </div>
		           </div>
		           </div>

		           <div class="panel panel-default">
		              <div class="panel-heading" role="tab" id="headingTwo">
		                  <h4 class="panel-title">
		                     <?php if(!empty($sharekey)){echo '<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href= "#002" aria-expanded ="false" aria-controls="collapseTwo">';}else{echo  '<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href= "" aria-expanded ="false" aria-controls="collapseTwo">';} ?> 
		                         共享空间 <?php if(!empty($sharekey)){echo '您的共享码是:<strong style="color: red">'.$sharekey.'</strong><button onclick="closeshare()">关闭空间</button><button onclick="share()">进入空间</button>';}else{echo '还未开通<button onclick="share()">开通空间</button>' ;} ?>
		                     </a>
		                  </h4>
		              </div>
		             <div id="002" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
		              <div class="panel-body">
		               <input id="1"  type="text" /><button onClick="change_sharekey()">修改</button>
		                </div>
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
		                  <input id="2"  type="text" /><button onClick="updatepassword('{$password}')">修改</button>
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
