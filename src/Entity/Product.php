<?php

declare(strict_types=1);


namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Delete;
use App\Enum\Currency;
use App\Enum\ProductStatus;
use App\Repository\ProductRepository;
use ApiPlatform\Metadata\ApiResource;
use App\State\ProductRemoveProcessor;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Attribute\Groups;
use App\Validator as AppAssert;
/**
 *
 */
#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(),
        new Patch(),
        new Delete(processor: ProductRemoveProcessor::class),
    ],
    normalizationContext: ['groups' => ['product:read']],
    paginationClientItemsPerPage: true,
    paginationItemsPerPage: 2, //W celu testowym
    paginationMaximumItemsPerPage: 100,
)]
#[ApiFilter(SearchFilter::class, properties: ['status' => 'exact'])]
#[Groups(['product:read'])]
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
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[AppAssert\UniqueActiveSku]
    private ?string $sku = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Assert\NotBlank]
    #[Assert\GreaterThan(0, message: 'Cena musi być większa od zera.')]
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

    #[ORM\OneToMany(targetEntity: PriceHistory::class, mappedBy: 'product', fetch: 'EAGER', orphanRemoval: true)]
    private Collection $priceHistories;

    #[ORM\Column(type: 'integer')]
    #[ORM\Version]
    #[Groups(['product:read', 'product:write'])]
    private ?int $version = null;

    public function __construct()
    {
        $this->priceHistories = new ArrayCollection();
    }

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

    public function getPriceHistories(): Collection
    {
        return $this->priceHistories;
    }

    public function setVersion(int $version): void
    {
        $this->version = $version;
    }

    public function getVersion(): ?int
    {
        return $this->version;
    }
}
