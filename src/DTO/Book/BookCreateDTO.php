<?php

declare(strict_types=1);

namespace App\DTO\Book;

use App\Validator\Constraint\UniqueField;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

class BookCreateDTO
{
    /**
     * @Assert\Type("string")
     * @Assert\NotBlank()
     * @Assert\Length(max=150)
     * @UniqueField(entityClass="App\Entity\Book", fieldName="name")
     */
    private ?string $name = null;

    private Collection $categories;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function setCategories(Collection $categories): void
    {
        $this->categories = $categories;
    }
}
