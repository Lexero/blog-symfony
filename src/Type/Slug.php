<?php

declare(strict_types=1);

namespace App\Type;

class Slug
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function isEqualTo(Slug $slug): bool
    {
        return $this->value === $slug->getValue();
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
