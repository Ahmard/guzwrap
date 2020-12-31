<?php

namespace Guzwrap\Core;

class Redirect
{
    protected array $options = array();

    /**
     * Set maximum redirects
     * @param int $value
     * @return $this
     */
    public function max(int $value): Redirect
    {
        return $this->setOption('max', $value);
    }

    /**
     * Set redirect option
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function setOption(string $name, $value): Redirect
    {
        $this->options[$name] = $value;
        return $this;
    }

    /**
     * Set strict redirects
     * @param bool $bool
     * @return $this
     */
    public function strict(bool $bool = true): Redirect
    {
        return $this->setOption('strict', $bool);
    }

    /**
     * Set whether to use redirect referrer
     * @param bool $ref
     * @return $this
     */
    public function referer(bool $ref = true): Redirect
    {
        return $this->setOption('referer', $ref);
    }

    /**
     * Set redirect protocols
     * @param mixed ...$protocols
     * @return $this
     */
    public function protocols(...$protocols): Redirect
    {
        return $this->setOption('protocols', $protocols);
    }

    /**
     * Listen to redirect event
     * @param callable $callback
     * @return $this
     */
    public function onRedirect(callable $callback): Redirect
    {
        return $this->setOption('on_redirect', $callback);
    }

    /**
     * Whether to track redirects
     * @return $this
     */
    public function trackRedirect(): Redirect
    {
        return $this->setOption('track_redirects', true);
    }

    /**
     * Get redirect options
     * @return array
     */
    public function getOptions(): array
    {
        if (empty($this->options)) {
            return [];
        }
        return $this->options;
    }
}