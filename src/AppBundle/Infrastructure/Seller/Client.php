<?php

namespace AppBundle\Infrastructure\Seller;

use Predis;

/**
 * Client.
 *
 * @author Mario Rezende
 */
class Client implements ClientInterface
{
    private $client;

    /**
     * {@inheritdoc}
     */
    public function __construct(Predis\Client $client)
    {
        $this->client = $client;
    }

    function __destruct()
    {
        $this->client->disconnect();
    }

    /**
     * {@inheritdoc}
     */
    public function exists($key)
    {
        $this->handleConnection();
        return $this->client->exists($key);
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        $this->handleConnection();
        return $this->client->get($key);
    }

    /**
     * {@inheritdoc}
     */
    public function sadd($key, array $members)
    {
        $this->handleConnection();
        return $this->client->sadd($key, $members);
    }

    /**
     * {@inheritdoc}
     */
    public function sismember($key, $member)
    {
        $this->handleConnection();
        return $this->client->sismember($key, $member);
    }

    private function handleConnection()
    {
        if (!$this->client->isConnected()) {
            $this->client->connect();
        }
    }

}
