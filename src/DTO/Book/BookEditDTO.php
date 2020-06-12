<?php

declare(strict_types=1);

namespace App\DTO\Book;

use App\Entity\Book;
use Doctrine\Common\Collections\Collection;

class BookEditDTO
{
    private Collection $categories;

    public static function createFromEntity(Book $book): self
    {
        $dto = new self();

        $dto->setCategories($book->getCategories());

        return $dto;
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
