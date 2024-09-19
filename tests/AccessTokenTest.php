<?php

namespace Edram\EasyFeishu\Tests\Work;

use Edram\EasyFeishu\Tests\TestCase;
use Edram\EasyFeishu\AccessToken;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

class AccessTokenTest extends TestCase
{
    public function test_get_token_from_http_request()
    {
        $httpClient = \Mockery::mock(Client::class);
        $result = [
            'tenant_access_token' => 'mock_access_token',
            'expire' => 7200,
        ];

        $response = new Response(200, [], json_encode($result));

        $config = [
            'app_id' => 'mock_app_id',
            'app_secret' => 'mock_app_secret',
        ];

        $options = [
            'json' => [
                'app_id' => $config['app_id'],
                'app_secret' => $config['app_secret'],
            ],
        ];
        $httpClient->allows()->request('POST', AccessToken::ENDPOINT_URL, $options)->andReturn($response);



        $accessToken = new AccessToken($config['app_id'], $config['app_secret'], null, $httpClient);

        $this->assertSame($result['tenant_access_token'], $accessToken->getToken());
    }
}
