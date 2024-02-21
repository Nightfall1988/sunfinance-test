<?php

namespace App\Entity;

use App\Repository\PaymentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PaymentRepository::class)
 */
class Payment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $payment_reference;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $payer_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $payer_surname;

    /**
     * @ORM\Column(type="float")
     */
    public $amount;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $description;

    /**
     * @ORM\Column(type="datetime")
     */
    public $payment_date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $national_security_number;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPaymentReference(): ?string
    {
        return $this->payment_reference;
    }

    public function setPaymentReference(string $paymentReference): self
    {
        $this->payment_reference = $paymentReference;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->payment_date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->payment_date = $date;

        return $this;
    }

    /**
     * Get the value of nationalSecurityNumber
     */ 
    public function getNationalSecurityNumber()
    {
        return $this->national_security_number;
    }

    /**
     * Set the value of nationalSecurityNumber
     *
     * @return  self
     */ 
    public function setNationalSecurityNumber($nationalSecurityNumber)
    {
        $this->national_security_number = $nationalSecurityNumber;

        return $this;
    }

    /**
     * Get the value of payer_surname
     */ 
    public function getPayerSurname()
    {
        return $this->payer_surname;
    }

    /**
     * Set the value of payer_surname
     *
     * @return  self
     */ 
    public function setPayerSurname($payer_surname)
    {
        $this->payer_surname = $payer_surname;

        return $this;
    }

    /**
     * Get the value of payer_surname
     */ 
    public function getPayerName()
    {
        return $this->payer_name;
    }

    /**
     * Set the value of payer_surname
     *
     * @return  self
     */ 
    public function setPayerName($payer_name)
    {
        $this->payer_name = $payer_name;

        return $this;
    }
}
