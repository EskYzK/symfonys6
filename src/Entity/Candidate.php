<?php

namespace App\Entity;

use App\Enum\Status;
use App\Repository\CandidateRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CandidateRepository::class)]
class Candidate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(groups: ['step1'])]
    private ?string $firstName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(groups: ['step1'])]
    private ?string $lastName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(groups: ['step1'])]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $phone = null;

    #[ORM\Column]
    #[Assert\NotBlank(groups: ['step1'])]
    private ?bool $hasExperience = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(groups: ['step2'])]
    private ?string $experienceDetails = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $availabilityDate = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(nullable: true, enumType: Status::class)]
    private ?Status $status = null;

    public ?string $currentStep = null;

    public ?bool $availableImmediately = null;

    public ?bool $consentRGPD = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function hasExperience(): ?bool
    {
        return $this->hasExperience;
    }

    public function setHasExperience(bool $hasExperience): static
    {
        $this->hasExperience = $hasExperience;

        return $this;
    }

    public function getExperienceDetails(): ?string
    {
        return $this->experienceDetails;
    }

    public function setExperienceDetails(?string $experienceDetails): static
    {
        $this->experienceDetails = $experienceDetails;

        return $this;
    }

    public function getAvailabilityDate(): ?\DateTime
    {
        return $this->availabilityDate;
    }

    public function setAvailabilityDate(?\DateTime $availabilityDate): static
    {
        $this->availabilityDate = $availabilityDate;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getAvailableImmediately(): ?bool
    {
        return $this->availableImmediately;
    }

    public function setAvailableImmediately(?bool $availableImmediately): static
    {
        $this->availableImmediately = $availableImmediately;

        return $this;
    }

    public function getConsentRGPD(): ?bool
    {
        return $this->consentRGPD;
    }

    public function setConsentRGPD(?bool $consentRGPD): static
    {
        $this->consentRGPD = $consentRGPD;

        return $this;
    }
}
