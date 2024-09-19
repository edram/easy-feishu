<?php

namespace Edram\EasyFeishu\Traits;

use Edram\EasyFeishu\Client;

trait HasClient
{
    protected ?Client $client = null;

    public function getClient(): Client
    {
        if (! $this->client) {
            $this->client = $this->createClient();
        }

        return $this->client;
    }

    public function setClient(Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    abstract public function createClient(): Client;
}
