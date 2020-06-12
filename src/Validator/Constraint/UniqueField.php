<?php

declare(strict_types=1);

namespace App\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueField extends Constraint
{
    public string $message = 'The value "{{ value }}" is already used.';

    /**
     * @var string
     */
    protected $entityClass;

    /**
     * @var string
     */
    protected $fieldName;

    public function getEntityClass(): string
    {
        return $this->entityClass;
    }

    public function getFieldName(): string
    {
        return $this->fieldName;
    }

    public function getRequiredOptions(): array
    {
        return ['entityClass', 'fieldName'];
    }
}
