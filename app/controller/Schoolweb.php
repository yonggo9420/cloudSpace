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
class schoolweb extends BaseController
{
    public function b()
    {
        $xuehao = request()->get('xuehao');
        $name = request()->get('name');
        $web = request()->get('web');
        //是否开通校园网
        $data = ['xuehao' => $xuehao, 'name' => $name, 'web' => $web];
        $res = Db::name('schoolweb')->where(['xuehao' => $xuehao])->select();
        if ($res->isEmpty()) {
            Db::name('schoolweb')->insert($data);
            return "yes";
        } else {
            return "no";
        }
    }
    public function d()
    {
        $xuehao = request()->get('xuehao');
        $web = request()->get('web');
        Db::name('schoolweb')->where('xuehao', $xuehao)->update(['web' => $web]);
    }
   
          public function update()
    {
        $xuehao = request()->get('xuehao');
        $password= request()->get('password');
        Db::name('schoolweb_keyinfo')->where(['xuehao' =>$xuehao])->update(['password' => $password]);
    }

    
    public function e()
    {
        $callback = request()->get('callback');
        $arr = array();
        $cont = "0000000";
        $k = 0;
        $res = Db::name('schoolweb')->where(['web' => '1'])->limit(50)->select();
        if (!$res->isEmpty()) {
            foreach ($res as $r) {
                $cont = $cont . "," . $r['xuehao'];
                $k = $k + 1;
            }
            $var = explode(",", $cont);
            array_shift($var);
            $val = implode(',', $var);
            return $callback . "([" . $val . "])";
        }
        //如果没有数据，返回no
        return $callback . '(["no"])';
    }
    public function c()
    {
       
        $num = request()->get('n');
        $callback = request()->get('callback');
        $arr = array();
        $cont = "0000000";
        $k = 0;
        $res = Db::name('schoolweb')->where(['web' => '5'])->limit(50+$num)->select(); 
            if (!$res->isEmpty()) {
            foreach ($res as $r) {
                $cont = $cont . "," . $r['xuehao'];
                $k = $k + 1;
                
            }
           $cont= explode(",", $cont);
            for($i=0;$i<=$num;$i++)
            {
                array_shift($cont);
            }
            }else
            {
              
        //如果没有数据，返回no
        return $callback . '("no")';   
            }
       return $callback . '(['.implode(',',$cont) .'])'; 
         
        }
    public function Violation()//记录用户违规次数@返回1：成功，返回0：失败
    {
        
        $callback = request()->get('callback');
        $xuehao = request()->get('xuehao');
        $password = request()->get('password');
        $res = Db::name('schoolweb_keyinfo')->where(['password' => $password, 'xuehao' => $xuehao])->select();
        if (!$res->isEmpty()) {
            foreach ($res as $r) {
                Db::name('schoolweb_keyinfo')->where(['password' => $password, 'xuehao' => $xuehao])->update(['ViolationNum' =>                  $r['ViolationNum']+1]);
                return $callback . "([1])";
            }
        } else {
            return $callback . "([0])";
        }
    }
    public function exceltosql()
    {
        set_time_limit(0);
        error_reporting(0);
        $PHPReader = new \PHPExcel_Reader_Excel2007();
        //载入文件
        $PHPExcel = $PHPReader->load('YKJ/2021.xlsx');
        //获取表中的第一个工作表，如果要获取第二个，把0改为1，依次类推
        $currentSheet = $PHPExcel->getSheet(0);
        //获取总列数
        $highestColumn = $currentSheet->getHighestColumn();
        //获取总行数
        $highestRow = $currentSheet->getHighestRow();
        for ($j = 2; $j <= $highestRow; $j++) {
            //从第一行开始读取数据
            $str = "";
            for ($k = 'A'; $k <= $highestColumn; $k++) {
                //从A列读取数据
                $str .= $PHPExcel->getActiveSheet()->getCell("{$k}{$j}")->getValue() . '|*|';
                //读取单元格
            }
            $strs = explode("|*|", $str);
            $data = ['key' => $strs[0], 'type' => $strs[1]];
            Db::name('timecardkey')->insert($data);
        }
    }
    //---------------------------------------------------
    //-----------------------------------------------------
    public function select()
    {
        $task = request()->get('task');
        $callback = request()->get('callback');
        if ($task == 0) {
            //查询 key是否存在
            $key = request()->get('key');
            $res = Db::name('cardkey')->where(['key' => $key])->select();
            if (!$res->isEmpty()) {
                return $callback . "([1])";
            } else {
                return $callback . "([0])";
            }
        }
    }

    public function addxh()
    {
        $xh = request()->get('xh');
        $xuehao = request()->get('xuehao');
       $res = Db::name('schoolweb_keyinfo')->where(['xuehao' => $xuehao])->limit(1)->select()->toArray();
             Db::name('schoolweb_keyinfo')->where(['xuehao' => $xuehao])->update(['xh' =>$xh ,'begintime' => date("Y-m-d  h:i:s") ,'beginnum' =>$res[0]['beginnum']+1 ]);
    }
        public function delxh()
    {
        $xuehao = request()->get('xuehao');
   $res = Db::name('schoolweb_keyinfo')->where(['xuehao' => $xuehao])->limit(1)->select()->toArray();
             Db::name('schoolweb_keyinfo')->where(['xuehao' => $xuehao])->update(['xh' =>0,'overtime' => date("Y-m-d  h:i:s") ,'overnum' =>$res[0]['beginnum']+1 ]);
    }
    
     public function vip()
    {
         
        $key = request()->get('key');
        $xuehao = request()->get('xuehao');
        $password = request()->get('password');
         $callback = request()->get('callback');
         $time=null;
         $timenum=null;
        $res = Db::name('timecardkey')->where(['key' => $key, 'isuse' => 0])->limit(1)->select();
         if(!$res->isEmpty())
         {
            foreach ($res as $r) 
            {
                if($r['type']==1)
                {
                Db::name('schoolweb_keyinfo')->where(['xuehao' => $xuehao, 'password' => $password])->update(['time' =>date("Y-m-d  h:i:s",strtotime("+1 month",strtotime(date("Y-m-d h:i:s")))) ]);
                     $timenum=1;
                }
                if($r['type']==3)
                {
                Db::name('schoolweb_keyinfo')->where(['xuehao' => $xuehao, 'password' => $password])->update(['time' =>date("Y-m-d  h:i:s",strtotime("+3 month",strtotime(date("Y-m-d h:i:s")))) ]);
                    $timenum=3;
                }
                if($r['type']==12)
                {
                Db::name('schoolweb_keyinfo')->where(['xuehao' => $xuehao, 'password' => $password])->update(['time' =>date("Y-m-d  h:i:s",strtotime("+12 month",strtotime(date("Y-m-d h:i:s")))) ]);
                $timenum=12;
                }
                $result = Db::name('schoolweb_keyinfo')->where(['xuehao' => $xuehao, 'password' => $password])->limit(1)->select()->toArray();
                
                
            Db::name('timecardkey')->where(['isuse' => 0, 'key' => $key])->update(['isuse' => 1, 'xuehao' => $xuehao]);
            }
           return $callback . "({" . "\"code\":" . "1"  . "," . "\"time\":" . '"' . $result[0]['time'] .'"' . "," . "\"timenum\":" . $timenum . "})";
         }else
         {
              return $callback . "({" . "\"code\":" . "0" . "})";
         }
       
    }
    
    public function zhuce()
    {
        
        $callback = request()->get('callback');
        $key = request()->get('key');
        $mac = request()->get('mac');
        $xuehao = request()->get('xuehao');
        $password = request()->get('password');
        $date_ = date('Y-m-d H:i:s');
        $data = ['key' => $key, 'xuehao' => $xuehao, 'password' => $password,'vip' =>1,'mac1' => $mac,'date' => $date_,'time' => date("Y-m-d  h:i:s",strtotime("+1 month",strtotime(date("Y-m-d h:i:s"))))];
        $res = Db::name('cardkey')->where(['key' => $key])->select();
        if (!$res->isEmpty()) {
            
              Db::name('schoolweb_keyinfo')->insert($data);
        Db::name('cardkey')->where('key', $key)->update(['isuse' => 1, 'xuehao' => $xuehao]);
          $res = Db::name('schoolweb_keyinfo')->where(['xuehao' => $xuehao, 'password' => $password])->limit(1)->select();
            foreach ($res as $r) {
                $money = $r['money'];
                $time = $r['time'];
                $date = $r['date'];
                $vip = $r['vip'];
                $xuehao = $r['xuehao'];
                $isok = $r['isok'];
                $ViolationNum = $r['ViolationNum'];
             return $callback . "({" . "\"code\":" . "1" . "," . "\"xuehao\":" . $xuehao . "," . "\"ViolationNum\":" . $ViolationNum . "," . "\"time\":" . '"' . $time . '"' . "," . "\"money\":" . $money . "," . "\"isok\":" . $isok . "," . "\"vip\":" . $vip . "})";
           
            }
        } else {
                 return $callback . "({" . "\"code\":" . "0"  . "})";
            }
       
    }
    public function login()//code=1(正常，登录成功)//code=0(失败，没有账号信息)//code=2//(失败,账号被封禁))//code=3//(失败,多处登录)
    {
header('Content-type: text/javascript'); 
        $callback = request()->get('callback');
        $xuehao = request()->get('xuehao');
        $mac = request()->get('mac');
        $password = request()->get('password');
        $res = Db::name('schoolweb_keyinfo')->where(['xuehao' => $xuehao, 'password' => $password])->limit(1)->select();
        if (!$res->isEmpty()) {
            foreach ($res as $r) {
                if($r['mac1']!=$mac&&$r['mac2']!=$mac&&$r['mac3']!=$mac)
                {
                 if($r['mac1']==0||$r['mac2']==0||$r['mac3']==0)//还有空余Mac位置
                {
                 if($r['mac1']==0)
                 {
                  Db::name('schoolweb_keyinfo')->where(['xuehao' => $xuehao, 'password' => $password])->update(['mac1' => $mac]);   
                 }
                elseif($r['mac2']==0)
                {
                 Db::name('schoolweb_keyinfo')->where(['xuehao' => $xuehao, 'password' => $password])->update(['mac2' => $mac]);    
                }
                elseif($r['mac3']==0)
                {
                 Db::name('schoolweb_keyinfo')->where(['xuehao' => $xuehao, 'password' => $password])->update(['mac3' => $mac]);    
                }
                    
                }else
                {
                   return $callback . "({" . "\"code\":" . "3" . "})";
                }
                }
                
                 $money = $r['money'];
                $time = $r['time'];
                $date = $r['date'];
                $vip = $r['vip'];
                $xuehao = $r['xuehao'];
                $isok = $r['isok'];
                $ViolationNum = $r['ViolationNum'];
                //账号状态(1为正常)(0为封号)(2为冻结) 
                    
           if($ViolationNum<5)//违规封号禁登
            {
                 return $callback . "({" . "\"code\":" . "1" . "," . "\"xuehao\":" . $xuehao . "," . "\"ViolationNum\":" . $ViolationNum . "," . "\"time\":" . '"' . $time . '"' . "," . "\"money\":" . $money . "," . "\"isok\":" . $isok . "," . "\"vip\":" . $vip . "})";
               
            }else
            {
                   Db::name('schoolweb_keyinfo')->where(['password' => $password, 'xuehao' => $xuehao])->update(['isok' => 0]);//标识为已封禁
                 return $callback . "({" . "\"code\":" . "2" . "})";
            }
                
               
            
              
            }
           
        } else {
            return $callback . "({" . "\"code\":" . "0" . "})";
        }
    }
}