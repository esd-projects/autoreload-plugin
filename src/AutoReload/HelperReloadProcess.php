<?php
/**
 * Created by PhpStorm.
 * User: administrato
 * Date: 2019/4/23
 * Time: 13:50
 */

namespace ESD\Plugins\AutoReload;


use ESD\BaseServer\Server\Message\Message;
use ESD\BaseServer\Server\Process;

class HelperReloadProcess extends Process
{

    /**
     * 在onProcessStart之前，用于初始化成员变量
     * @return mixed
     */
    public function init()
    {

    }

    public function onProcessStart()
    {

    }

    public function onProcessStop()
    {

    }

    public function onPipeMessage(Message $message, Process $fromProcess)
    {

    }
}