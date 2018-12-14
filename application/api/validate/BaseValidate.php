<?php
/**
 * Created by PhpStorm.
 * User: CHUNLAI
 * Date: 2018/10/9
 * Time: 8:33
 */

namespace app\api\validate;


use app\api\lib\exception\ParameterException;
use think\Request;
use think\Validate;

class BaseValidate extends Validate
{
    public function goCheck(){
        //获取http传入的参数
        //对传入的参数作参数校验
        $request = Request::instance();
        $param = $request->param();
        $result = $this->batch()->check($param);
        if (!$result){
            $e = new ParameterException([
                'msg' => $this->error
            ]);
            throw $e;
        }
        else{
            return true;
        }
    }
    public function goCheck1($param){
        //对传入的参数作参数校验
        $result = $this->batch()->check($param);
        if (!$result){
            $e = new ParameterException([
                'msg' => $this->error
            ]);
            throw $e;
        }
        else{
            return true;
        }
    }
    protected function isPositiveInteger($value, $rule='', $data='', $field=''){
        if (is_numeric($value) && is_int($value + 0) && ($value + 0) > 0){
            return true;
        }
        else{
            return false;
        }
    }
}