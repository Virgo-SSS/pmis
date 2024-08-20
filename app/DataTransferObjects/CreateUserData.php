<?php

namespace App\DataTransferObjects;

use Carbon\Carbon;

class CreateUserData
{
    public function __construct(
        public readonly string $name,
        public readonly string $username,
        public readonly string $email,
        public readonly ?string $password,
        public readonly int $department_id,
        public readonly array $roles,
        public readonly ?string $profile_picture,
        public readonly ?string $phone,
        public readonly ?string $emergency_contact,
        public readonly ?string $address,
        public readonly ?Carbon $joined_at,
    ){}

    /**
     * Create a new instance from the given array.
     *
     * @param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            username: $data['username'],
            email: $data['email'],
            password: $data['password'] ?? null,
            department_id: $data['department_id'],
            roles: $data['roles'],
            profile_picture: $data['profile_picture'] ?? null,
            phone: $data['phone'] ?? null,
            emergency_contact: $data['emergency_contact'] ?? null,
            address: $data['address'] ?? null,
            joined_at: $data['joined_at'] ? Carbon::parse($data['joined_at']) : null,
        );
    }
}
