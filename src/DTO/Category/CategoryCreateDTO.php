<?php

declare(strict_types=1);

namespace App\DTO\Category;

use App\Validator\Constraint\UniqueField;
use Symfony\Component\Validator\Constraints as Assert;

class CategoryCreateDTO
{
    /**
     * @Assert\Type("string")
     * @Assert\NotBlank()
     * @Assert\Length(max=100)
     * @UniqueField(entityClass="App\Entity\Category", fieldName="name")
     */
    private ?string $name = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }
}
