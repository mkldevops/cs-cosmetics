<?php

namespace App\Entity;

use App\Entity\Enum\LocationEnum;
use App\Entity\Trait\IdEntityTrait;
use App\Entity\Trait\TimestampableEntityTrait;
use App\Repository\ContractRepository;
use App\Validator\ContraintStartsDate;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ContractRepository::class)]
#[ContraintStartsDate]
class Contract implements AuthorEntityInterface
{
    use IdEntityTrait;
    use TimestampableEntityTrait;

    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    #[ORM\Column(length: 255)]
    private ?string $contractor = null;

    #[Assert\NotBlank]
    #[Assert\Email]
    #[Assert\Length(max: 180)]
    #[ORM\Column(length: 180)]
    private ?string $contractorMail = null;

    #[Assert\NotBlank]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Formation $formation = null;

    #[Assert\NotBlank]
    #[Assert\Positive]
    #[ORM\Column]
    private ?float $amount = null;

    #[Assert\NotBlank]
    #[ORM\Column]
    private ?DateTimeImmutable $startAt = null;

    #[Assert\NotBlank]
    #[ORM\Column]
    private ?DateTimeImmutable $endAt = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 50, options: ['default' => LocationEnum::OnSite->value])]
    private ?LocationEnum $location = LocationEnum::OnSite;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fileContract = null;

    #[Assert\NotBlank]
    #[Assert\Positive]
    #[ORM\Column]
    private ?int $duration = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    public function __toString(): string
    {
        return $this->contractor.' - '.$this->formation?->getName();
    }

    public function getContractor(): ?string
    {
        return $this->contractor;
    }

    public function setContractor(string $contractor): static
    {
        $this->contractor = $contractor;

        return $this;
    }

    public function getFormation(): ?Formation
    {
        return $this->formation;
    }

    public function setFormation(Formation $formation): static
    {
        $this->formation = $formation;
        $this->amount = $formation->getPrice();
        $this->duration = $formation->getDuration();

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getStartAt(): ?DateTimeImmutable
    {
        return $this->startAt;
    }

    public function setStartAt(DateTimeImmutable $startAt): static
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): ?DateTimeImmutable
    {
        return $this->endAt;
    }

    public function setEndAt(DateTimeImmutable $endAt): static
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function days(): int
    {
        if (!$this->startAt instanceof DateTimeImmutable || !$this->endAt instanceof DateTimeImmutable) {
            return 0;
        }

        return (int) $this->startAt->diff($this->endAt)->days;
    }

    public function getLocation(): ?LocationEnum
    {
        return $this->location;
    }

    public function setLocation(LocationEnum $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getFileContract(): ?string
    {
        return $this->fileContract;
    }

    public function setFileContract(string $fileContract): static
    {
        $this->fileContract = $fileContract;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getContractorMail(): ?string
    {
        return $this->contractorMail;
    }

    public function setContractorMail(string $contractorMail): static
    {
        $this->contractorMail = $contractorMail;

        return $this;
    }
}
