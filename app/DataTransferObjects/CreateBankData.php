<?php

namespace App\DataTransferObjects;

class CreateBankData
{
    public function __construct(
        public readonly string $name,
    ) {}

    /**
     * Create a new instance from the given array.
     *
     * @param array<string, string> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
        );
    }
}
