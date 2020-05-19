<?php

namespace App\Entity;

use App\Repository\FlowersRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FlowersRepository::class)
 */
class Flowers
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="text", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $deliver_on;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddress() {
        return $this->address;
    }

    public function setAddress($address) {
        return $this->address = $address;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        return $this->name = $name;
    }

    public function getDeliveron() {
        return $this->deliver_on;
    }

    public function setDeliveron($deliver_on) {
        return $this->deliver_on = $deliver_on;
    }

} 
