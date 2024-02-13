<?php

declare(strict_types=1);

namespace Shift\System\Library;

use Phpfastcache\Helper\Psr16Adapter;
use Phpfastcache\Config\ConfigurationOptionInterface;
use Phpfastcache\Config\ConfigurationOption;
use Phpfastcache\Drivers as CacheDrivers;
use Phpfastcache\Core\Pool\ExtendedCacheItemPoolInterface;
use Phpfastcache\Exceptions\PhpfastcacheInvalidArgumentException;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;

/**
 * PHPFastCache PSR-16 adapter wrapper
 *
 * Psr16Adapter public methods:
 * - get(string $key, mixed $default = null): mixed
 * - public function set(string $key, mixed $value, null|int|\DateInterval $ttl = null): bool
 * - delete(string $key): bool
 * - clear(): bool
 * - getMultiple(iterable $keys, mixed $default = null): iterable
 * - setMultiple(iterable $values, null|int|\DateInterval $ttl = null): bool
 * - deleteMultiple(iterable $keys): bool
 * - has(string $key): bool
 *
 * @link https://github.com/PHPSocialNetwork/phpfastcache/blob/master/lib/Phpfastcache/Helper/Psr16Adapter.php
 */
class Cache extends Psr16Adapter {
    public function __construct(string|ExtendedCacheItemPoolInterface $driver, array|ConfigurationOptionInterface $config = null)
    {
        $this->setup($driver, $config);
    }

    /**
     * Setup helper
     */
    public function setup(string|ExtendedCacheItemPoolInterface $driver, array|ConfigurationOptionInterface $config = null)
    {
        if (\is_array($config)) {
            switch ($driver) {
                case 'Files':
                    $config = new CacheDrivers\Files\Config($config);
                    break;

                case 'Redis':
                    $config = new CacheDrivers\Redis\Config($config);
                    break;

                case 'Memcached':
                    $config = new CacheDrivers\Memcached\Config($config);
                    break;

                default:
                    $config = new ConfigurationOption($config);
                    break;
            }
        }

        parent::__construct($driver, $config);
    }

    /**
     * Instance to access all API
     *
     * @return \Phpfastcache\Core\Pool\ExtendedCacheItemPoolInterface
     */
    public function instance()
    {
        return $this->internalCacheInstance;
    }

    /**
     * Set cache with "tags" parameter
     *
     * @param string $key
     * @param mixed $value
     * @param null|int|\DateInterval $ttl
     * @param array $tags
     * @return bool
     * @throws PhpfastcacheSimpleCacheException
     */
    public function set(string $key, mixed $value, null|int|\DateInterval $ttl = null, array $tags = []): bool
    {
        try {
            $cacheItem = $this->internalCacheInstance
                ->getItem($key)
                ->set($value);

            if ($tags) {
                $cacheItem->addTags($tags);
            }

            if (\is_int($ttl) && $ttl <= 0) {
                $cacheItem->expiresAt((new \DateTime('@0')));
            } elseif ($ttl !== null) {
                $cacheItem->expiresAfter($ttl);
            }

            return $this->internalCacheInstance->save($cacheItem);
        } catch (PhpfastcacheInvalidArgumentException $e) {
            throw new PhpfastcacheSimpleCacheException($e->getMessage(), 0, $e);
        }
    }

    /**
     * Delete cache item by tags
     *
     * @param  array|string $tagName
     * @return bool
     */
    public function deleteByTags(array|string $tagName): bool
    {
        return $this->internalCacheInstance->deleteItemsByTags((array) $tagName);
    }

    public function getHash(string|int|float|bool|null|array $args): string
    {
        if (is_array($args)) {
            $args = json_encode($args);
        }

        return md5((string) $args);
    }
}
