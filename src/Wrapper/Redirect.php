<?php
declare(strict_types=1);

namespace Guzwrap\Wrapper;

use Closure;

class Redirect
{
    protected array $values = array();

    /**
     * Set maximum redirects
     * @param int $value
     * @return $this
     */
    public function max(int $value): Redirect
    {
        return $this->setValue('max', $value);
    }

    /**
     * Set redirect option
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function setValue(string $name, $value): Redirect
    {
        $this->values[$name] = $value;
        return $this;
    }

    /**
     * Set strict redirects
     * @param bool $bool
     * @return $this
     */
    public function strict(bool $bool = true): Redirect
    {
        return $this->setValue('strict', $bool);
    }

    /**
     * Set whether to use redirect referrer
     * @param bool $shouldUseReferer
     * @return $this
     */
    public function referer(bool $shouldUseReferer = true): Redirect
    {
        return $this->setValue('referer', $shouldUseReferer);
    }

    /**
     * Set redirect protocols
     * @param mixed ...$protocols
     * @return $this
     */
    public function protocols(...$protocols): Redirect
    {
        return $this->setValue('protocols', $protocols);
    }

    /**
     * Listen to redirect event
     * @param Closure $callback
     * @return $this
     */
    public function onRedirect(Closure $callback): Redirect
    {
        return $this->setValue('on_redirect', $callback);
    }

    /**
     * Whether to track redirects
     * @return $this
     */
    public function trackRedirects(): Redirect
    {
        return $this->setValue('track_redirects', true);
    }

    /**
     * Get redirect options
     * @return array
     */
    public function getValues(): array
    {
        if (empty($this->values)) {
            return [];
        }
        return $this->values;
    }
}