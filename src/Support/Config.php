<?php

namespace Edram\EasyFeishu\Support;

class Config implements \ArrayAccess
{
    /**
     * @var array
     */
    protected $config;

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    public function get($key, $default = null): mixed
    {
        $config = $this->config;

        if (isset($config[$key])) {
            return $config[$key];
        }

        if (false === strpos($key, '.')) {
            return $default;
        }

        foreach (explode('.', $key) as $segment) {
            if (!is_array($config) || !array_key_exists($segment, $config)) {
                return $default;
            }
            $config = $config[$segment];
        }

        return $config;
    }

    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->config);
    }

    public function offsetGet($offset): mixed
    {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value): void
    {
        if (isset($this->config[$offset])) {
            $this->config[$offset] = $value;
        }
    }

    public function offsetUnset($offset): void
    {
        if (isset($this->config[$offset])) {
            unset($this->config[$offset]);
        }
    }
}
