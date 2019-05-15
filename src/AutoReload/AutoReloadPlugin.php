<?php
/**
 * Created by PhpStorm.
 * User: administrato
 * Date: 2019/4/23
 * Time: 12:17
 */

namespace ESD\Plugins\AutoReload;

use ESD\BaseServer\Plugins\Logger\Logger;
use ESD\BaseServer\Server\Context;
use ESD\BaseServer\Server\Exception\ConfigException;
use ESD\BaseServer\Server\Plugin\AbstractPlugin;

class AutoReloadPlugin extends AbstractPlugin
{
    const processName = "helper";
    const processGroupName = "HelperGroup";
    /**
     * @var InotifyReload
     */
    protected $inotifyReload;
    /**
     * @var AutoReloadConfig
     */
    private $autoReloadConfig;

    /**
     * AutoReloadPlugin constructor.
     * @param AutoReloadConfig|null $autoReloadConfig
     * @throws \DI\DependencyException
     * @throws \ReflectionException
     */
    public function __construct(?AutoReloadConfig $autoReloadConfig = null)
    {
        parent::__construct();
        if ($autoReloadConfig == null) {
            $autoReloadConfig = new AutoReloadConfig();
        }
        $this->autoReloadConfig = $autoReloadConfig;
    }

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
     * @throws ConfigException
     * @throws \ESD\BaseServer\Exception
     * @throws \ReflectionException
     */
    public function beforeServerStart(Context $context)
    {
        if ($this->autoReloadConfig->getMonitorDir() == null) {
            $this->autoReloadConfig->setMonitorDir($context->getServer()->getServerConfig()->getSrcDir());
        }
        $this->autoReloadConfig->merge();
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
            $this->inotifyReload = new InotifyReload($context->getDeepByClassName(Logger::class), $context->getServer(), $this->autoReloadConfig);
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