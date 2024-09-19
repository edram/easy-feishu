<?php

namespace Edram\EasyFeishu\HttpClient;;

use Edram\EasyFeishu\Kernel\Contracts\AccessToken as AccessTokenInterface;
use Psr\Http\Message\RequestInterface;

class AccessTokenAwareClient
{

    public function __construct(protected AccessTokenInterface $accessToken) {}

    public function __invoke(callable $handler): callable
    {
        return function (RequestInterface $request, array $options) use ($handler) {
            $request = $request->withHeader(
                'Authorization',
                'Bearer ' . $this->accessToken->getToken()
            );

            return $handler($request, $options);
        };
    }
}
