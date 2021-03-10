<?php

declare(strict_types=1);

namespace App\Model\Market\Entity\Author;

use Webmozart\Assert\Assert;

class Email
{
    private ?string $value = null;

    public function __construct(?string $value = null)
    {
        Assert::notEmpty($value);
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Incorrect email.');
        }
        $this->value = mb_strtolower($value);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function isEqual(self $other): bool
    {
        return $this->getValue() === $other->getValue();
    }
}
