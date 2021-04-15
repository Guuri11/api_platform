<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\CheeseListingRepository;
use Carbon\Carbon;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

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
 *  shortName="cheeses"
 * )
 * @ORM\Entity(repositoryClass=CheeseListingRepository::class)
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

    public function __construct()
    {
        $this->create_at = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    /**
     * The description of a cheese
     * 
     * @Groups({"cheese_listing:write"})
     */
    public function setTextDescription(string $description): self
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
