<?php
/**
 * Created by PhpStorm.
 * User: administrato
 * Date: 2019/4/23
 * Time: 12:17
 */

namespace GoSwoole\Plugins\AutoReload;

use GoSwoole\BaseServer\Server\Context;
use GoSwoole\BaseServer\Server\Exception\ConfigException;
use GoSwoole\BaseServer\Server\Plugin\AbstractPlugin;
use Monolog\Logger;

class AutoReloadPlugin extends AbstractPlugin
{
    const processName = "helper";
    const processGroupName = "HelperGroup";
    /**
     * @var InotifyReload
     */
    protected $inotifyReload;

    /**
     * 获取插件名字
     * @return string
     */
    public function getName(): string
    {
        return "AutoReload";
    }

    /**
     * 在服务启动前
     * @param Context $context
     * @return mixed
     * @throws \GoSwoole\BaseServer\Server\Exception\ConfigException
     */
    public function beforeServerStart(Context $context)
    {
        //有没有设置RootDIR
        $rootDir = $context->getServer()->getServerConfig()->getRootDir();
        if (empty($rootDir)) {
            throw new ConfigException("没有配置rootDir");
        }
        //添加一个helper进程
        $context->getServer()->addProcess(self::processName, HelperReloadProcess::class, self::processGroupName);
        return;
    }

    /**
     * 在进程启动前
     * @param Context $context
     * @return mixed
     */
    public function beforeProcessStart(Context $context)
    {
        if ($context->getServer()->getProcessManager()->getCurrentProcess()->getProcessName() === self::processName) {
            $this->inotifyReload = new InotifyReload($context->getDeepByClassName(Logger::class), $context->getServer());
        }
        $this->ready();
    }

    /**
     * @return InotifyReload
     */
    public function getInotifyReload(): InotifyReload
    {
        return $this->inotifyReload;
    }
}