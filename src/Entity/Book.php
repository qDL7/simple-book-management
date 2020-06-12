<?php

declare(strict_types=1);

namespace App\Entity;

use App\DTO\Book\BookEditDTO;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Book
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", unique=true, length=150)
     */
    private string $name;

    /**
     * @ORM\ManyToMany(targetEntity="Category")
     * @ORM\JoinTable(name="books_categories",
     *      joinColumns={@ORM\JoinColumn(name="book_id", referencedColumnName="id", onDelete="cascade")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id", onDelete="cascade")}
     * )
     */
    private Collection $categories;

    public function __construct(string $name, ?Collection $categories = null)
    {
        $this->name = $name;

        $this->categories = $categories ?? new ArrayCollection();
    }

    public function updateByDTO(BookEditDTO $dto): void
    {
        $newCategories = $dto->getCategories();

        foreach ($this->getCategories() as $category) {
            if (!$newCategories->contains($category)) {
                $this->removeCategory($category);
            }
        }

        foreach ($newCategories as $newCategory) {
            $this->addCategory($newCategory);
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCategories(): Collection
    {
        return $this->categories;
    }

    private function addCategory(Category $category): void
    {
        if ($this->categories->contains($category)) {
            return;
        }

        $this->categories->add($category);
    }

    private function removeCategory(Category $category): void
    {
        if (!$this->categories->contains($category)) {
            return;
        }

        $this->categories->removeElement($category);
    }
}
