<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api\TasticFieldValue;

use Kore\DataObject\DataObject;

class Error extends DataObject
{
    const ERROR_CODE_PAGE_FOLDER_NOT_FOUND = 'PAGE_FOLDER_NOT_FOUND';

    public string $message;

    public string $errorCode;

    public ?string $developerHint;
}
