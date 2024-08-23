<?php

namespace App\DataTransferObjects;

use Carbon\Carbon;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Testing\File as TestingFile;

class CreateUserData
{
    public function __construct(
        public readonly string $name,
        public readonly string $username,
        public readonly string $email,
        public readonly ?string $password,
        public readonly int $department_id,
        public readonly File|TestingFile|UploadedFile|null $profile_picture,
        public readonly ?string $phone,
        public readonly ?string $emergency_contact,
        public readonly ?string $address,
        public readonly Carbon $joined_at,
        public readonly ?string $gender,

        public readonly ?int $bank_id,
        public readonly ?string $bank_account_number,
        public readonly ?string $bank_account_name,

        public readonly array $roles,
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
            profile_picture: $data['profile_picture'] ?? null,
            phone: $data['phone'] ?? null,
            emergency_contact: $data['emergency_contact'] ?? null,
            address: $data['address'] ?? null,
            joined_at: Carbon::parse($data['joined_at']),
            gender: $data['gender'] ?? null,
            bank_id: $data['bank_id'] ?? null,
            bank_account_number: $data['account_number'] ?? null,
            bank_account_name: $data['account_name'] ?? null,
            roles: $data['roles'],
        );
    }
}
