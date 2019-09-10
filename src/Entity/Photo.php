<?php

/**
 *  Photo entity
 */
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Exception;
use Serializable;

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
class Photo implements Serializable
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

    /**   tu było chyba name albo coś takiego, może się jeszcze przyda
     * @ORM\Column(type="string", length=60)
     *
     *   @Assert\NotBlank
     *   @Assert\Type("string")
     *   @Assert\Length(
     *     max="50",
     * )
     */




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
     * Getter for File.
     *
     * @return mixed|null File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Setter for File name.
     *
     * @param file $file
     *
     * @return Photo
     */
    public function setFile($file): self
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

    /**
     * Setter for Monster.
     *
     * @param Monster $monster Monster entity
     */
    public function setMonster(?Monster $monster): void
    {
        $this->monster = $monster;

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
     * @see Serializable::unserialize()
     *
     * @param string $serialized Serialized object
     */
    public function unserialize($serialized): void
    {
        list($this->id) = unserialize($serialized);
    }
}
