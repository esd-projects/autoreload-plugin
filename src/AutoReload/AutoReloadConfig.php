<?php
/**
 * Created by PhpStorm.
 * User: 白猫
 * Date: 2019/5/6
 * Time: 16:58
 */

namespace GoSwoole\Plugins\AutoReload;


use GoSwoole\BaseServer\Plugins\Config\BaseConfig;

class AutoReloadConfig extends BaseConfig
{
    const key = "reload";

    protected $enable = true;

    /**
     * 监控地址
     * @var string|null
     */
    protected $monitorDir;
    public function __construct()
    {
        parent::__construct(self::key);
    }

    /**
     * @return bool
     */
    public function isEnable(): bool
    {
        return $this->enable;
    }

    /**
     * @param bool $enable
     */
    public function setEnable(bool $enable): void
    {
        $this->enable = $enable;
    }

    /**
     * @return string|null
     */
    public function getMonitorDir(): ?string
    {
        return $this->monitorDir;
    }

    /**
     * @param string|null $monitorDir
     */
    public function setMonitorDir(?string $monitorDir): void
    {
        $this->monitorDir = $monitorDir;
    }
}