<?php

declare(strict_types=1);

namespace Edram\EasyFeishu\Kernel\Contracts;

interface AccessToken
{
    public function getToken(): string;

    /**
     * @return array<string,string>
     */
    public function toQuery(): array;
}
