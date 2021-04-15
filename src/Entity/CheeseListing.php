<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\CheeseListingRepository;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Carbon\Carbon;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;

// Collections operations: operaciones sobre multiples representaciones de la clase
// Item operations: operaciones sobre una representacion de la clase

// Serialize group: Normalize is JSON to Array(output), denormalize is Array to JSON(input)

/**
 * @ApiResource(
 *  collectionOperations={"get", "post"},
 *  itemOperations={
 *      "get", 
 *      "put"
 *  },
 *  normalizationContext={
 *      "groups"={ "cheese_listing:read", "swagger_definition_name"="Read"}
 *  },
 *  denormalizationContext={
 *      "groups"={ "cheese_listing:write", "swagger_definition_name"="Write" }
 *  },
 *  shortName="cheeses",
 *  attributes={
 *      "pagination_items_per_page"=3
 *  }
 * )
 * @ORM\Entity(repositoryClass=CheeseListingRepository::class)
 * @ApiFilter(BooleanFilter::class, properties={"is_published"})
 * @ApiFilter(SearchFilter::class, properties={"title":"partial", "getDescription": "partial"})
 * @ApiFilter(RangeFilter::class, properties={"price"})
 * @ApiFilter(PropertyFilter::class)
 * 
 * Ejemplo de property filter: /api/cheeses/1.jsonld?properties[]=title&properties[]=description
 * 
 */
class CheeseListing
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"cheese_listing:read", "cheese_listing:write"})
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Groups({"cheese_listing:read", "cheese_listing:write"})
     */
    private $description;

    /**
     * This is the cheese price
     * 
     * @ORM\Column(type="integer")
     * @Groups({"cheese_listing:read", "cheese_listing:write"})
     */
    private $price;

    /**
     * @ORM\Column(type="datetime")
     */
    private $create_at;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_published = false;

    public function __construct(string $title = null)
    {
        $this->create_at = new DateTimeImmutable();
        $this->title = $title;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @Groups({"cheese_listing:read"})
     */
    public function getShortDescription(): ?string
    {
        return strlen($this->description) < 40 ? $this->description: substr($this->description,0, 40).'...' ;
    }

    /**
     * @SerializedName("custom_description")
     */
    public function setDescription(string $description): self
    {
        $this->description = nl2br($description);

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->create_at;
    }

    /**
     * How long ago this cheese was created
     * 
     * @Groups({"cheese_listing:read"})
     */
    public function getCreateAtAgo(): string
    {
        return Carbon::instance($this->getCreateAt())->diffForHumans();
    }

    public function getIsPublished(): ?bool
    {
        return $this->is_published;
    }

    public function setIsPublished(bool $is_published): self
    {
        $this->is_published = $is_published;

        return $this;
    }
}
