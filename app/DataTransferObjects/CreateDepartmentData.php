<?php 

namespace App\DataTransferObjects;

class CreateDepartmentData
{
    public function __construct(
        public readonly string $name,
    ) {}

    /**
     * Create a new instance from an array.
     *
     * @param array{name: string} $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
        );
    }
}