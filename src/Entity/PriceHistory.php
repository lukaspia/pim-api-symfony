<?php

declare(strict_types=1);


namespace App\Entity;

use App\Repository\PriceHistoryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

/**
 *
 */
#[ORM\Entity(repositoryClass: PriceHistoryRepository::class)]
class PriceHistory
{
    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var \App\Entity\Product|null
     */
    #[ORM\ManyToOne(targetEntity: Product::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Product $product = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Groups(['product:read'])]
    private ?string $oldPrice = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Groups(['product:read'])]
    private ?string $newPrice = null;

    /**
     * @var \DateTimeImmutable
     */
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Groups(['product:read'])]
    private \DateTimeImmutable $changedAt;

    /**
     *
     */
    public function __construct()
    {
        $this->changedAt = new \DateTimeImmutable();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return \App\Entity\Product|null
     */
    public function getProduct(): ?Product
    {
        return $this->product;
    }

    /**
     * @param \App\Entity\Product|null $product
     * @return $this
     */
    public function setProduct(?Product $product): self
    {
        $this->product = $product;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getOldPrice(): ?string
    {
        return $this->oldPrice;
    }

    /**
     * @param string $oldPrice
     * @return $this
     */
    public function setOldPrice(string $oldPrice): self
    {
        $this->oldPrice = $oldPrice;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNewPrice(): ?string
    {
        return $this->newPrice;
    }

    /**
     * @param string $newPrice
     * @return $this
     */
    public function setNewPrice(string $newPrice): self
    {
        $this->newPrice = $newPrice;
        return $this;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getChangedAt(): \DateTimeImmutable
    {
        return $this->changedAt;
    }

    public function setChangedAt(\DateTimeImmutable $changedAt): static
    {
        $this->changedAt = $changedAt;

        return $this;
    }
}
