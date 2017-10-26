<?php
include_once 'Event.php';
/**
 * 以发布新闻为例子来使用事件模型
 * @package default
 * @author  ccyours
 * @date:   2016/5/20
 * @time:   11:54
 */
class NewsEvent extends Event{
    CONST TYPE_SUCCESS = 1;
    CONST TYPE_FAIL = 2;
}

//情景文件
class News
{
    public $title = '';
    private $evt = null;

    public function __construct( )
    {
        $this->evt = new NewsEvent();
    }
    //添加一篇新闻到数据库
    public function add()
    {
        try
        {
            //在这里进行添加新闻的操作
            echo 'adding news into Database';
            echo "<br />\n";
            //添加完成，触发成功事件
            $this->evt->exec(NewsEvent::TYPE_SUCCESS , $this);
        }
        catch( Exception $e )
        {
            //添加失败，触发失败事件
            $this->evt->exec(NewsEvent::TYPE_FAIL , $this);
        }
    }

    //提供了事件注册和卸载的接口
    public function addEventListener( $evtName , $handler , $scope = null )
    {
        $this->evt->addEventListener($evtName , $handler , $scope );
    }
    public function removeEventListener( $evtName , $handler , $scope = null )
    {
        $this->evt->removeEventListener($evtName , $handler , $scope );
    }
}



//测试代码
//定义回调函数
function fnAddSuccess( $news )
{
    echo 'news:'.$news->title.' has been added';
}
function fnAddFailed( $news )
{
    echo 'news:'.$news->title.' is NOT added';
}

$myNews = new News();
$myNews->title = 'my news title';
$myNews->addEventListener(NewsEvent::TYPE_SUCCESS , 'fnAddSuccess');
$myNews->addEventListener(NewsEvent::TYPE_FAIL , 'fnAddFailed');
$myNews->add();