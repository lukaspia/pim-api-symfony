<?php

declare(strict_types=1);


namespace App\Entity;

use App\Enum\Currency;
use App\Enum\ProductStatus;
use App\Repository\ProductRepository;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 */
#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ApiResource]
#[UniqueEntity(fields: ['sku'], message: 'Produkt o tym SKU już istnieje.')]
class Product
{
    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $name = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank]
    private ?string $sku = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Assert\NotBlank]
    #[Assert\PositiveOrZero]
    private ?string $price = null;

    /**
     * @var \App\Enum\Currency
     */
    #[ORM\Column(length: 3, enumType: Currency::class)]
    private Currency $currency = Currency::PLN;

    /**
     * @var \App\Enum\ProductStatus
     */
    #[ORM\Column(length: 20, enumType: ProductStatus::class)]
    private ProductStatus $status = ProductStatus::ACTIVE;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSku(): ?string
    {
        return $this->sku;
    }

    /**
     * @param string $sku
     * @return $this
     */
    public function setSku(string $sku): self
    {
        $this->sku = $sku;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPrice(): ?string
    {
        return $this->price;
    }

    /**
     * @param string $price
     * @return $this
     */
    public function setPrice(string $price): self
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return \App\Enum\Currency
     */
    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    /**
     * @param \App\Enum\Currency $currency
     * @return $this
     */
    public function setCurrency(Currency $currency): self
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @return \App\Enum\ProductStatus
     */
    public function getStatus(): ProductStatus
    {
        return $this->status;
    }

    /**
     * @param \App\Enum\ProductStatus $status
     * @return $this
     */
    public function setStatus(ProductStatus $status): self
    {
        $this->status = $status;
        return $this;
    }
}
