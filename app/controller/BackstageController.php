<?php
namespace app\controller;

use app\BaseController;
use think\facade\View;
use think\facade\Cookie;
use think\facade\Db;
use think\facade\Request;
use phpoffice\phpexcel\Classes\PHPExcel\Reader\Excel2007;
use phpoffice\PHPExcel\Classes\PHPExcel;
use phpoffice\PHPExcel\Classes\PHPExcel\IOFactory;

class BackstageController extends BaseController
{

    public function index()
    {
		$arr=array(
        'code'=>0,
        'msg'=>''
        );
        $task=request()->post('task');
        if($task==1)//修改密码
        {
            $newpassword=request()->post('newpassword');
            $oldpassword=request()->post('oldpassword');
             $grxuehao=Cookie::get('grxuehao');
            Db::name('xukekey')->where(['xuehao'=>$grxuehao,'yontu'=>'个人','password'=>$oldpassword])->update(['password'=>$newpassword]);
            Cookie::delete('grpassword');
            Cookie::forever('grpassword',$newpassword);
            $arr['msg']='修改成功，您的新密码是:'.$newpassword."请妥善保管！";
            
        }
        
        return json_encode($arr);
        
        
    }  
	
    
    public function backstage_personal()
    {
        $sharekey='';
        $state_change="开通";
        $ID="";
		$result=Db::table('xukekey')->where(['xuehao'=>Cookie::get('grxuehao'),'password'=>Cookie::get('grpassword'),'yontu'=>'个人'])->select();
		if(!$result->isEmpty())//用户是否存在
		{
            foreach($result as $r)
            {
               $sharekey=$r['sharekey']; 
                if(!empty($sharekey))
                {
                    $state_change="关闭";
                }
                 $ID=$r['key'];
            }
     
     }
		
			View::assign(['password'=>@Cookie::get('grpassword'),'ID'=>@$ID,'xuehao'=>@Cookie::get('grxuehao'),'state_change'=>$state_change,'sharekey'=>@$sharekey]);
			 return View::fetch('../view/backstage_controller/backstage_personal.php');
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    

	//-----------------------------------------------------班级空间区---------------------------------------------
	
    public function backstage_class()
    {
        
    	 View::assign('title','云空间');
    	 View::assign(['DIR_CSS'=>DIR_CSS,'DIR_JS'=>DIR_JS,'DIR_IMG'=>DIR_IMG]);
		$result=Db::table('xukekey')->where('banjikey',Cookie::get('banjikey'))->select()->toArray();
		$result2=Db::table(Cookie::get('banjikey'))->select();
		$usertable="";
        $banjikey=Cookie::get('banjikey');
        $dir=Cookie::get('dir').'upload/';
        $zipdir='./YKJ/szyYKJ/'.$banjikey.'/zip/';  //  根目录/YKJ/zip/szyYKJ/
		$ID=$result[0]['key'];
        $allfilenumber =Db::name(Cookie::get('banjikey'))->sum('filenumber');
        $zipallname='YKJ/szyYKJ/'.$banjikey.'/zip/szyYKJ'.$banjikey.'.zip'; 
        $zipname='szyYKJ'.$banjikey.".zip"; 
        $zipDownloadAllName=HOST_DIR.$zipallname;
		if(!$result2->isEmpty())
		{
			$usertable=" <table class=\"imagetable\" style=\"width:-moz-available;\">
    <tr>
        <th>ID</th><th>学号</th><th>姓名</th><th>操作</th>
    </tr>
    <tr>";
				
			
			
			
			
			$weijiaoresult=Db::table(Cookie::get('banjikey'))->where('filenumber',0)->select();//查询已交和未交文件的学生
			$yijiaoresult=Db::table(Cookie::get('banjikey'))->where('filenumber','<>',0)->select();
			if(!$weijiaoresult->isEmpty())
				{
					$weijiaousertable="      <table class=\"imagetable\" style=\"float:left;\">
    <tr>
        <th>学号</th><th>姓名</th>
    </tr>
    ";
			// 获取数据集记录数
			$weijiaousernum= count($weijiaoresult);
			// 遍历数据集
			foreach($weijiaoresult as $weijiaores)
			{
			$weijiaoname= $weijiaores['name'];
			$weijiaoxuehao= $weijiaores['xuehao'];
			$weijiaousertable_="
			<tr id=\"$weijiaoxuehao\">
						  <td>$weijiaoxuehao</td><td>$weijiaoname</td>
			</tr>
			
					";
			$weijiaousertable=$weijiaousertable.$weijiaousertable_;
		}
			$weijiaousertable=$weijiaousertable." </tr></table>";
				}
			else
			{
				$weijiaousernum=0;
				$weijiaousertable="<h1>无</h1>";
			}
			
			
			
			
			
			if(!$yijiaoresult->isEmpty())
				{
					$yijiaousertable="      <table class=\"imagetable\" style=\"float: right\">
    <tr>
        <th>学号</th><th>姓名</th><th>已交文件数</th><th>移除</th><th>文件操作</th>
    </tr>
    ";
			// 获取数据集记录数
			$yijiaousernum= count($yijiaoresult);
			// 遍历数据集
			foreach($yijiaoresult as $yijiaores)
			{
			$yijiaoname= $yijiaores['name'];
			$yijiaoxuehao= $yijiaores['xuehao'];
			$yijiaofilenumber= $yijiaores['filenumber'];
                
                
			$yijiaousertable_="
			<tr id=\"$yijiaoxuehao\">
						  <td>$yijiaoxuehao</td><td>$yijiaoname</td><td>$yijiaofilenumber</td><td><button onclick=\"yichu('yijiao','$yijiaoxuehao')\">手动移除</button></td><td><button onclick=\"showfile('$yijiaoxuehao')\">查看文件</button></td>
			</tr>
			
					";
			$yijiaousertable=$yijiaousertable.$yijiaousertable_;
		}
			$yijiaousertable=$yijiaousertable." </tr></table>";
				}
			else
			{
				$yijiaousernum=0;
				$yijiaousertable="<h1>无</h1>";
			}
			
			
			
			
		// 获取数据集记录数
		$usernum = count($result2);
			// 遍历数据集
			foreach($result2 as $res)
			{
			$name= $res['name'];
			$id= $res['id'];
			$xuehao= $res['xuehao'];
			$usertable_="
						  <td>$id</td><td>$xuehao</td><td>$name</td><td><button onclick=\"userinfoupdate('$id','$xuehao','$name')\">修改</button></td>
			</tr>
			<tr>
					";
			$usertable=$usertable.$usertable_;
		}
			$usertable=$usertable." </tr></table><br/><button  id=\"upload\" >重新导入班级人员名单</button>";
		}
		else
		{
		$usernum = 0;
		$usertable=" <h1>上传文件示例</h1><br/> <h5>请上传.xlsx类型的Excel文件将班级成员名单导入数据库，如下图所示：<br/>A列单元格填写学生学号，B列单元格填写学生姓名<br/><h3 style=\"color:red;\">A1和B1单元格为字段名，请分别填写'学号'，'姓名'</h3></h5><br/><img src=\"/static/images/backstage/shili.png\"  alt=\"上传文件示例\" /><br/>
		<button class=\"layui-btn layui-btn-normal\"  id=\"upload\" >导入班级人员名单</button><a href=\"".HOST_DIR."/static/file/backstage/shili.xlsx\" download=\"示例模板文件.xlsx\" class=\"layui-btn\"  >下载模板文件</a>";
		$weijiaousernum=0;
		$yijiaousernum=0;
		$weijiaousertable="<h1>无</h1>";
		}
 
		
			View::assign(['usertable'=>@$usertable,'allfilenumber'=>$allfilenumber,'ID'=>@$ID,'banjikey'=>@Cookie::get('banjikey'),'password'=>@Cookie::get('password'),'weijiaousertable'=>@$weijiaousertable,'yijiaousertable'=>@$yijiaousertable,'usernum'=>@$usernum,'weijiaousernum'=>@$weijiaousernum,'yijiaousernum'=>@$yijiaousernum,'banji'=>@$result[0]['banji'],'zyname'=>@$result[0]['zyname'],'task_time'=>@$result[0]['task_time'],'zipallname'=>@$zipallname,'zipDownloadAllName'=>@$zipDownloadAllName,'zipname'=>@$zipname,'zipdir'=>@$zipdir,'dir'=>@$dir]);
			 return View::fetch('../view/backstage_controller/backstage_class.php');
    }
    
    
    
    
    
    
    
    
    
    public function upload_file()
	{
		$arr = array(
		'code' => 0,
		'filenewname'=>$_FILES["file"]["name"],
		'msg'=>Cookie::get('dir').'/main'.$_FILES["file"]["name"],
		'data' =>array(
     	'src' => $_FILES["file"]["name"]
     ),
			'path'=>'无'
		);
		$file=Request::file('file');
		$yontu='szyYKJ';
		$nameArr=explode('.',$_FILES["file"]["name"]);
		if(end($nameArr)=="xlsx")
		{
			$path=\think\facade\Filesystem::disk('public')->putFile('YKJ/'.$yontu.'/'.Cookie::get('banjikey').'/main',$file,
																	function () use ($file){
																		$nameArr=explode('.',$_FILES["file"]["name"]);

																		return chop($_FILES["file"]["name"],'.'.end($nameArr));
																	});
			$banjikey=Cookie::get('banjikey');
			Db::query("TRUNCATE TABLE `$banjikey`");
                        Db::name('xukekey')->where('banjikey',Cookie::get('banjikey'))->update(['excelpath' => $path]);
			$this->exceltosql($path,$banjikey);
			$arr['path']=$path;
			$arr['msg']="上传成功";
			
		}
		else
		{
			$arr['code']=1;
			$arr['msg']="请上传指定文件类型";
			
		}
		
		
		return json($arr);
		
	}
	
	private function exceltosql($path,$banjikey)
	{
		
		set_time_limit(0);
		error_reporting(0);
        $PHPReader = new \PHPExcel_Reader_Excel2007();
        //载入文件
        $PHPExcel = $PHPReader->load($path);
        //获取表中的第一个工作表，如果要获取第二个，把0改为1，依次类推
        $currentSheet = $PHPExcel->getSheet(0);
        //获取总列数
        $highestColumn = $currentSheet->getHighestColumn();
        //获取总行数
       $highestRow = $currentSheet->getHighestRow();
   
 
	for($j=2;$j<=$highestRow;$j++)                        //从第一行开始读取数据
    { 	
		$str="";
        for($k='A';$k<=$highestColumn;$k++)            //从A列读取数据
 
         { 
 
             $str .=$PHPExcel->getActiveSheet()->getCell("$k$j")->getValue().'|*|';//读取单元格
 
         } 
		$strs = explode("|*|",$str);
	
		$data = ['xuehao' => $strs[0], 'name' => $strs[1]];
		
		Db::name($banjikey)->insert($data);
      
	}
		
	}
	
	
	public function update()
    {
		$arr=array(
        'code'=>0,
        'msg'=>''
        );
		
		$task=request()->post('task');
		if($task==1)//修改班级名称和
		{
			$val=request()->post('val');
			$name=request()->post('name');
        $result="";
        $re="";
            if($name=="banjikey")//当修改的字段是班级口令时
            {
                $re=Db::table('xukekey')->where('banjikey', $val)->select();
                if(!$re->isEmpty())
                {
                    $arr['code']=1;
                    $arr['msg']="口令已有人使用了，请换一个吧";
                    return json_encode($arr);
                }
                
                Db::table('xukekey')->where('banjikey',Cookie::get('banjikey'))->update([$name => $val,'mulu'=>'YKJ/szyYKJ/'.$val]);
                if(!rename(Cookie::get('dir'),'YKJ/szyYKJ/'.$val))//重命名空间目录
                {
                   $arr['code']=1;
                    $arr['msg']="重命名空间目录失败";
                    return json_encode($arr);
                }
              
                Cookie::delete('dir');
                Cookie::forever('dir','YKJ/szyYKJ/'.$val.'/'); 
                $result=Db::table('xukekey')->where('banjikey',$val)->select();  
                Db::query('ALTER TABLE `'.Cookie::get('banjikey').'` RENAME TO `'.$val.'`');
                Db::query('ALTER TABLE `'.Cookie::get('banjikey').'_file_info'.'` RENAME TO `'.$val.'_file_info `');
                
            }else
            {
               Db::table('xukekey')->where('banjikey',Cookie::get('banjikey'))->update([$name => $val]);
                $result=Db::table('xukekey')->where('banjikey',Cookie::get('banjikey'))->select();  
            }
               
            }
			
            
			if(!$result->isEmpty())
			{
                foreach($result as $res)
                {
                    if($res[$name]==$val)
                    {
                        if($name=='password'||$name=='banji')
                        {
                        Cookie::delete($name);
                        Cookie::forever($name,$val); 
                        } 
                        if($name=='banjikey')
                        {
                        Cookie::delete('banjikey');
                        Cookie::forever('banjikey',$val); 
                        }
                        
                        $arr['msg']='修改成功';  
                    }
                }
           
			}
        else
        {
            
            $arr['msg']='修改失败';
            $arr['code']=1;
        }
            return json_encode($arr);
		}
        
    
	public function yichu()
    {
		$xuehao=request()->post('xuehao');
		$type=request()->post('type');
		$a=0;
        $arr=array(
        'code'=>0,
        'msg'=>'移除成功，已删除该生所有文件'
        );
		
		if($type=="yijiao")
		{
		$res=Db::name(Cookie::get('banjikey').'_file_info')->where('xuehao',$xuehao)->select();
      $a=Db::name(Cookie::get('banjikey').'_file_info')->where('xuehao',$xuehao)->count();
			foreach($res as $r)
			{
				
				if(unlink($r['filepath']))
			{
            Db::query('DELETE FROM `'.Cookie::get('banjikey').'_file_info'.'` WHERE `filepath` = '."'".$r['filepath']."'");
            
            $a=$a-1;
            Db::name(Cookie::get('banjikey'))->where('xuehao',$xuehao)->update(['filenumber'=> $a]);
			}
			else{
				
				 $arr['msg']='操作失败';
                
			}
			}
		 return json_encode($arr);
		}
    	
    }
	public function reset_all()//班级空间清空所有文件，并打包所有文件至压缩包
    {
		$banjikey=Cookie::get('banjikey');
	
	
	
				Db::query("DROP TABLE `".$banjikey.'_file_info'."`;");
				Db::query("DROP TABLE `$banjikey`;");
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
		$res=Db::name('xukekey')->where('banjikey',$banjikey)->select()->toArray();
		$this->exceltosql($res[0]['excelpath'],$banjikey);
	              unlink($res[0]['mulu'].'/zip/szyYKJ'.$banjikey.'.zip');
			if($this->zip($res[0]['mulu'].'/upload/', $res[0]['mulu'].'/zip/szyYKJ'.$banjikey.'.zip'))
				{
				$this->deleteDir($res[0]['mulu'].'/upload/');
					
					mkdir ($res[0]['mulu'].'/upload/',0777,true);

				}
				return "ok";
		
			
			
			
    	
    }
    
    public function isxuehao()//检测是否输入过学号//本地是否有学号cookie
    {
        $arr=array(
        'code'=>0,
        'msg'=>''
        );
        if(!empty(Cookie::get('xuehao')))
        {
            $arr['msg']=Cookie::get('xuehao');
            
        }else
        {
            $arr['code']=1;
            $arr['msg']="无本地学号cookie";
        }
        return json_encode($arr);
    }
    
    
    
    
    
    public function showfile()
    {
		$xuehao=request()->post('xuehao');
		$arr=array(
        'code'=>0,
        'msg'=>''
        );
        $filenumber=0;
        $filenumber =Db::name(Cookie::get('banjikey'))->where('xuehao',$xuehao)->value('filenumber');
        $content="<span style=\"font-size:18px;\">文件数：<strong style=\"font-size:18px;color:red\">$filenumber</strong></span><br/><hr><div style=\"min-width:300px;min-height:300px;text-align:center\">";
    $result = Db::name(Cookie::get('banjikey')."_file_info")->where('xuehao',$xuehao)->select();
		if(!$result->isEmpty())
      {
        foreach($result as $r)
        {
            $filename=$r['filename'];
            $filepath=$r['filepath'];
            $downloadpath=HOST_DIR.$filepath;
            $dir="YKJ/szyYKJ/".Cookie::get('banjikey')."/upload/";
            $content=$content."<a onclick=\"setfile('$filename','$filepath','$downloadpath','$dir')\" style=\"font-size:18px;\">$filename</a><br/><hr>";
        }
            $arr['msg']=$content."</div>";
        }
        else
        {
            $arr['code']=1;
            $arr['msg']="未交作业";
        }
            return json_encode($arr);
        }
        
        
    
    
    
    
    
	public function userinfoupdate()
    {
		$id=request()->post('id');
		$name=request()->post('name');
		$xuehao=request()->post('xuehao');
		Db::name(Cookie::get('banjikey'))->where('id',$id)->update(['xuehao'=> $xuehao,'name'=> $name]);
		return "ok";
    }
	
	public function usertable()
    {
	
    	$usertable="";
    	$result=Db::table(Cookie::get('banjikey'))->select();
    	if(!$result->isEmpty())
		{
			// 遍历数据集
			foreach($result as $res)
			{
			$name= $res['name'];
			$xuehao= $res['xuehao'];
			$usertable_="
						  <td>$xehao</td><td>$name</td><td><button onclick=\"userinfoupdate('$id','$xuehao','$name')\">修改</button></td>
			</tr>
			<tr>
					";
			$usertable=$usertable.$usertable_;
				}
			
			return print_r($result);
		}
		else
		{
			return '空的';
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
 * 删除当前目录及其目录下的所有目录和文件
 * @param string $path 待删除的目录
 * @note  $path路径结尾不要有斜杠/(例如:正确[$path='./static/image'],错误[$path='./static/image/'])
 */
function deleteDir($path) {

    if (is_dir($path)) {
        //扫描一个目录内的所有目录和文件并返回数组
        $dirs = scandir($path);

        foreach ($dirs as $dir) {
            //排除目录中的当前目录(.)和上一级目录(..)
            if ($dir != '.' && $dir != '..') {
                //如果是目录则递归子目录，继续操作
                $sonDir = $path.'/'.$dir;
                if (is_dir($sonDir)) {
                    //递归删除
                    deleteDir($sonDir);

                    //目录内的子目录和文件删除后删除空目录
                    @rmdir($sonDir);
                } else {

                    //如果是文件直接删除
                    @unlink($sonDir);
                }
            }
        }
        @rmdir($path);
    }
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