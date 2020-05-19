<?php

namespace App\Entity;

use App\Repository\CoffeeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CoffeeRepository::class)
 */
class Coffee
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $milk;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $milk_type;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $cup_size;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $location;

    /**
     * @ORM\Column(type="datetime")
     */
    private $deliver_on;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMilk(): ?string
    {
        return $this->milk;
    }

    public function setMilk(string $milk): self
    {
        $this->milk = $milk;

        return $this;
    }

    public function getMilkType(): ?string
    {
        return $this->milk_type;
    }

    public function setMilkType(string $milk_type): self
    {
        $this->milk_type = $milk_type;

        return $this;
    }

    public function getCupSize(): ?string
    {
        return $this->cup_size;
    }

    public function setCupSize(string $cup_size): self
    {
        $this->cup_size = $cup_size;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getDeliveron() {
        return $this->deliver_on;
    }

    public function setDeliveron($deliver_on) {
        return $this->deliver_on = $deliver_on;
    }
}
