<?php

namespace AppBundle\Application;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class AbstractQueue
 */
abstract class QueueAbstract
{
    /**
     * @param string $producerKey
     * @param Event $event
     * @param array $message
     */
    abstract protected function publish($producerKey, Event $event, array $message = []);

    /**
     * @param string $producerReference
     * @return string
     */
    protected function handleProducerName($producerReference)
    {
        $producerName = 'old_sound_rabbit_mq.'.$producerReference.'_producer';

        return $producerName;
    }

    /**
     * @param array $message
     * @return array
     */
    protected function handleRetryCounter(array $message)
    {
        $retryCounter = 0;

        if (isset($message['retry'])) {
            $retryCounter = $message['retry'] + 1;
        }

        $message['retry'] = $retryCounter;

        return $message;
    }

}
