<?php

namespace App\Contracts;

interface PmsAdapterInterface
{
    /**
     * Authenticate with the PMS system and get access token
     */
    public function authenticate(): bool;

    /**
     * Import hotels from the PMS system
     */
    public function importHotels(): array;

    /**
     * Import room types for a specific hotel
     */
    public function importRoomTypes(string $hotelId): array;

    /**
     * Import rooms for a specific hotel
     */
    public function importRooms(string $hotelId): array;

    /**
     * Get the PMS system identifier
     */
    public function getPmsSystemSlug(): string;

    /**
     * Test the connection to the PMS system
     */
    public function testConnection(): bool;
}