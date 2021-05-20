<?php
namespace app\controller;

use app\BaseController;
use think\facade\View;
use think\facade\Cookie;
use think\facade\Request;
use think\facade\Db;
class mobileorpcController extends BaseController
{

	public function Index(){
		$result=Db::table('xukekey')->where('banjikey',Cookie::get('banjikey'))->select()->toArray();
		
					$time1=date("Y-m-d H:i:s");                              //获取当前时间
			if(strtotime($time1)-strtotime($result[0]['task_time'])>=0){                   //对两个时间差进行差运算

				$stop="1";    //倒计时过期                        

			}else{

				$stop="0";                            

			}
			if(!empty(Cookie::get('xuehao'))&&!empty(Cookie::get('password')))//如果管理员曾经登录过并且没有清除数据
			{
				$result=Db::table('xukekey')->where(['xuehao'=>Cookie::get('xuehao'),'password'=>Cookie::get('password'),'yontu'=>'收作业'])->select();//根据学号和密码查询数据库信息
				foreach($result as $r)
				{
					if(@$r['banjikey']!=@Cookie::get('banjikey'))//如果不是自己管理的班级云空间
					{
						Cookie::delete('password');//清空本地管理员学号和密码
						Cookie::delete('xuehao');
					}
				}
			}
			
			View::assign(['stop'=>$stop,'zyname'=>$result[0]['zyname'],'task_time'=>$result[0]['task_time'],'banji'=>Cookie::get('banji'),'password'=>Cookie::get('password'),'xuehao'=>Cookie::get('xuehao')]);
		if(Request::instance()->isMobile())
		{
			return View::fetch('jiaozymobile');
		}
		else
		{
		
			return View::fetch('jiaozypc');
		}
		
	}
	
public function changepassword()
{
		$task= request()->post('task');//获取步骤号
		$arr= array(
				'code'=>0,
				'msg'=>'',
				'xukekey'=>''
				);
		if($task==1)//验证许可码是否正确
		{
			$xukekey=request()->post('xukekey');
			$result=Db::name('xukekey')->where('key',$xukekey)->select();
			if(!$result->isEmpty())
			{
				$arr['msg']='正确许可码';
				$arr['xukekey']=$xukekey;
				return json_encode($arr);
			}else
			{
				$arr['code']=1;
				$arr['msg']='无效许可码';
				return json_encode($arr);
				
			}
		}
	if($task==2)
	{
		$newpassword= request()->post('newpassword');//获取密码
		$xukekey= request()->post('xukekey');//获取许可码
		Db::name('xukekey')->where('key',$xukekey)->update(['password'=>$newpassword]);
		$arr['msg']='修改密码成功，您的新密码是'.$newpassword;
		return json_encode($arr);
	}
			
			
	
	
}
	
	
	
	public function jiaozyupload()
	{
		View::assign(['DIR_CSS'=>DIR_CSS,'url'=>'jiaozyupload_file','DIR_JS'=>DIR_JS,'DIR_IMG'=>DIR_IMG]);
		return View::fetch('upload');
	}
	
	public function upload_task1()
	{
		$xuehao=request()->post('xuehao');
		$res=Db::name(Cookie::get('banjikey'))->where('xuehao',$xuehao)->select();
		if(!$res->isEmpty())
		{
			Cookie::forever('xuehao',$xuehao);
			return "ok";
		}
		else
		{
			return "您的学号为:".$xuehao."该班级不存在学号为:".$xuehao."的学生";
		}
	
		
	}

public function yaoqiu_upload_file()
	{
		$arr = array(
		'code' => 0,
		'filenewname'=>$_FILES["file"]["name"],
		'msg'=>Cookie::get('dir').'/yaoqiu'.$_FILES["file"]["name"],
		'data' =>array(
     	'src' => $_FILES["file"]["name"]
     ),
			'path'=>'无'
		);
		
	
		if(!file_exists(Cookie::get('dir').'/yaoqiu/'.$_FILES["file"]["name"]))
		{
			$file=Request::file('file');
			$yontu='szyYKJ';
			$path=\think\facade\Filesystem::disk('public')->putFile('YKJ/'.$yontu.'/'.Cookie::get('banjikey').'/yaoqiu',$file,
																	function () use ($file){
																		$nameArr=explode('.',$_FILES["file"]["name"]);

																		return chop($_FILES["file"]["name"],'.'.end($nameArr));
																	});
			$arr['path']=$path;
			$arr['msg']="上传成功";
			
			
		}
		else
		{
			$arr['code']=1;
			$arr['msg']="文件已存在";
			
		}
		
		
		return json($arr);
		
	}
	



	public function jiaozyupload_file()//班级空间学生文件上传方法
	{
		$arr = array(
		'code' => 0,
		'filenewname'=>$_FILES["file"]["name"],
		'msg'=>Cookie::get('dir').'/upload'.$_FILES["file"]["name"],
		'data' =>array(
     	'src' => $_FILES["file"]["name"]
     ),
			'path'=>'无'
		);
		
		if(!file_exists(Cookie::get('dir').'/upload/'.$_FILES["file"]["name"]))
		{
			$file=Request::file('file');
			$yontu='szyYKJ';
			$path=\think\facade\Filesystem::disk('public')->putFile('YKJ/'.$yontu.'/'.Cookie::get('banjikey').'/upload',$file,
																	function () use ($file){
																		$nameArr=explode('.',$_FILES["file"]["name"]);

																		return chop($_FILES["file"]["name"],'.'.end($nameArr));
																	});
            
			$arr['path']=$path;
			$arr['msg']="上传成功";
        $filenumber=0;
        $result=Db::name(Cookie::get('banjikey'))->where('xuehao', Cookie::get('xuehao'))->select();
        if(!$result->isEmpty())
        {
           foreach($result as $r)
           {
               $filenumber=$r['filenumber']+1;
               
           }
        }
			Db::name(Cookie::get('banjikey'))->where('xuehao', Cookie::get('xuehao'))->update(['filenumber' => $filenumber]);
        Db::name(Cookie::get('banjikey').'_file_info')->save(['filename' =>$_FILES["file"]["name"],'filepath' =>$path,'xuehao'=>Cookie::get('xuehao')]);
       
        
			
		}
		else
		{
			$arr['code']=1;
			$arr['msg']="文件已存在";
			
		}
		
		
		return json($arr);
		
	}
	public function grupload_file()
	{
		$arr = array(
		'code' => 0,
		'filenewname'=>$_FILES["file"]["name"],
		'msg'=>Cookie::get('grdir').'/upload'.$_FILES["file"]["name"],
		'data' =>array(
     	'src' => $_FILES["file"]["name"]
     ),
			'path'=>'无'
		);
		
		if(!file_exists('YKJ/grYKJ/'.Cookie::get('grxuehao').'/upload/'.$_FILES["file"]["name"]))
		{
			$file=Request::file('file');
			$yontu='grYKJ';
			$path=\think\facade\Filesystem::disk('public')->putFile('YKJ/'.$yontu.'/'.Cookie::get('grxuehao').'/upload',$file,
																	function () use ($file){
																		$nameArr=explode('.',$_FILES["file"]["name"]);

																		return chop($_FILES["file"]["name"],'.'.end($nameArr));
																	});
			$arr['path']=$path;
			$arr['msg']="上传成功";
			
		}
		else
		{
			$arr['code']=1;
			$arr['msg']="文件已存在";
			
		}
		
		
		return json($arr);
		
	}
	
}