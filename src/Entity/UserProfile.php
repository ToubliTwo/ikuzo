<?php

namespace App\Entity;

use App\Repository\UserProfileRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;




#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: UserProfileRepository::class)]
class UserProfile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photoFilename = null;
    #[Vich\UploadableField(mapping: 'user_profile_photos',fileNameProperty: 'photoFilename',size: 'photoSize')]
    private ?File $photoFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?int $photoSize = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPhotoFilename(): ?string
    {
        return $this->photoFilename;
    }

    public function setPhotoFilename(?string $photoFilename): void
    {
        $this->photoFilename = $photoFilename;
    }

    public function getPhotoFile(): ?File
    {
        return $this->photoFile;
    }
    public function setPhotoSize(?int $photoSize): void
    {
        $this->photoSize = $photoSize;
    }
    public function getPhotoSize(): ?int
    {
        return $this->photoSize;
    }
    public function setPhotoFile(?File $photoFile = null): void
    {
        $this->photoFile = $photoFile;

        if (null!== $photoFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

}
