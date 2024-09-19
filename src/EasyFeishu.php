<?php

namespace Edram\EasyFeishu;


use Edram\EasyFeishu\Support\Config;
use Edram\EasyFeishu\Traits\HasClient;

class EasyFeishu
{
    use HasClient;

    /**
     * @var Config
     */
    protected $config;

    /**
     * Constructor.
     */
    public function __construct(array $config)
    {
        $this->config = new Config($config);
    }

    /**
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    public function createClient(): Client
    {
        return new Client($this->config);
    }
}
