<?php 

namespace App\DataTransferObjects;

class CreateRoleData
{
    public function __construct(
        public readonly string $name,
        public readonly array $permissions
    ) {}
    
    /**
     * Create a new instance from an array.
     * 
     * @param array{name: string, permissions: array} $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            permissions: $data['permissions']
        );
    }
}