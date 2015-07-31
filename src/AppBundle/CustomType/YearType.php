<?php
namespace AppBundle\CustomType;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class YearType extends Type
{
    const YEAR = 'year';

    public function getName()
    {
        return self::YEAR;
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'YEAR';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value.'-1-1';
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value;
    }
}