<?php

namespace AppBundle\Application;

use Symfony\Component\EventDispatcher\Event;
use \Domain;

/**
 * Class Application Event
 *
 * All Application event Listeners will receive an Event instance.
 *
 * @author Paulo Gomes <pv.gomes89@gmail.com>
 */
class ApplicationEvent extends Event
{
    /**
     * @var array
     */
    private $message;
    /**
     * @var \Domain\Command
     */
    private $command;
    /**
     * @var \Exception
     */
    private $exception;

    /**
     * @return array
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param array $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return Domain\Command
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * @param Domain\Command $command
     */
    public function setCommand($command)
    {
        $this->command = $command;
    }

    /**
     * @return \Exception
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * @param \Exception $exception
     */
    public function setException($exception)
    {
        $this->exception = $exception;
    }

}
