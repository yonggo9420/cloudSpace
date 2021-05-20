<?php
namespace app\controller;

use app\BaseController;
use think\facade\View;
use think\facade\Cookie;
use think\facade\Request;
use think\facade\Db;
 class TaskShowController extends BaseController
{
	public function Index()
	{
		//如果变量是0或者0的字符串empty都会认为是空if语句也是
		if(!empty(Cookie::get('grpassword')))//检测本地个人密码是否存在//是否登录过
		{
			$password=Cookie::get('grpassword');
			$xuehao=Cookie::get('grxuehao');
			$result=Db::table('xukekey')->where('password', $password)->where('xuehao', $xuehao)->where('yontu', '个人')->select()->toArray();
			if(!empty($result))
			{
				if($result[0]['yontu']=="个人")
				{
					
					return "ok";
				}
				else
				{
					return "云空间类型不是个人云空间";
				}
		
			}
			else
			{
				return "密码错误或用户不存在";
			}
		}
		else
		{
			$xuehao=request()->post('xuehao');
			$password=request()->post('password');
			$result=Db::table('xukekey')->where('password', $password)->where('xuehao', $xuehao)->where('yontu', '个人')->select()->toArray();
			if(!empty($result))
			{
				if($result[0]['yontu']=="个人")
					{
						
						$dir="YKJ/grYKJ/".$xuehao."/";
						Cookie::forever('grxuehao',$xuehao);
						Cookie::forever('grpassword',$password);
						Cookie::forever('gryontu',$result[0]['yontu']);
						Cookie::forever('grdir',$dir);
					if(!empty($result[0]['sharepath']))//这里新用户注册后没有开通共享空间，获取数据库里的共享空间地址为空的话会报错//Argument 2 passed to think\Cookie::forever() must be of the type string, null given//所以先判断是否为空
					{
							Cookie::forever('sharepath',$result[0]['sharepath']);
						
					}
					
						return "ok";

					}
					else
					{
						return "云空间类型不是个人云空间";
					}
			}
			else
			{
			return "密码错误或用户不存在";
			}
		}
		
		
	}	
	
	 public function issharefile($filename,$xuehao)//判断文件是否存在于共享空间里
	 {
		 $path='YKJ/grYKJ/'.$xuehao.'/share/'.$filename;  
			if (!is_dir($path)) {
				return false;
			}

			$files = scandir($path);

			// 删除  "." 和 ".."
			unset($files[0]);
			unset($files[1]);

			// 判断是否为空
			if (!empty($files[2])) {
				//此处根据实际需要看是否要返回该文件夹内的文件名称
				//注意，此处仅仅只是返回了一个文件名称。若有需求要获取多个（全部），可能需要循环获取
				//return $files[2];
				return true;
			}

			return false;
		 
	 }
	 
	 
	 
	 
	 
	 
	public function grYKJ()
	{
			
			$xuehao=Cookie::get('grxuehao');
			$password=Cookie::get('grpassword');
			$dir="YKJ/grYKJ/".$xuehao."/upload/";
			$yontu='grYKJ';
			$zipname=$yontu.$xuehao;
			$zipallname='YKJ/'.$yontu.'/'.$xuehao.'/zip/'.$yontu.$xuehao.'.zip';  
			$zipDownloadAllName='/YKJ/'.$yontu.'/'.$xuehao.'/zip/'.$yontu.$xuehao.'.zip';  
			$zipdir='./YKJ/'.$yontu.'/'.$xuehao.'/zip/';  
			$i = 0;
			$j = 1;
			$k = 0;
			$addcss='';
			$content="";
			$content1="";
			//遍历文件夹下所有文件
		if (false != (@$handle = opendir ( $dir ))) 
		{
    		
			while (false !== ($file = readdir($handle))) {

				$fileDownloadpath=HOST_DIR.'/YKJ/'.$yontu.'/'.$xuehao.'/upload/'.$file;
				$filepath=$dir.$file;
				if ($file != "." && $file != ".." && !is_dir($dir.'/'.$file)) {

					$size = filesize($dir.$file);
					$size =taskShowController::trans_byte($size);
					$filedata =date("Y-m-d H:i:s",filemtime($dir.$file));
					$arr=explode('.', $file);
					$houzui=end($arr);
					if($this->issharefile($file,$xuehao))//如果共享空间存在该文件添加一个取消共享按钮
					{
						$contentadd="
						<br/>
				<div class=\"svg-wrapper\">
				  <svg height=\"40\" width=\"150\" xmlns=\"http://www.w3.org/2000/svg\">
					<rect class=\"shape\" height=\"40\" width=\"150\" />
					<div class=\"text\">
					  <a onclick=\"setshare('$filepath','cancel')\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px;\"><span class=\"spot\"></span>取消共享</a> 

					</div>
				  </svg>
				</div>";
					}
					else//如果共享空间存在该文件添加一个设置共享按钮
					{
							$contentadd="
							<br/>
				<div class=\"svg-wrapper\">
				  <svg height=\"40\" width=\"150\" xmlns=\"http://www.w3.org/2000/svg\">
					<rect class=\"shape\" height=\"40\" width=\"150\" />
					<div class=\"text\">
					  <a onclick=\"setshare('$filepath','set')\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px;\"><span class=\"spot\"></span>设置共享</a> 

					</div>
				  </svg>
				</div>";
					}
					$content0="
						 <td class=\"td\">
						 <div class=\"container\">
	  <div class=\"card\" style=\"	width: 60%;height: 300px;\">
		<div class=\"fac face1\">
		  <div class=\"content\">
			<span class=\"stars\"></span>
		   <div class=\"svg-wrapper\">
	  <svg height=\"40\" width=\"150\" xmlns=\"http://www.w3.org/2000/svg\">
		<rect class=\"shape\" height=\"40\" width=\"150\" />
		<div class=\"text\" >
		  <a id=\"$i\" onclick=\"Authentication('delfile','$filepath')\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px; \"><span class=\"spot\"></span>删除</a> 

		</div>
	  </svg>
	</div>
		  <br/>
	<div class=\"svg-wrapper\">
	  <svg height=\"40\" width=\"150\" xmlns=\"http://www.w3.org/2000/svg\">
		<rect class=\"shape\" height=\"40\" width=\"150\" />
		<div class=\"text\">
		  <a id=\"$j\" data-dir=\"$dir\" data-xuehao=\"$xuehao\" data-oldname=\"$file\" onclick=\"rename('$filepath','$j')\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px;\"><span class=\"spot\"></span>改名</a> 

		</div>
	  </svg>
	</div>
	<br/>
	<div class=\"svg-wrapper\">
	  <svg height=\"40\" width=\"150\" xmlns=\"http://www.w3.org/2000/svg\">
		<rect class=\"shape\" height=\"40\" width=\"150\" />
		<div class=\"text\">
		  <a href=\"$fileDownloadpath\" download=\"$file\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px;\"><span class=\"spot\"></span>下载</a> 

		</div>
	  </svg>
	</div>

	<br/>
	";
	if($houzui=="docx"||$houzui=="doc"||$houzui=="pdf"||$houzui=="ppt"||$houzui=="pptx"||$houzui=="xlsx"||$houzui=="xls")
	{
		$content2="	<div class=\"svg-wrapper\"><svg height=\"40\" width=\"150\" xmlns=\"http://www.w3.org/2000/svg\"><rect class=\"shape\" height=\"40\" width=\"150\" /><div class=\"text\"><a onclick=\"yulan('$filepath','$file')\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px;\"><span class=\"spot\"></span>$houzui 文件在线预览</a> </div></svg></div>";

	}
	else
	{
		if($houzui=="png"||$houzui=="jpg")
		{
			$content2="
				<div class=\"svg-wrapper\">
				  <svg height=\"40\" width=\"150\" xmlns=\"http://www.w3.org/2000/svg\">
					<rect class=\"shape\" height=\"40\" width=\"150\" />
					<div class=\"text\">
					  <a onclick=\"pictureyulan('$filepath')\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px;\"><span class=\"spot\"></span>图片预览</a> 

					</div>
				  </svg>
				</div>";

		}
		else
		{
			$content2="";
		}


	}
	$content3=" <br/></div>
		</div>
		<div class=\"face face2\" style=\"background-image: linear-gradient(40deg, #fff 0%, #000 45%, #fff 100%);\" >
		  <h2 style=\"margin-bottom:30%;\">$file</h2> 
		<h1 >文件大小：$size</h1>
		  <h1 >提交日期：$filedata</h1>
		  <h1 >文件ID：$k</h1>

		</div>

	  </div>
		</div>
		</td>";

	$content=$content.$content0.$content2.$contentadd.$content3;
	if(($k+1)%5==0)
	{
		$content=$content."</tr><tr>";
	}		
					
	if($k+1==5)
	{
		$addcss="<link rel=\"stylesheet\" href=\"/static/css/zhishiyingtable.css\">";
	}
	$i+=2;
	$j+=2;
	$k+=1;
				}

}
	//关闭句柄
closedir($handle);
	}
		
		$content1="<div id=\"modal-container\">
 
<div class=\"container\" style=\"margin-top: 10%;\">
  <div class=\"card\" style=\"height:500px\">
    <div class=\"fac face1\" >
      <div id=\"content\" class=\"content\">
        <span class=\"stars\"></span>
       <div class=\"svg-wrapper\">
  <svg height=\"40\" width=\"150\" xmlns=\"http://www.w3.org/2000/svg\">
	<rect class=\"shape\" height=\"40\" width=\"150\" />
	<div class=\"text\" >
	  <a onclick=\"Authentication('addzip','$dir')\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px; \"><span class=\"spot\"></span>压缩所有文件</a> 
		
	</div>
  </svg>
</div>
	  <br/>
<div class=\"svg-wrapper\">
  <svg height=\"40\" width=\"150\" xmlns=\"http://www.w3.org/2000/svg\">
	<rect class=\"shape\" height=\"40\" width=\"150\" />
	<div class=\"text\">
	  <a href=\"".HOST_DIR.@$zipDownloadAllName."\" download=\"$zipname\"  style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px;\"><span class=\"spot\"></span>下载压缩包</a> 
		
	</div>
  </svg>
</div>  
<br/>
<div class=\"svg-wrapper\">
  <svg height=\"40\" width=\"150\" xmlns=\"http://www.w3.org/2000/svg\">
	<rect class=\"shape\" height=\"40\" width=\"150\" />
	<div class=\"text\">
	  <a onclick=\"Authentication('delfile','$zipallname')\"  style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px;\"><span class=\"spot\"></span>删除压缩包</a> 
		
	</div>
  </svg>
</div>
<br/>
<div class=\"svg-wrapper\">
  <svg height=\"40\" width=\"150\" xmlns=\"http://www.w3.org/2000/svg\">
	<rect class=\"shape\" height=\"40\" width=\"150\" />
	<div class=\"text\">
	<a  style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px; \" id=\"upload\" >上传文件</A> 
	</div>
  </svg>
</div>
<br/>
<div class=\"svg-wrapper\">
  <svg height=\"40\" width=\"150\" xmlns=\"http://www.w3.org/2000/svg\">
	<rect class=\"shape\" height=\"40\" width=\"150\"/>
	<div class=\"text\">
	 <a id=\"searchfile\" onclick=\"search_gr_file()\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px; \"><span class=\"spot\"></span>搜索文件</a> 
		
	</div>
  </svg>
</div>
<br/>
<div class=\"svg-wrapper\">
  <svg height=\"40\" width=\"150\" xmlns=\"http://www.w3.org/2000/svg\">
	<rect class=\"shape\" height=\"40\" width=\"150\"/>
	<div class=\"text\">
	 <a id=\"searchfile\" onclick=\"share()\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px; \"><span class=\"spot\"></span>共享空间</a> 
		
	</div>
  </svg>
</div>
<br/>
<div class=\"svg-wrapper\">
  <svg height=\"40\" width=\"150\" xmlns=\"http://www.w3.org/2000/svg\">
	<rect class=\"shape\" height=\"40\" width=\"150\"/>
	<div class=\"text\">
	 <a id=\"searchfile\" onclick=\"backstage_personal()\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px; \"><span class=\"spot\"></span>后台设置</a> 
		
	</div>
  </svg>
</div>
      </div>
    </div>
  
  </div>
	</div>
		
</div>";
		
		
		
		
			View::assign(['title'=>'个人云空间','content'=>@$content,'addcss'=>@$addcss,'content1'=>@$content1,'zipallname'=>@$zipallname,'zipdir'=>@$zipdir,'dir'=>@$dir,'HOST_DIR'=>HOST_DIR]);
		return View::fetch('../view/grYKJ/gr_task_show.php');
		
	}
	
	public function jiaozy()
	{
		
		$xuehao=Cookie::get('xuehao');
		$password=Cookie::get('password');
		$banjikey=Cookie::get('banjikey');
		$yontu=Cookie::get('yontu');
		$dir=Cookie::get('dir').'upload/';
		if($yontu=="收作业")
		{
		$yontu='szyYKJ';
		}
			$zipname=$yontu.$banjikey; //  "szyYKJ计算机类2020班"
			$zipallname='YKJ/'.$yontu.'/'.$banjikey.'/zip/'.$yontu.$banjikey.'.zip';  

			$zipDownloadAllName='/YKJ/'.$yontu.'/'.$banjikey.'/zip/'.$yontu.$banjikey.'.zip';  //  "/YKJ/zip/szyYKJ/szyYKJ计算机类2020班.zip"
			$zipdir='./YKJ/'.$yontu.'/'.$banjikey.'/zip/';  //  根目录/YKJ/zip/szyYKJ/
			$i = 0;
			$j = 1;
			$k = 0;
			$content="";
			$addcss="";
			$content1="";
		//遍历文件夹下所有文件
		if (false != (@$handle = opendir ( $dir ))) 
		{
    		
		
			while (false !== ($file = readdir($handle))) {

				$fileDownloadpath=HOST_DIR.'/YKJ/'.$yontu.'/'.$banjikey.'/upload/'.$file;
				$filepath=$dir.$file;
				if ($file != "." && $file != ".." && !is_dir($dir.'/'.$file)) {

					$size = filesize($dir.$file);
					$size =taskShowController::trans_byte($size);
					$filedata =date("Y-m-d H:i:s",filemtime($dir.$file));
					$arr=explode('.', $file);
					$houzui=end($arr);
					if($houzui=="docx"||$houzui=="doc"||$houzui=="pdf"||$houzui=="ppt"||$houzui=="pptx")
			{
				$content0="
					 <td class=\"td\">
					 <div class=\"container\">
  <div class=\"card\">
    <div class=\"fac face1\">
      <div class=\"content\">
        <span class=\"stars\"></span>
       <div class=\"svg-wrapper\">
  <svg height=\"40\" width=\"150\" xmlns=\"http://www.w3.org/2000/svg\">
	<rect class=\"shape\" height=\"40\" width=\"150\" />
	<div class=\"text\" >
	  <a id=\"$i\" onclick=\"Authentication('delfile','$filepath','$file')\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px; \"><span class=\"spot\"></span>删除</a> 
		
	</div>
  </svg>
</div>
	  <br/>
<div class=\"svg-wrapper\">
  <svg height=\"40\" width=\"150\" xmlns=\"http://www.w3.org/2000/svg\">
	<rect class=\"shape\" height=\"40\" width=\"150\" />
	<div class=\"text\">
	  <a id=\"$j\" data-dir=\"$dir\" data-xuehao=\"$xuehao\" data-oldname=\"$file\" onclick=\"rename('$filepath','$j')\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px;\"><span class=\"spot\"></span>改名</a> 
		
	</div>
  </svg>
</div>
<br/>
<div class=\"svg-wrapper\">
  <svg height=\"40\" width=\"150\" xmlns=\"http://www.w3.org/2000/svg\">
	<rect class=\"shape\" height=\"40\" width=\"150\" />
	<div class=\"text\">
	  <a href=\"$fileDownloadpath\" download=\"$file\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px;\"><span class=\"spot\"></span>下载</a> 
		
	</div>
  </svg>
</div>

<br/>
<div class=\"svg-wrapper\">
  <svg height=\"40\" width=\"150\" xmlns=\"http://www.w3.org/2000/svg\">
	<rect class=\"shape\" height=\"40\" width=\"150\" />
	<div class=\"text\">
	  <a onclick=\"yulan('$filepath','$file')\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px;\"><span class=\"spot\"></span>$houzui 文件在线预览</a> 
		
	</div>
  </svg>
</div>

      </div>
    </div>
    <div class=\"face face2\" style=\"background-image: linear-gradient(40deg, #fff 0%, #000 45%, #fff 100%);\" >
      <h2   style=\"margin-bottom:30%;\">$file</h2> 
    <h1 >文件大小：$size</h1>
      <h1 >提交日期：$filedata</h1>
      <h1 >文件ID：$k</h1>
	
    </div>
	  
  </div>
	</div>
	</td>
	";
			}else{
						
						if($houzui=="png"||$houzui=="jpg"){
							$content0="
					 <td class=\"td\">
					 <div class=\"container\">
  <div class=\"card\">
    <div class=\"fac face1\">
      <div class=\"content\">
        <span class=\"stars\"></span>
       <div class=\"svg-wrapper\">
  <svg height=\"40\" width=\"150\" xmlns=\"http://www.w3.org/2000/svg\">
	<rect class=\"shape\" height=\"40\" width=\"150\" />
	<div class=\"text\" >
	  <a id=\"$i\" onclick=\"Authentication('delfile','$filepath','','$file')\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px; \"><span class=\"spot\"></span>删除</a> 
		
	</div>
  </svg>
</div>
	  <br/>
<div class=\"svg-wrapper\">
  <svg height=\"40\" width=\"150\" xmlns=\"http://www.w3.org/2000/svg\">
	<rect class=\"shape\" height=\"40\" width=\"150\" />
	<div class=\"text\">
	  <a id=\"$j\" data-dir=\"$dir\" data-xuehao=\"$xuehao\" data-oldname=\"$file\" onclick=\"rename('$filepath','$j')\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px;\"><span class=\"spot\"></span>改名</a> 
		
	</div>
  </svg>
</div>
<br/>
<div class=\"svg-wrapper\">
  <svg height=\"40\" width=\"150\" xmlns=\"http://www.w3.org/2000/svg\">
	<rect class=\"shape\" height=\"40\" width=\"150\" />
	<div class=\"text\">
	  <a href=\"$fileDownloadpath\" download=\"$file\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px;\"><span class=\"spot\"></span>下载</a> 
		
	</div>
  </svg>
</div>
<br/>
<div class=\"svg-wrapper\">
  <svg height=\"40\" width=\"150\" xmlns=\"http://www.w3.org/2000/svg\">
	<rect class=\"shape\" height=\"40\" width=\"150\" />
	<div class=\"text\">
	  <a onclick=\"pictureyulan('$filepath')\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px;\"><span class=\"spot\"></span>图片预览</a> 
		
	</div>
  </svg>
</div>
</div>
    </div>
    <div class=\"face face2\" style=\"background-image: linear-gradient(40deg, #fff 0%, #000 45%, #fff 100%);\" >
      <h2 style=\"margin-bottom:30%;\">$file</h2> 
    <h1 >文件大小：$size</h1>
      <h1 >提交日期：$filedata</h1>
      <h1 >文件ID：$k</h1>
	
    </div>
	  
  </div>
	</div>
	</td>
	";
							
						}else{
							
							$content0="
					 <td class=\"td\">
					 <div class=\"container\">
  <div class=\"card\">
    <div class=\"fac face1\">
      <div class=\"content\">
        <span class=\"stars\"></span>
       <div class=\"svg-wrapper\">
  <svg height=\"40\" width=\"150\" xmlns=\"http://www.w3.org/2000/svg\">
	<rect class=\"shape\" height=\"40\" width=\"150\" />
	<div class=\"text\" >
	  <a id=\"$i\" onclick=\"Authentication('delfile','$filepath','$file')\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px; \"><span class=\"spot\"></span>删除</a> 
		
	</div>
  </svg>
</div>
	  <br/>
<div class=\"svg-wrapper\">
  <svg height=\"40\" width=\"150\" xmlns=\"http://www.w3.org/2000/svg\">
	<rect class=\"shape\" height=\"40\" width=\"150\" />
	<div class=\"text\">
	  <a id=\"$j\" data-dir=\"$dir\" data-xuehao=\"$xuehao\" data-oldname=\"$file\" onclick=\"rename('$filepath','$j')\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px;\"><span class=\"spot\"></span>改名</a> 
		
	</div>
  </svg>
</div>
<br/>
<div class=\"svg-wrapper\">
  <svg height=\"40\" width=\"150\" xmlns=\"http://www.w3.org/2000/svg\">
	<rect class=\"shape\" height=\"40\" width=\"150\" />
	<div class=\"text\">
	  <a href=\"$fileDownloadpath\" download=\"$file\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px;\"><span class=\"spot\"></span>下载</a> 
		
	</div>
  </svg>
</div>


      </div>
    </div>
    <div class=\"face face2\" style=\"background-image: linear-gradient(40deg, #fff 0%, #000 45%, #fff 100%);\" >
      <h2 style=\"margin-bottom:30%;\">$file</h2> 
    <h1 >文件大小：$size</h1>
      <h1 >提交日期：$filedata</h1>
      <h1 >文件ID：$k</h1>
	
    </div>
	  
  </div>
	</div>
	</td>
	";
						}
						
							
					}
			$content=$content.$content0;
					
				
			if(($k+1)%5==0)
			{
				$content=$content."</tr><tr>";
			}		
				if($k+1==5)
	{
		$addcss="<link rel=\"stylesheet\" href=\"/static/css/zhishiyingtable.css\">";
	}	
					$i+=2;
					$j+=2;
					$k+=1;
				}

			}
			//关闭句柄
			closedir($handle);
		}
		
		if(Cookie::get('xuehao')&&Cookie::get('password')){
			$content1="<div id=\"modal-container\">
 
<div class=\"container\" style=\"margin-top: 10%;\">
  <div class=\"card\">
    <div class=\"fac face1\" >
      <div id=\"content\" class=\"content\">
        <span class=\"stars\"></span>
       <div class=\"svg-wrapper\">
  <svg height=\"40\" width=\"150\" xmlns=\"http://www.w3.org/2000/svg\">
	<rect class=\"shape\" height=\"40\" width=\"150\" />
	<div class=\"text\" >
	  <a onclick=\"Authentication('addzip','$dir')\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px; \"><span class=\"spot\"></span>压缩所有文件</a> 
		
	</div>
  </svg>
</div>
	  <br/>
<div class=\"svg-wrapper\">
  <svg height=\"40\" width=\"150\" xmlns=\"http://www.w3.org/2000/svg\">
	<rect class=\"shape\" height=\"40\" width=\"150\" />
	<div class=\"text\">
	  <a href=\"".HOST_DIR.@$zipDownloadAllName."\" download=\"".@$zipname."\"  style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px;\"><span class=\"spot\"></span>下载压缩包</a> 
		
	</div>
  </svg>
</div>  
<br/>
<div class=\"svg-wrapper\">
  <svg height=\"40\" width=\"150\" xmlns=\"http://www.w3.org/2000/svg\">
	<rect class=\"shape\" height=\"40\" width=\"150\" />
	<div class=\"text\">
	  <a onclick=\"Authentication('delfile','$zipallname')\"  style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px;\"><span class=\"spot\"></span>删除压缩包</a> 
		
	</div>
  </svg>
</div>
<br/>
<div class=\"svg-wrapper\">
  <svg height=\"40\" width=\"150\" xmlns=\"http://www.w3.org/2000/svg\">
	<rect class=\"shape\" height=\"40\" width=\"150\" />
	<div class=\"text\">
	 <a id=\"searchfile\" onclick=\"searchfile()\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px; \"><span class=\"spot\"></span>搜索文件</a> 
		
	</div>
  </svg>
</div>
      </div>
    </div>
  
  </div>
	</div>
		
</div>";
		}
		else
		{
			$content1="";
		}
		
		View::assign(['title'=>'在线提交作业','content'=>@$content,'addcss'=>@$addcss,'content1'=>@$content1,'zipallname'=>@$zipallname,'zipdir'=>@$zipdir,'dir'=>@$dir,'HOST_DIR'=>HOST_DIR]);
		return View::fetch('../view/task_show_controller/task_show.php');
		
	}
	
	
	
public function classfile_user_show()
	{
		$banjikey=Cookie::get('banjikey');
			$yontu='szyYKJ';
		$dir=Cookie::get('dir').'yaoqiu/';
	
		
		//遍历文件夹下所有文件
if (false != (@$handle = opendir ( $dir ))) {

	
	$k = 0;
	$content="";
	$addcss="";
	$content1="";
    while (false !== ($file = readdir($handle))) {
		$fileDownloadpath=HOST_DIR.'/YKJ/'.$yontu.'/'.$banjikey.'/yaoqiu/'.$file;
		$filepath=$dir.$file;
        if ($file != "." && $file != ".." && !is_dir($dir.'/'.$file)) {
					$size = filesize($dir.$file);
					$size =taskShowController::trans_byte($size);
					$filedata =date("Y-m-d H:i:s",filemtime($dir.$file));
					$arr=explode('.', $file);
					$houzui=end($arr);
					if($houzui=="docx"||$houzui=="doc"||$houzui=="pdf"||$houzui=="ppt"||$houzui=="pptx")
			{
				$content0="
					 <td class=\"td\">
					 <div class=\"container\">
  <div class=\"card\">
    <div class=\"fac face1\">
      <div class=\"content\">
        <span class=\"stars\"></span>

<div class=\"svg-wrapper\">
  <svg height=\"40\" width=\"150\" xmlns=\"http://www.w3.org/2000/svg\">
	<rect class=\"shape\" height=\"40\" width=\"150\" />
	<div class=\"text\">
	  <a href=\"$fileDownloadpath\" download=\"$file\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px;\"><span class=\"spot\"></span>下载</a> 
		
	</div>
  </svg>
</div>

<br/>
<div class=\"svg-wrapper\">
  <svg height=\"40\" width=\"150\" xmlns=\"http://www.w3.org/2000/svg\">
	<rect class=\"shape\" height=\"40\" width=\"150\" />
	<div class=\"text\">
	  <a onclick=\"yulan('$filepath','$file')\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px;\"><span class=\"spot\"></span>$houzui 文件在线预览</a> 
		
	</div>
  </svg>
</div>

      </div>
    </div>
    <div class=\"face face2\" style=\"background-image: linear-gradient(40deg, #fff 0%, #000 45%, #fff 100%);\" >
      <h2 style=\"margin-bottom:30%;\">$file</h2> 
    <h1 >文件大小：$size</h1>
      <h1 >提交日期：$filedata</h1>
      <h1 id=\"$k\" data-filepath=\"$filepath\" >文件ID：$k</h1>
	
    </div>
	  
  </div>
	</div>
	</td>
	";
			}else{
						
						if($houzui=="png"||$houzui=="jpg"){
							$content0="
					 <td class=\"td\">
					 <div class=\"container\">
  <div class=\"card\">
    <div class=\"fac face1\">
      <div class=\"content\">
        <span class=\"stars\"></span>
      
<div class=\"svg-wrapper\">
  <svg height=\"40\" width=\"150\" xmlns=\"http://www.w3.org/2000/svg\">
	<rect class=\"shape\" height=\"40\" width=\"150\" />
	<div class=\"text\">
	  <a href=\"$fileDownloadpath\" download=\"$file\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px;\"><span class=\"spot\"></span>下载</a> 
		
	</div>
  </svg>
</div>
<br/>
<div class=\"svg-wrapper\">
  <svg height=\"40\" width=\"150\" xmlns=\"http://www.w3.org/2000/svg\">
	<rect class=\"shape\" height=\"40\" width=\"150\" />
	<div class=\"text\">
	  <a onclick=\"pictureyulan('$filepath')\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px;\"><span class=\"spot\"></span>图片预览</a> 
		
	</div>
  </svg>
</div>
</div>
    </div>
    <div class=\"face face2\" style=\"background-image: linear-gradient(40deg, #fff 0%, #000 45%, #fff 100%);\" >
      <h2 style=\"margin-bottom:30%;\">$file</h2> 
    <h1 >文件大小：$size</h1>
      <h1 >提交日期：$filedata</h1>
       <h1 id=\"$k\" data-filepath=\"$filepath\" >文件ID：$k</h1>
	
    </div>
	  
  </div>
	</div>
	</td>
	";
							
						}else{
							
							$content0="
					 <td class=\"td\">
					 <div class=\"container\">
  <div class=\"card\">
    <div class=\"fac face1\">
      <div class=\"content\">
        <span class=\"stars\"></span>
      
<div class=\"svg-wrapper\">
  <svg height=\"40\" width=\"150\" xmlns=\"http://www.w3.org/2000/svg\">
	<rect class=\"shape\" height=\"40\" width=\"150\" />
	<div class=\"text\">
	  <a href=\"$fileDownloadpath\" download=\"$file\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px;\"><span class=\"spot\"></span>下载</a> 
		
	</div>
  </svg>
</div>


      </div>
    </div>
    <div class=\"face face2\" style=\"background-image: linear-gradient(40deg, #fff 0%, #000 45%, #fff 100%);\" >
      <h2 style=\"margin-bottom:30%;\">$file</h2> 
    <h1 >文件大小：$size</h1>
      <h1 >提交日期：$filedata</h1>
     <h1 id=\"$k\" data-filepath=\"$filepath\" >文件ID：$k</h1>
	
    </div>
	  
  </div>
	</div>
	</td>
	";
						}
						
							
					}
			$content=$content.$content0;
					
				
			if(($k+1)%5==0)
			{
				$content=$content."</tr><tr>";
			}	
			
		if($k+1==5)
	{
		$addcss="<link rel=\"stylesheet\" href=\"/static/css/zhishiyingtable.css\">";
	}
					
			$k+=1;
        }
		
    }
    //关闭句柄
    closedir($handle);
}
		if(Cookie::get('xuehao')&&Cookie::get('password')){
		$content1="<div class=\"layer-footer\" style=\"z-index: 10; position: fixed; text-align: right; margin-left: -10%; bottom: 0; width:100%; height:50px\">
    
    <button style=\"height: 40px;width: 60px\" onclick=\"delefile()\" type=\"button\" class=\" layui-btn layui-btn-sm layui-btn-normal layui-btn-primary\" lay-submit=\"\"  lay-filter=\"formDemo\">删除</button>  
	<button style=\"height: 40px;width: 60px\" id=\"upload\" type=\"button\" class=\" layui-btn layui-btn-sm layui-btn-normal layui-btn-primary\" lay-submit=\"\"  lay-filter=\"formDemo\">上传</button>  
</div>";
		}
		
		View::assign(['content'=>@$content,'addcss'=>@$addcss,'content1'=>@$content1,'HOST_DIR'=>HOST_DIR]);
		return View::fetch('../view/task_show_controller/classfile_user_show.php');
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
		public function trans_byte($byte)//文件大小数据
		{

			$KB = 1024;

			$MB = 1024 * $KB;

			$GB = 1024 * $MB;

			$TB = 1024 * $GB;

			if ($byte < $KB) {

			return $byte . "B";

			} elseif ($byte < $MB) {

			return round($byte / $KB, 2) . "KB";

			} elseif ($byte < $GB) {

			return round($byte / $MB, 2) . "MB";

			} elseif ($byte < $TB) {

			return round($byte / $GB, 2) . "GB";

			} else {

			return round($byte / $TB, 2) . "TB";

			}

		}
     
     
     
		public function searchfile()//查找文件方法
		{
            $task=request()->post('task');
            if($task==1)//单个文件操作方法
            {
             
				
				$res=Db::name(Cookie::get('banjikey')."_file_info")->where('xuehao',$xuehao)->select();
				
				if(!$res->isEmpty())
				{
					foreach($res as $r)
					{
						
						$arr=array(
							'code'=>0,
							'filepath'=>$r['filepath'],
							'filename'=>$r['filename'],
							'dir'=>Cookie::get('dir').'upload/',
							'fileDownloadpath'=>HOST_DIR.'/YKJ/szyYKJ/'.Cookie::get('banjikey').'/upload/'.$r['filename'],
							
							
						);
						if(empty($r['filename'])){
							$arr['code']=1;
							
						}
						
					}
				}
				else
				{
					$arr=array(
							'code'=>1,
						  'msg'=>'文件不存在'
						);
						
				}
			
			
            }
		      if($task==2)//返回搜索到的所有文件
              {
                  	$arr=array(
                    'code'=>0,
                    'msg'=>'',
                    'filenumber'=>''
                    );
                  $res="";
                  $result="";
                  $xuehao=0;
                  	if(request()->post('type')=="number")
                    {
                    $xuehao= request()->post('content');   
                    }
                  else
                  {
                      $name= request()->post('content');
                      $res = Db::name(Cookie::get('banjikey'))->where('name',$name)->select();
                    if(!$res->isEmpty())//如果根据姓名能在班级表中找到改学生
                  {
                    foreach($res as $re)
                    {
                        $xuehao=$re['xuehao'];
                        $arr['filenumber']=$re['filenumber'];
                    }
                  }else{
                   
                        $arr['code']=1;
                        $arr['msg']="查找不到该学生"; 
                        return json_encode($arr);//直接返回结果
                    }
                      
                  }
                  
                   $res = Db::name(Cookie::get('banjikey'))->where('xuehao',$xuehao)->select();
                if(!$res->isEmpty())//学生是否存在
                  {
                  foreach($res as $re)
                  {
                      $arr['filenumber']=$re['filenumber'];
                  }
                  $result = Db::name(Cookie::get('banjikey')."_file_info")->where('xuehao',$xuehao)->select(); 
                  $content="<div style=\"min-width:300px;min-height:300px;text-align:center;
            \">";
                
                        if(!$result->isEmpty())//学生是否提交过文件
                        {
                            foreach($result as $r)
                            {
                            $filename=$r['filename'];
                            $filepath=$r['filepath'];
                            $downloadpath=HOST_DIR.$filepath;
                            $dir="YKJ/szyYKJ/".Cookie::get('banjikey')."/upload/";
                            $content=$content."<a onclick=\"setfile('$filename','$filepath','$downloadpath','$dir')\" style=\"font-size:40px;\">$filename</a><br/>";
                            }
                        $arr['msg']=$content."</div>";
                        }  
                      else
                        {
                        $arr['code']=1;
                        $arr['msg']="该学生还未交作业";
                        }
                    
                 }else
                        {
                        $arr['code']=1;
                        $arr['msg']="不存在该学生";
                        }
                    
                  
              }
		
			return json_encode($arr);
			
			
		}
     
     
		public function class_admin_login()//班级空间管理员登录
		{
			$xuehao= request()->post('xuehao');//获取学号	
			$password= request()->post('password');//获取密码
        $banjikey=Cookie::get('banjikey');
			$arr= array(
			'code'=>0,
			'msg'=>''
			);
			$result=Db::table('xukekey')->where(['xuehao'=>$xuehao,'banjikey'=>$banjikey,'yontu'=>'收作业'])->select();//根据学号查询数据库信息
			if(!$result->isEmpty())
			{
				foreach($result as $res)
				{
					if($res['password']!=$password)//输入的密码错误
					{
						if($res['banjikey']==Cookie::get('banjikey'))
						{
							$arr['msg']='密码错误但是本班级管理员';
							$arr['code']=1;
							return json_encode($arr);
						}else
						{
							$arr['msg']='密码错误且不是本班级管理员';
							$arr['code']=2;
							return json_encode($arr);
						}
						
					}
					else
					{
						if(Cookie::get('banjikey')!=$res['banjikey'])//不是本班级管理员
						{
							$arr['msg']="确实是管理员但不是本班级的管理员";
							$arr['code']=3;
							return json_encode($arr);
							
						}
						else//是本班级管理员
						{
							Cookie::set('xuehao',$xuehao,36000);
							Cookie::set('password',$password,36000);
							$arr['msg']="登录成功";
							return json_encode($arr);
						}
						
					}
				}
				
			}
			else
			{
				$arr['msg']="没有这个管理员";
				$arr['code']=3;
				return json_encode($arr);
				
			}
		}
	public function unlinkfunc()//班级空间删除文件
		{
			$filename= request()->post('filename');
			$unlinkpath=request()->post('unlinkpath');
			if(unlink($unlinkpath))
			{
        $filenumber=0;
        $xuehao=0;
        $result=Db::name(Cookie::get('banjikey')."_file_info")->where('filename',$filename)->select();
        if(!$result->isEmpty())
        {
           foreach($result as $r)
           {
              
               $xuehao=$r['xuehao'];
               
           }
        }  
        $result=Db::name(Cookie::get('banjikey'))->where('xuehao',$xuehao)->select();
        if(!$result->isEmpty())
        {
           foreach($result as $r)
           {
              
              $filenumber=$r['filenumber']-1;
               
           }
        }
        
			Db::name(Cookie::get('banjikey'))->where('xuehao', $xuehao)->update(['filenumber' => $filenumber]);
				Db::name(Cookie::get('banjikey')."_file_info")-> where('filename' , $filename)->delete();
				return "yes";
			}
			else{
				return "no";
			}
		
		
		}	
     
     
	 public function unlinkfunc_gr()//个人空间删除文件
		{
			$filename= request()->post('filename');
			$unlinkpath=request()->post('unlinkpath');
			unlink($unlinkpath);
			return "yes";
		}
	 
	 
	 public function search_gr_file()//查找个人空间里的文件
	 {
		$uploadpath=Cookie::get('grdir').'upload/';
		$searchfilename=request()->post('filename');
		 $arr=array(
		'code'=>0,
		'filepath'=>'',
		'filename'=>'',
		'dir'=>'',
		'fileDownloadpath'=>'',
		 'msg'=>''
		 );
		 if(false != ($handle=@opendir($uploadpath)))
		 {
			 while(false != ($filename=@readdir($handle)))
			 {
				 if($filename==$searchfilename)
				 {
					 $arr['filepath']=$uploadpath.$filename;
					 $arr['dir']=$uploadpath;
					 $arr['filename']=$filename;
					 $arr['fileDownloadpath']=HOST_DIR.'/YKJ/grYKJ/'.Cookie::get('grxuehao').'/upload/'.$filename;
					 return json_encode($arr);
				 }
				 
			 }
			 closedir($handle);
			 $arr['code']=1;
			 $arr['msg']='没有找到您搜索的文件哦';
				 return json_encode($arr);
		 }
		  $arr['code']=1;
			 $arr['msg']='读取目录失败';
				 return json_encode($arr);
	
	 }
	 
	 
	public function addzip()//压缩文件
		{
			$path=request()->post('path');
			$zipname=request()->post('zipname');
			if(taskShowController::zip($path, $zipname))
				{
				return "已压缩";
				}
				else{
					return "已存在";
				}
		}
	
	
     
     
	public function renamefunc()//班级空间里面的修改文件名
		{
			$file = request()->post('path');
			$dir = request()->post('newdir');
			$newname = request()->post('newname');
			if(rename($file,$dir)){
			
				Db::name(Cookie::get('banjikey')."_file_info")->where('filepath', $file)->update(['filepath' => $dir,'filename' => $newname]);
			return "yes";
			}else{
			return "no";
			}
		}	
     
     
     
     
	 public function renamefunc_gr()//个人云空间里面的修改文件名
		{
			$file = request()->post('path');
			$dir = request()->post('newdir');
			rename($file,$dir);
			return "yes";
			
		}	
     
     
     
     
     
     
     
     
     
     public function setshare($filepath)//文件共享设置
	 {
		 $task=request()->post('task');
		 $oldfilepath=request()->post('filepath');
		  $dirarr=explode('/',$filepath);
		 $sharepath=$dirarr[0].'/'.$dirarr[1].'/'.$dirarr[2]."/share/";
		 $newfilename=$sharepath.$dirarr[4];
		 $arr=array(
		 'code'=>0
		,'msg'=>'',
			 
		 );
		 if($task==1)//设置共享复制文件到共享空间
		 {
       if(!$this->dir_exist_file($newfilename))
        {
			if(copy($oldfilepath,$newfilename))
			{
          
				$arr['msg']='共享设置成功';
				return json_encode($arr);
			}
           else
           {
            $arr['code']=1;
				 $arr['msg']="设置失败";
				 return json_encode($arr); 
           }
           
        }
			 else
			 {
				$arr['code']=1;
				 $arr['msg']="共享空间已存在该文件";
            return json_encode($arr); 
			 }
			 
		 }
		 else//取消共享删除共享空间的文件
		 {
			 if(unlink($oldfilepath))
			{
			
				$arr['msg']='取消共享设置成功';
				return json_encode($arr);
			}
			else{
				 $arr['code']=1;
				 $arr['msg']="设置失败";
				 return json_encode($arr);
			}
		 }
		 
	 }
     
     
     
     
     
     
     
     
     
     
	 public function sharemain()//有关共享空间的方法
	 {
		 $task=request()->post('task');
         	 $arr=array(
			'code'=>0,
			'xuehao'=>'',
			'msg'=>''
			 );
		 if($task=='1')//开通共享空间
		 {
		$sharekey=request()->post('sharekey');
		 $grxuehao=Cookie::get('grxuehao');
		 $grpassword=Cookie::get('grpassword');
		 $sharepath=Cookie::get('grdir').'share/';
		 Db::name('xukekey')->where(['xuehao'=>$grxuehao,'yontu'=>'个人','password'=>$grpassword])->update(['sharekey'=>$sharekey,'sharepath'=>$sharepath]);
		mkdir($sharepath,0777,true);
			 $arr['msg']="开通成功"; 
		 
 			
		 
		 }
		 if($task=="2")//如果用户是在主入口输入分享口令的就根据口令查找共享空间(查询别人是否开通共享空间(共享口令有效性))
		{
		
			$sharekey=request()->post('sharekey'); 
			$result=Db::name('xukekey')->where('sharekey',$sharekey)->select();
			if(!$result->isEmpty())//开通了共享空间
			{
				foreach($result as $r)
				{
					$arr['xuehao']=$r['xuehao'];
					if(!empty(Cookie::get('sharexuehao')))
					{
						Cookie::delete('sharexuehao');
						Cookie::delete('sharepath');
						Cookie::set('sharexuehao',$r['xuehao'],3600);
						Cookie::set('sharepath',$r['sharepath'],3600);
					}
					else
					{
						Cookie::set('sharexuehao',$r['xuehao'],3600);
						Cookie::set('sharepath',$r['sharepath'],3600);
					}
				}
				 $arr['msg']="共享空间有效"; 
			
		 	}else
			{
				$arr['code']=1;
				 $arr['msg']="无效共享空间"; 
			} 
		}	
		 if($task=="3")//在自己的个人空间里面打开的共享空间(判断自己是否开通共享空间)
		{
			
			$grxuehao=Cookie::get('grxuehao');
			$grpassword=Cookie::get('grpassword');
			$result=Db::name('xukekey')->where(['xuehao'=>$grxuehao,'password'=>$grpassword,'yontu'=>'个人'])->select();
			if(!$result->isEmpty())
			{
				foreach($result as $r)
				{
					if(is_dir($r['sharepath']))//开通了共享空间
					{
						
						if($r['sharepath']==Cookie::get('sharepath'))
						{
							
			 				$arr['msg']='已开通共享空间并且本地共享空间路径也是自己的';
						} 
						else
						{
						Cookie::delete('sharexuehao');
						Cookie::delete('sharepath');
						Cookie::forever('sharexuehao',$r['xuehao']);
						Cookie::forever('sharepath',$r['sharepath']);
							$arr['msg']='已开通共享空间但本地共享空间路径不是自己的，已设置为自己的了';
						}
						
					}else
					{
							$arr['code']=1;
			 				$arr['msg']='没有开通共享空间';
					} 
				}
				
				
		 	}else
			{
							$arr['code']=1;
			 				$arr['msg']='数据库没有你的信息';
			} 
			
		}
		 if($task=='4')//修改sharekey
		 {
			 $sharekey=request()->post('sharekey'); 
			 $grxuehao=Cookie::get('grxuehao');
			 $grpassword=Cookie::get('grpassword');
			  Db::name('xukekey')->where(['xuehao'=>$grxuehao,'yontu'=>'个人','password'=>$grpassword])->update(['sharekey'=>$sharekey]);
        
			 $arr['msg']="共享口令修改成功，您的新口令为：".$sharekey."。分享给好朋友吧！"; 
		 }
		 if($task=='5')//判断是否是自己的共享空间
		 {
			 if(!empty(Cookie::get('grxuehao'))&&!empty(Cookie::get('grpassword')))
			 {
				$grxuehao=Cookie::get('grxuehao');
				$grpassword=Cookie::get('grpassword');
				$result = Db::name('xukekey')->where(['xuehao'=>$grxuehao,'password'=>$grpassword,'yontu'=>'个人'])-select();
				 if(!$result->isEmpty())
				 {
					 foreach($result as $res)
					 {
						 if($res['sharepath']==Cookie::get('sharepath'))
						 {
							 $arr['msg']="是自己的共享空间"; 
						 }
						 else
						 {
                    $arr['code']=1;
							 $arr['msg']= "没有权限哦";
						 }
					 }
				 }
				 else
				 {
                $arr['code']=1;
					 $arr['msg']='用户不存在';
				 }
				 
			 }
			 else
			 {
            $arr['code']=1;
				  $arr['msg']="您还未登录哦，请先登录";
			 }
			
			
		 }
	return json_encode($arr);
		 
		 
		
	 }
	 
	 
	 
	 public function share_task_show()//返回视图
		{
		//返回用户级别视图//需要共享空间路径和共享空间主人学号
		 
			$sharexuehao=Cookie::get('sharexuehao');
			$sharepath=Cookie::get('sharepath');
		 
			//遍历文件夹下所有文件
			if (false != (@$handle = opendir ( $sharepath ))) 
			{
				$k = 0;
				$content="";
				$content0="";
				$content1="";
				$content2="";
				while (false !== ($file = readdir($handle))) 
				{
					$fileDownloadpath=HOST_DIR.'/YKJ/grYKJ/'.$sharexuehao.'/share/'.$file;
					$filepath=$sharepath.$file;
					if ($file != "." && $file != ".." && !is_dir($sharepath.'/'.$file)) 
						{
						$size = filesize($sharepath.$file);
						$size =taskShowController::trans_byte($size);
						$filedata =date("Y-m-d H:i:s",filemtime($sharepath.$file));
						$arr=explode('.', $file);
						$content0="
												 <td class=\"td\">
												 <div class=\"container\">
							  <div class=\"card\">
								<div class=\"fac face1\">
								  <div class=\"content\">
									<span class=\"stars\"></span>

							<div class=\"svg-wrapper\">
							  <svg height=\"40\" width=\"150\" xmlns=\"http://www.w3.org/2000/svg\">
								<rect class=\"shape\" height=\"40\" width=\"150\" />
								<div class=\"text\">
								  <a href=\"$fileDownloadpath\" download=\"$file\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px;\"><span class=\"spot\"></span>下载</a> 

								</div>
							  </svg>
							</div>
							<br/>";

								$content2=" </div>
								</div>
								<div class=\"face face2\" style=\"background-image: linear-gradient(40deg, #fff 0%, #000 45%, #fff 100%);\" >
								  <h2 style=\"margin-bottom:30%;\">$file</h2> 
								<h1 >文件大小：$size</h1>
								  <h1 >提交日期：$filedata</h1>
								 <h1 id=\"$k\" data-filepath=\"$filepath\" >文件ID：$k</h1>

								</div>

							  </div>
								</div>
								</td>
								";
       //特殊情况当用户学号密码都为0时empty的效果就不能达到预期效果了//	if(!empty(Cookie::get('grxuehao'))&&!empty(Cookie::get('grpassword')))
		if(!empty(Cookie::get('grxuehao'))&&!empty(Cookie::get('grpassword')))
		{
				$grxuehao=Cookie::get('grxuehao');
				$grpassword=Cookie::get('grpassword');
				$result = Db::name('xukekey')->where(['xuehao'=>$grxuehao,'password'=>$grpassword,'yontu'=>'个人'])->select();
			 if(!$result->isEmpty())
				 {
					 foreach($result as $res)
					 {
						 if($res['sharepath']==Cookie::get('sharepath'))
						 {
							$content1="
								<div class=\"svg-wrapper\">
							  <svg height=\"40\" width=\"150\" xmlns=\"http://www.w3.org/2000/svg\">
								<rect class=\"shape\" height=\"40\" width=\"150\" />
								<div class=\"text\">
								  <a onclick=\"setshare('$filepath','cancel')\" style=\"min-height: 50px; margin-top: 20px; opacity:0.7 ; min-width: 150px;\"><span class=\"spot\"></span>取消共享</a> 

								</div>
							  </svg>
							</div>";
						 }
							
					}
				}
							
		}
						
						
						$content=$content.$content0.$content1.$content2;
							}
				
					if(($k+1)%5==0)
					{
						$content=$content."</tr><tr>";
					}	
						if($k+1==5)
					{
						$addcss="<link rel=\"stylesheet\" href=\"/static/css/zhishiyingtable.css\">";
					}
					$k+=1;
				}
	//关闭句柄
			closedir($handle);
			}
		
			View::assign(['content'=>@$content,'addcss'=>@$addcss,'HOST_DIR'=>HOST_DIR,'xuehao'=>$sharexuehao]);
			return View::fetch('../view/task_show_controller/classfile_user_show.php'); 
		 
		
		}
		
	 
	 
	public function dir_exist_file_func()//判断某个目录下是否存在文件使用//public function dir_exist_file($path)方法
		{
			$path=request()->post('path');
			if(taskShowController::dir_exist_file($path))
			{
			return "yes";

			}else
			{
			return "no"; 	
			}
		}
	
	
			/**
		 * 判断某个目录下是否存在文件
		 * @param string $path 要进行判断的目录
		 * @return bool|string
		 */
		public function dir_exist_file($path)
		{
        
			if (!is_dir($path)) {
				return false;
			}

			$files = scandir($path);

			// 删除  "." 和 ".."
			unset($files[0]);
			unset($files[1]);

			// 判断是否为空
			if (!empty($files[2])) {
				//此处根据实际需要看是否要返回该文件夹内的文件名称
				//注意，此处仅仅只是返回了一个文件名称。若有需求要获取多个（全部），可能需要循环获取
				//return $files[2];
				return true;
			}

			return false;
		}

	
	

/**
 * 使用ZIP压缩文件或目录
 * @param  [string] $toName   压缩后的文件名
 * @param  [string] $fromName 被压缩的文件或目录名
 * @return [bool]             成功返回TRUE, 失败返回FALSE
 */
		public function zip($fromName, $toName)
		{
			if(!file_exists($fromName)){
				return FALSE;
			}
			$zipArc = new \ZipArchive();
			if(!$zipArc->open($toName, \ZipArchive::CREATE)){
				return FALSE;
			}
			$res = is_dir($fromName) ? $zipArc->addGlob("{$fromName}/*") : $zipArc->addFile($fromName);
			if(!$res){
				$zipArc->close();
				return FALSE;
			}
			return $zipArc->close();
		}
	
}