<?php
class Service
{
    public static $errorMsg = "";
    
    /**
     * 获得最后一次的错误信息
     * @return string 错误信息
     */
    public function getLastErrMsg()
    {
        return self::$errorMsg;
    }
    
    /**
     * 获得查询条件
     * @param array $condition 自定义查询条件
     * @return CDbCriteria 查询条件对象
     */
    public function getFindCond($condition = array())
    {
        $criteria = new CDbCriteria();
        
        if(!empty($condition)){
            foreach($condition as $key=>$cond){
                if(is_array($cond)){
                    foreach($cond as $k=>$val){
                        if($k == 'between'){
                            list($s_v, $e_v) = $val;
                            $criteria->addBetweenCondition($key, $s_v, $e_v);
                        }
                        if($k == 'like'){
                            $criteria->compare($key, $val, true);
                        }

                        if($k == 'neq'){
                            $criteria->addCondition($key.'<>'.$val);
                        }
                    }
                }
                else{
                    $criteria->compare($key, $cond);
                }
            }
        }
        
        return $criteria;
    }
}