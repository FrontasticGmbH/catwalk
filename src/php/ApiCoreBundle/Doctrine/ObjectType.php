<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

/**
 * Maps an arbitrary PHP value to a CLOB column using PHP's native serialization.
 *
 * The type is registered under the name "object" (see config/doctrine.yml), so
 * every mapping that declares type="object" resolves to it: the XML mappings of
 * the ApiCoreBundle and the FrontendBundle as well as the generated custom app
 * entities. The column content is exactly what serialize() produces, which is
 * the same format the DBAL 3 built-in object type used.
 *
 * Only the parts of the Type API that DBAL 3 and DBAL 4 share are used, so the
 * class works unchanged with either major version.
 */
class ObjectType extends Type
{
    const NAME = 'object';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getClobTypeDeclarationSQL($column);
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): mixed
    {
        return serialize($value);
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): mixed
    {
        if ($value === null) {
            return null;
        }

        $value = is_resource($value) ? stream_get_contents($value) : $value;

        set_error_handler(function (int $code, string $message): bool {
            throw new ConversionException(sprintf(
                'Could not convert database value to "%s" as an error was triggered by the unserialization: %s',
                self::NAME,
                $message
            ));
        });

        try {
            return unserialize($value);
        } finally {
            restore_error_handler();
        }
    }

    /**
     * Name the type is registered under. DBAL 3 declares this method abstract,
     * DBAL 4 does not know it.
     */
    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * Makes DBAL 3 write the "(DC2Type:object)" column comment its built-in
     * object type wrote as well, so introspection maps the column back to this
     * type and the schema comparison stays clean. DBAL 4 does not call this
     * method and stores no type comments.
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
