<?php

class Card
{
    public function getAll(): array
    {
        return [
            [
                'id' => 1,
                'card_number' => '8600123456789012',
                'holder_name' => 'ALI VALIYEV',
                'status' => 'ACTIVE',
                'type' => 'UZCARD',
                'created_at' => '2026-10-20'
            ],
            [
                'id' => 2,
                'card_number' => '9860123456789012',
                'holder_name' => 'VALI HASANOV',
                'status' => 'BLOCKED',
                'type' => 'UZCARD',
                'created_at' => '2026-10-18'
            ],
            [
                'id' => 3,
                'card_number' => '5614681234567890',
                'holder_name' => 'JOHN DOE',
                'status' => 'ACTIVE',
                'type' => 'MASTERCARD',
                'created_at' => '2026-10-15'
            ],
            [
                'id' => 4,
                'card_number' => '8600987654321098',
                'holder_name' => 'SARVAR KARIMOV',
                'status' => 'EXPIRED',
                'type' => 'UZCARD',
                'created_at' => '2026-09-30'
            ]
        ];
    }

    public function search(string $query): array
    {
        $cards = $this->getAll();

        if ($query === '') {
            return $cards;
        }

        $query = strtolower(trim($query));

        return array_values(
            array_filter(
                $cards,
                function (array $card) use ($query): bool {

                    return str_contains(
                        strtolower($card['card_number']),
                        $query
                    )
                    ||
                    str_contains(
                        strtolower($card['holder_name']),
                        $query
                    );
                }
            )
        );
    }

    public function countActiveByPrefix(string $prefix): int
    {
        $cards = $this->getAll();

        return count(
            array_filter(
                $cards,
                function (array $card) use ($prefix): bool {

                    return $card['status'] === 'ACTIVE'
                        && str_starts_with(
                            $card['card_number'],
                            $prefix
                        );
                }
            )
        );
    }
}