<?php
/**
 * Created by PhpStorm.
 * User: administrato
 * Date: 2019/4/23
 * Time: 12:17
 */

namespace ESD\Plugins\AutoReload;


use ESD\Core\Context\Context;
use ESD\Core\PlugIn\AbstractPlugin;
use ESD\Core\Server\Server;

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
     * @throws \DI\NotFoundException
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
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ESD\Core\Plugins\Config\ConfigException
     * @throws \ReflectionException
     * @throws \ESD\Core\Exception
     */
    public function beforeServerStart(Context $context)
    {
        if ($this->autoReloadConfig->getMonitorDir() == null) {
            $this->autoReloadConfig->setMonitorDir(Server::$instance->getServerConfig()->getSrcDir());
        }
        $this->autoReloadConfig->merge();
        //添加一个helper进程
        Server::$instance->addProcess(self::processName, HelperReloadProcess::class, self::processGroupName);
        return;
    }

    /**
     * 在进程启动前
     * @param Context $context
     * @return mixed
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function beforeProcessStart(Context $context)
    {
        if (Server::$instance->getProcessManager()->getCurrentProcess()->getProcessName() === self::processName) {
            $this->inotifyReload = new InotifyReload($this->autoReloadConfig);
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