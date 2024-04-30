<?php

namespace App\Namer;

use App\Entity\User;
use Symfony\Component\DomCrawler\Image;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\DirectoryNamerInterface;

/**
 * @implements DirectoryNamerInterface<Image>
 */
class ImageDirectoryNamer implements DirectoryNamerInterface
{
    public function directoryName(object|array $object, PropertyMapping $mapping): string
    {
        if (!$object instanceof User) {
            throw new \InvalidArgumentException(sprintf(format: 'Expected an instance of "%s", but got "%s"', values: User::class, method: get_class($object)));
        }

        $directoryName = '';

        if ($object->getImageName() || $object->getImageFile())  {
            $userId = $object->getId();
            $directoryName = 'images/users/'.$userId;
        }

        return $directoryName;
    }
}
