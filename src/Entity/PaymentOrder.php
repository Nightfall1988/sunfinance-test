<?php

namespace App\Entity;

use App\Repository\PaymentOrderRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PaymentOrderRepository::class)
 */
class PaymentOrder
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reference;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $payer_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $payer_surname;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getPayerName(): ?string
    {
        return $this->payer_name;
    }

    public function setPayerName(string $payer_name): self
    {
        $this->payer_name = $payer_name;

        return $this;
    }

    public function getPayerSurname(): ?string
    {
        return $this->payer_surname;
    }

    public function setPayerSurname(string $payer_surname): self
    {
        $this->payer_surname = $payer_surname;

        return $this;
    }
}
