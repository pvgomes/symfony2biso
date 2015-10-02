<?php

namespace AppBundle\Infrastructure\Seller;

use Predis;

/**
 * ClientInterface.
 *
 * @author Mario Rezende
 */
interface ClientInterface
{
    /**
     * @param Predis\Client $client
     */
    public function __construct(Predis\Client $client);

    /**
     * @param string $key
     *
     * @return bool
     */
    public function exists($key);

    /**
     * @param string $key
     *
     * @return array
     */
    public function get($key);

    /**
     * Add the specified members to the set stored at key
     * @param string $key
     * @param array $members
     * @return mixed
     */
    public function sadd($key, array $members);

    /**
     * Returns if member is a member of the set stored at key.
     * @param $key
     * @param $member
     * @return boolean
     */
    public function sismember($key, $member);
}
