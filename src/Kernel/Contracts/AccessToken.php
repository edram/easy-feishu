<?php

namespace Edram\EasyFeishu\Kernel\Contracts;

interface AccessToken
{
    public function getToken(): string;
}
