<?php


namespace Edram\EasyFeishu;

use Edram\EasyFeishu\HttpClient\AccessTokenAwareClient;
use Edram\EasyFeishu\Kernel\Contracts\AccessToken as AccessTokenInterface;
use Edram\EasyFeishu\Support\Config;
use Edram\EasyFeishu\Traits\HasHttpRequest;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\HandlerStack;

class Client
{
    use HasHttpRequest;

    protected AccessTokenInterface $accessToken;

    public function __construct(
        protected Config $config,
    ) {
        $this->accessToken = new AccessToken($config->get('app_id'), $config->get('app_secret'));
    }


    public function createHttpClient()
    {
        $config = array_merge([
            'base_uri' => 'https://open.feishu.cn/'
        ], $this->config->get('http') ?? []);

        $handler = HandlerStack::create();

        $config['handler'] = $handler;

        $handler->push(new AccessTokenAwareClient($this->accessToken));

        $httpClient = new HttpClient($config);

        return $httpClient;
    }
}
