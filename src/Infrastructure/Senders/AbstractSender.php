<?php


namespace Infrastructure\Senders;

/**
 * Class AbstractSender
 * @package Infrastructure\Senders
 */
abstract class AbstractSender
{
    /**
     * email or phone
     * @var string
     */
    protected $to;

    /**
     * @var string
     */
    protected $message;

    /**
     * @param string $to
     *
     * @return static
     */
    public function setTo(string $to): AbstractSender
    {
        $this->to = $to;
        return $this;
    }

    /**
     * @param string $message
     *
     * @return static
     */
    public function setMessage(string $message): AbstractSender
    {
        $this->message = $message;
        return $this;
    }

    /**
     * sends a message
     *
     * @return mixed
     */
    abstract public function send();
}
