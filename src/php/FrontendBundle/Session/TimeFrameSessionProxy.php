<?php

namespace Frontastic\Catwalk\FrontendBundle\Session;

use Symfony\Component\HttpFoundation\Session\Storage\Proxy\SessionHandlerProxy;

class TimeFrameSessionProxy extends SessionHandlerProxy
{
    /**
     * @var int
     */
    private $ttl;

    /**
     * @var int
     */
    private $lastUpdated = 0;

    /**
     * @var string
     */
    private $sfAttributes;

    public function __construct(\SessionHandlerInterface $handler, int $ttl)
    {
        parent::__construct($handler);

        $this->ttl = $ttl;
    }

    public function read($sessionId)
    {
        $data = parent::read($sessionId);

        // Session data does not contain the meta part, we can not explode by
        // this and so also not retrieve the metaData entry
        if (!str_contains($data, '_sf2_meta|')) {
            return $data;
        }

        list($this->sfAttributes, $metaData) = explode("_sf2_meta|", $data);

        if (is_array($metaData = @unserialize($metaData))) {
            $this->lastUpdated = (int) $metaData['u'];
        }

        return $data;
    }

    public function write($sessionId, $data)
    {
        list($sfAttributes, ) = explode("_sf2_meta|", $data);
        if ($this->sfAttributes == $sfAttributes && $this->isTimestampStillValid()) {
            $this->updateTimestamp($sessionId, $data);
            return true;
        }
        return parent::write($sessionId, $data);
    }

    public function updateTimestamp($sessionId, $data)
    {
        if ($this->isTimestampStillValid()) {
            return true;
        }
        return parent::updateTimestamp($sessionId, $data);
    }

    private function isTimestampStillValid(): bool
    {
        return (time() - $this->lastUpdated < $this->ttl);
    }
}
