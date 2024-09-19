<?php


namespace Edram\EasyFeishu;

use Edram\EasyFeishu\Kernel\Contracts\AccessToken as AccessTokenInterface;
use Edram\EasyFeishu\Kernel\Exceptions\HttpException;
use GuzzleHttp\Client;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Psr16Cache;

class AccessToken implements AccessTokenInterface
{
    public const ENDPOINT_URL = 'https://open.feishu.cn/open-apis/auth/v3/tenant_access_token/internal';

    protected Client $httpClient;

    protected CacheInterface $cache;

    public function __construct(
        protected string $appId,
        protected string $appSecret,
        ?CacheInterface $cache = null,
        ?Client $httpClient = null
    ) {
        $this->httpClient = $httpClient ?? new Client($this->getGuzzleOptions());
        $this->cache = $cache ?? new Psr16Cache(new FilesystemAdapter(namespace: 'easy-feishu', defaultLifetime: 1500));
    }

    protected function getGuzzleOptions()
    {
        return [];
    }

    public function getToken(): string
    {
        $token = $this->cache->get($this->getKey());

        if ($token && is_string($token)) {
            return $token;
        }

        return $this->refresh();
    }

    public function getKey(): string
    {
        return $this->key ?? $this->key = sprintf('feishu.access_token.%s', $this->appId);
    }

    public function refresh(): string
    {
        $response = $this->httpClient->request('POST', self::ENDPOINT_URL, [
            'json' => [
                'app_id' => $this->appId,
                'app_secret' => $this->appSecret,
            ],
        ]);

        $json = json_decode($response->getBody()->getContents(), true);

        $token = $json['tenant_access_token'];
        if (empty($token)) {
            throw new HttpException('Failed to get access_token: ' . json_encode($json, JSON_UNESCAPED_UNICODE), $response);
        }

        $this->cache->set($this->getKey(), $token, intval($json['expire']));

        return $token;
    }
}
