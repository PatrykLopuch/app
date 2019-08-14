<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="monsters")
 * @ORM\Entity(repositoryClass="App\Repository\MonsterRepository")
 */
class Monster
{
    /**
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See http://symfony.com/doc/current/best_practices/configuration.html#constants-vs-configuration-options
     *
     * @constant int NUMBER_OF_ITEMS
     */
    const NUMBER_OF_ITEMS = 12;
    /**
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     *
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="3",
     *     max="64",
     * )
     * 
     */
    private $Name;

    /**
     * @ORM\Column(type="integer")
     */
    private $Health;

    /**
     * @ORM\Column(type="integer")
     */
    private $Experience;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Photo", mappedBy="monster", cascade={"persist", "remove"})
     */
    private $photo;

    /**
     * @return int|null
     *
     * getter for ID
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     *
     * getter for Name
     */
    public function getName(): ?string
    {
        return $this->Name;
    }

    /**
     * @param string $Name
     * @return Monster
     *
     * setter for Name
     */
    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    /**
     * @return int|null
     *
     * getter for Health
     */
    public function getHealth(): ?int
    {
        return $this->Health;
    }

    /**
     * @param int $Health
     * @return Monster
     *
     * setter for Health
     */
    public function setHealth(int $Health): self
    {
        $this->Health = $Health;

        return $this;
    }

    /**
     * @return int|null
     *
     * getter for Experience
     */
    public function getExperience(): ?int
    {
        return $this->Experience;
    }

    /**
     * @param int $Experience
     * @return Monster
     *
     * setter for Experience
     */
    public function setExperience(int $Experience): self
    {
        $this->Experience = $Experience;

        return $this;
    }

    /**
     * @return Photo|null
     */
    public function getPhoto(): ?Photo
    {
        return $this->photo;
    }

    /**
     * @param Photo|null $photo
     *
     *
     * @return Monster
     */
    public function setPhoto(?Photo $photo): self
    {
        $this->photo = $photo;

        // set (or unset) the owning side of the relation if necessary
        $newMonster = $photo === null ? null : $this;
        if ($newMonster !== $photo->getMonster()) {
            $photo->setMonster($newMonster);
        }

        return $this;
    }
}
