<?php

/**
 * 事件驱动（观察者模式）
 * 有一篇博客写的不错：http://www.cnitblog.com/CoffeeCat/archive/2009/04/23/56628.html
 * @package default
 * @author  ccyours
 * @date:   2016/5/20
 * @time:   11:09
 */
class Event {

    private $handler = array();//所有事件处理函数列表，事件名为key,处理函数和参数等是value

    /**
     * @param $eventName String  事件名称
     * @param $func      String  处理函数
     * @param null $scopeString 作用域，如果是String，则代表类名，则方法为静态方法
     */
    public function addEventListener($eventName, $func, $scope = null) {
        $item = $this->_getEventListener($eventName, $func, $scope);
        if (!$item) {
            $item = array();
            $item['handler'] = $func;
            $item['scope'] = $scope;
            $this->handler[$eventName][] = $item;
        } else {
            echo '已经注册过了';
        }
        return true;
    }

    /**
     * @param $eventName    String  事件名称
     * @return bool
     */
    public function removeEventListener($eventName, $func, $scope = null) {
        $item = $this->_getEventListener($eventName, $func, $scope);
        if ($item) {
            unset($this->handler[$eventName]);
        }
        return true;
    }

    /**
     * @param $eventName String 事件名
     * @param $func     String 回调名称
     * @param null      $scope String 作用域
     * @param bool      $remove boolean是否删除
     * @return null
     */
    private function _getEventListener($eventName, $func, $scope = null, $remove = false) {
        $item = null;
        if (!empty($this->handler) && isset($this->handler[$eventName])) {
            foreach ($this->handler[$eventName] as $k => $v) {
                if ($func == $v['handler'] && $scope == $v['scope']) {
                    $item = $v;
                    if ($remove) {
                        unset($this->handler[$eventName][$k]);
                        break;
                    }
                }
            }
        }
        return $item;
    }

    /**
     * @param $eventName String 事件名
     * @param null $param mixed 参数
     */
    public function exec($eventName,$param = null){
        $handler = $this -> handler[$eventName];
        if(is_array($this -> handler[$eventName])){
            foreach($handler as $k => $v ){
                if($v['scope'] === null){
                    call_user_func_array($v['handler'],array($param));
                }elseif(is_string($v['scope'])){
                    call_user_func_array($v['scope'].'::'.$v['handler'],array($param));
                }else{
                    call_user_func_array(array($v['scope'],$v['handler']),array($param));
                }
            }
        }else{
            echo '没有事件绑定';
        }
        return true;
    }
}
//例子
function fnCallBack( $msg1 = 'default msg1' , $msg2 = 'default msg2' )
{
    echo 'show msg1:'.$msg1;
    echo "<br />\n";
    echo 'show msg2:'.$msg2;
    echo "<br />\n";
    echo "<br />\n";
}
$evt = new Event();
$evt->addEventListener( 'test' , 'fnCallBack' );
$evt->addEventListener( 'test' , 'fnCallBack' );
$evt->exec('test' , array('my first message') );
