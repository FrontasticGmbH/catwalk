<?php

class ExperimentConfigurationNotFound extends Exception
{
    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString()
    {
        return __class__ . ": [{$this->code}]: {$this->message}\n";
    }
}

