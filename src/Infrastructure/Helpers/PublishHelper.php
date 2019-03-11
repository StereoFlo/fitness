<?php

namespace Infrastructure\Helpers;


/**
 * Class PublishHelper
 * @package Infrastructure\Helpers
 */
class PublishHelper
{
    /**
     * @var string|int
     */
    private $to;

    /**
     * @var string|null
     */
    private $message;

    /**
     * @var string|null
     */
    private $code;

    /**
     * @var int
     */
    private $type;

    /**
     * @return PublishHelper
     */
    public static function create(): self
    {
        return new self();
    }

    /**
     * @return int|string
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param int|string $to
     * @return PublishHelper
     */
    public function setTo($to)
    {
        $this->to = $to;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return PublishHelper
     */
    public function setMessage(string $message): PublishHelper
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type
     * @return PublishHelper
     */
    public function setType(int $type): PublishHelper
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return PublishHelper
     */
    public function setCode(string $code): PublishHelper
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->publish();
    }

    /**
     * @return string
     */
    public function publish(): string
    {
        return json_encode([
            'to' => $this->to,
            'message' => $this->message,
            'type' => $this->type,
        ]);
    }
}
