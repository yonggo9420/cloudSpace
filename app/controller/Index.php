<?php
namespace app\controller;

use app\BaseController;
use think\facade\View;
use think\facade\Cookie;
use think\facade\Db;
use think\facade\Request;



class Index extends BaseController
{

    public function index()
    {
    	 View::assign('title','云空间');
		 return View::fetch('index');
    }


 public function apply()
    {
      $task= request()->get('task');
      $page= "";
      if($task==1)
      {
         $page= "apply_gr"; 
      }
      if($task==2)
      {
         $page= "apply_class"; 
      }
      if($task==3)
      {
         $page= "apply_subject"; 
      }
    	
		 return View::fetch($page);
    }
 


    public function searchClass()
    {
			$arr= array	(
					'code'=>0,
					'msg' => "目前一切正常",
					'password'=>''
					);
       // 读取某个cookie数据
		if(Cookie::get('banjikey'))
		{
		$arr['msg']="已确认身份";

		}
		else
		{
		
		$banjikey= request()->post('banjikey');
		$result=Db::table('xukekey')->where('banjikey', $banjikey)->select()->toArray();
			
			if(!empty($result))
			{
				if($result[0]['yontu']=="收作业")
				{
					$userDir="YKJ/szyYKJ/".$banjikey."/";
					Cookie::forever('banji', $result[0]['banji']);
					Cookie::forever('yontu', $result[0]['yontu']);
					Cookie::forever("dir",$userDir);
					Cookie::forever("banjikey",$banjikey);

					$arr['msg']="已确认身份";

				}
				else
				{
					$arr['code']=1;
					$arr['msg']= "该云空间不是此类型";
				}
			}
			else
			{
				$arr['code']=1;
				$arr['msg']= "未开通云空间";
			}
		}
			return json_encode($arr);	
    }
	

	
	public function backstage_YKJ()
	{
		$arr= array	(
					'code'=>0,
					'msg' => "目前一切正常"
					);
		$task=request()->post('task');
		
		
		if($task=="1")
		{
			$banjikey=request()->post('banjikey');
			$arr['msg']="班级口令状态未知";
		
			$result=Db::table('xukekey')->where('banjikey', $banjikey)->select()->toArray();
			if(empty($result))
			{
			$arr['msg']='班级口令未使用';
			}
			else
			{
				$arr['code']=1;
				$arr['msg']='班级口令已有人使用';
			}
		}
		
		if($task=="2")
		{
			$name=request()->post('name');
			$yontu=request()->post('yontu');
			$xuehao=request()->post('xuehao');
			$password=request()->post('password');
			$banji=request()->post('banji');
			$key=Cookie::get('key');

				if($yontu=="个人")
				{
                    $res=Db::name('xukekey')->where(['xuehao'=>$xuehao,'yontu'=>'个人'])->select();
                    
            if(!$res->isEmpty())
            {
                $arr['code']=1;
				    $arr['msg']='一个学号只能拥有一个个人空间哦';
            return json_encode($arr);
            }
					$useruploadDir="YKJ/grYKJ/".$xuehao."/upload/";
					$usermainDir="YKJ/grYKJ/".$xuehao."/main/";
					$userzipDir="YKJ/grYKJ/".$xuehao."/zip/";
					if(!is_dir($useruploadDir))//判断目录是否存在
					{
						mkdir ($useruploadDir,0777,true);//如果目录不存在则创建目录
						mkdir ($usermainDir,0777,true);
						mkdir ($userzipDir,0777,true);
					}
					$userDir="YKJ/grYKJ/".$xuehao;
					$userzipDir="YKJ/grYKJ/".$xuehao."/zip/grYKJ";
             $key=time();
					$data = [
							'key' =>$key,
							'xuehao' => $xuehao,
							'name'=>$name,
							'banji'=>$banji,
							'password'=>$password,
							'yontu'=>$yontu,
							'isuse'=>1,
							'banjikey'=>$password,
							'mulu'=>"YKJ/grYKJ/".$xuehao.'/'.$name.'/'
							 ];
					        Db::name('xukekey')->insert($data);
                                                $this->delecookie();
						Cookie::forever('grxuehao',$xuehao);
						Cookie::forever('grpassword',$password);
						$dir="YKJ/grYKJ/".$xuehao."/";
						Cookie::forever('grdir',$dir);
                    
					$arr['msg']="申请成功,".$key."这是您的空间唯一ID，用以找回密码证明身份等，请妥善保管";
					
				}	
			
				if($yontu=="收作业")
				{
           
				 $banjikey=request()->post('banjikey');
				
					$useruploadDir="YKJ/szyYKJ/".$banjikey."/upload/";
					$usermainDir="YKJ/szyYKJ/".$banjikey."/main/";
					$useryaoqiuDir="YKJ/szyYKJ/".$banjikey."/yaoqiu/";
					$userzipDir="YKJ/szyYKJ/".$banjikey."/zip/";
					if(!is_dir($useruploadDir))//判断目录是否存在
					{
						mkdir ($useruploadDir,0777,true);//如果目录不存在则创建目录
						mkdir ($usermainDir,0777,true);
						mkdir ($useryaoqiuDir,0777,true);
						mkdir ($userzipDir,0777,true);
					}
					$userDir="YKJ/szyYKJ/".$banjikey;
					$userzipDir="YKJ/szyYKJ/".$banjikey."/zip/szyYKJ";
            $key=time();
						$data = [
							'key'=>$key,
							'xuehao' => $xuehao,
							  'name'=>$name,
							  'banji'=>$banji,
							  'password'=>$password,
							  'yontu'=>$yontu,
							  'isuse'=>1,
							  'banjikey'=>$banjikey,
							  'mulu'=>"YKJ/szyYKJ/".$banjikey
							 ];
					Db::name('xukekey')->insert($data);
					Db::query("
					CREATE TABLE `$banjikey`  (
					  `id` int NOT NULL AUTO_INCREMENT,
					  `xuehao` int NULL,
					  `name` varchar(255) NULL,
					  `filenumber` int NOT NULL DEFAULT 0,
					  PRIMARY KEY (`id`)
					);");	
             Db::query("
                CREATE TABLE `".$banjikey."_file_info`  (
                  `id` int NOT NULL AUTO_INCREMENT,
                  `filename` varchar(255) NULL,
                  `filepath` varchar(255) NULL,
                  `xuehao` varchar(255) NULL,
                  PRIMARY KEY (`id`)
                );");
                    
					Cookie::delete('key');
					Cookie::delete('banji');
					Cookie::delete('yontu');
					Cookie::delete("xuehao");
					Cookie::delete("dir");
					Cookie::delete("banjikey");
					Cookie::delete("password");
					
					$arr['msg']="申请成功,".$key."这是您的空间唯一ID，用以找回密码证明身份等，请妥善保管";
				}

/*
			#写入
			
			$text=$useruploadDir.PHP_EOL.$usermainDir.PHP_EOL.$name.PHP_EOL.$xuehao;
			$fp=fopen("$usermainDir"."maininfo.txt",'ab');
			fwrite($fp,$text,strlen($text));
			fclose($fp);
				 /*
				#读取
				$fb=fopen("$DOCUMENT_ROOT/YKJ/userYkj/".$xuehao.$name."/main/".$xuehao.$name.$key.".txt",'rb');//根目录/YKJ/userYkj/学号+姓名/main/学号+姓名+许可码.txt
				while(!feof($fb)){
					$order=fgets($fb,2048);
					echo $order."<br>";
				}
			*/
		
	}
		
		return json_encode($arr);	
}
	public function delecookie()
	{
		Cookie::delete('key');
		Cookie::delete('banji');
		Cookie::delete('yontu');
		Cookie::delete("xuehao");
		Cookie::delete("grxuehao");
		Cookie::delete("dir");
		Cookie::delete("grdir");
		Cookie::delete("gryontu");
		Cookie::delete("banjikey");
		Cookie::delete("grpassword");
		Cookie::delete("password");
		Cookie::delete("sharexuehao");
		Cookie::delete("sharepath");
		Cookie::delete("banjikey");
		
		
		return "已清除";
	}
	
}
