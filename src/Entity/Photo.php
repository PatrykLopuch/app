<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Photo.
 *
 * @ORM\Entity(repositoryClass="App\Repository\PhotoRepository")
 * @ORM\Table(
 *     name="photos",
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="UQ_photos_1",
 *              columns={"file"},
 *          ),
 *     },
 * )
 *
 * @UniqueEntity(
 *     fields={"file"}
 * )
 */
class Photo implements \Serializable
{
    /**
     * Primary Key
     *
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * File.
     *
     * @ORM\Column(
     *     type="string",
     *     length=191,
     *     nullable=false,
     *     unique=true,
     * )
     *
     * @Assert\NotBlank
     * @Assert\Image(
     *     maxSize = "1024k",
     *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg", "image/jpeg", "image/pjpeg"},
     * )
     */
    private $file;

    /**
     * Monster.
     *
     * @ORM\OneToOne(
     *     targetEntity="App\Entity\Monster",
     *     inversedBy="photo"
     * )
     * @ORM\JoinColumn(nullable=false)
     */
    private $monster;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $name;

    /**
     * Getter for Id.
     *
     * @return int|null Id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for Created at.
     *
     * @return \DateTimeInterface|null Created at
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }


    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(string $file): self
    {
        $this->file = $file;

        return $this;
    }
    /**
     * Getter for Monster.
     *
     * @return Monster|null Monster entity
     */
    public function getMonster(): ?Monster
    {
        return $this->monster;
    }

    public function setMonster(?Monster $monster): self
    {
        $this->monster = $monster;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @see \Serializable::serialize()
     *
     * @return string Serialized object
     */
    public function serialize(): string
    {
        $file = $this->getFile();

        return serialize(
            [
                $this->id,
                ($file instanceof File) ? $file->getFilename() : $file,
            ]
        );
    }

    /**
     * @see \Serializable::unserialize()
     *
     * @param string $serialized Serialized object
     */
    public function unserialize($serialized): void
    {
        list($this->id) = unserialize($serialized);
    }
}
