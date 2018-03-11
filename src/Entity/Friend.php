<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FriendRepository")
 */
class Friend
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var integer
     */
    private $id;
    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $firstName;
    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $lastName;
    /**
     * @ORM\Column(type="boolean")
     * @var boolean
     */
    private $isConnected;
    /**
     * @ORM\Column(type="integer")
     * @var integer
     */
    private $lastScore;

    /**
     * @return mixed
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function isConnected(): bool
    {
        return $this->isConnected;
    }

    /**
     * @param mixed $isConnected
     */
    public function setConnected($isConnected): void
    {
        $this->isConnected = $isConnected;
    }

    /**
     * @return mixed
     */
    public function getLastScore(): int
    {
        return $this->lastScore;
    }

    /**
     * @param mixed $lastScore
     */
    public function setLastScore($lastScore): void
    {
        $this->lastScore = $lastScore;
    }
}
