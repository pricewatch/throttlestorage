<?php

namespace PriceWatch\ThrottleStorage;

use Stiphle\Storage\StorageInterface;

/**
 * Redis storage 
 */
class Redis implements StorageInterface
{
    protected $lockWaitTimeout = 1000;
    protected $redis;

    public function __construct(\Redis $redis)
    {
        $this->redis = $redis;
    }

    /**
     * {@inheritDoc}
     */
    public function setLockWaitTimeout($milliseconds)
    {
        $this->lockWaitTimeout = $milliseconds;
    }

    /**
     * {@inheritDoc}
     */
    public function lock($key)
    {
        $start = microtime(true);

        while (is_null($this->redis->set($this->getLockKey($key), 'LOCKED', ['nx', 'px' => 3600]))) {
            $passed = (microtime(true) - $start) * 1000;
            if ($passed > $this->lockWaitTimeout) {
                throw new LockWaitTimeoutException();
            }
            usleep(100);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function unlock($key)
    {
        $this->redis->del($this->getLockKey($key));
    }

    /**
     * {@inheritDoc}
     */
    public function get($key)
    {
        return $this->redis->get($key);
    }

    /**
     * {@inheritDoc}
     */
    public function set($key, $value)
    {
        $this->redis->set($key, $value);
    }

    private function getLockKey($key)
    {
        return $key . "::LOCK";
    }
}