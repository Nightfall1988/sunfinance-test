<?php

namespace App\Entity;

use App\Repository\LoanRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LoanRepository::class)
 */
class Loan
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $customerId;

    /**
     * @ORM\Column(type="text")
     */
    private $reference;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="float")
     */
    private $amount_issued;

    /**
     * @ORM\Column(type="float")
     */
    private $amount_to_pay;

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCustomerId(): ?string
    {
        return $this->customerId;
    }

    public function setCustomerId(string $customerId): self
    {
        $this->customerId = $customerId;

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

    public function getStatus(): ?string
    {
        return $this->state;
    }

    public function setStatus(string $state): self
    {
        $this->status = $state;

        return $this;
    }

    public function getAmountIssued(): ?float
    {
        return $this->amount_issued;
    }

    public function setAmountIssued(float $amount_issued): self
    {
        $this->amount_issued = $amount_issued;

        return $this;
    }

    public function getAmountToPay(): ?float
    {
        return $this->amount_to_pay;
    }

    public function setAmountToPay(float $amount_to_pay): self
    {
        $this->amount_to_pay = $amount_to_pay;

        return $this;
    }

    /**
     * Mark the loan as paid.
     */
    public function markAsPaid(): void
    {
        $this->setAmountToPay(0);
        $this->setStatus('PAID');
    }
}
