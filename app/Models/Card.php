<?php

class Card
{

    private const FILE = __DIR__ . '/../../storage/cards.json';

    // Allowed status changes: current status => statuses it can change into
    private const TRANSITIONS = [
        'ACTIVE'  => ['BLOCKED'],
        'BLOCKED' => ['ACTIVE'],
        'EXPIRED' => [],
    ];

        public function getAll(): array
    {
        $json = file_get_contents(self::FILE);

        if ($json === false) {
            throw new RuntimeException('Cannot read cards storage.');
        }

        return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    }

        public function findById(int $id): ?array
    {
        foreach ($this->getAll() as $card) {
            if ($card['id'] === $id) {
                return $card;
            }
        }

        return null;
    }

    public function canChangeStatus(array $card, string $newStatus): bool
    {
        $allowed = self::TRANSITIONS[$card['status']] ?? [];

        return in_array($newStatus, $allowed, true);
    }

    public function updateStatus(int $id, string $newStatus, string $changedBy): bool
    {
        $handle = fopen(self::FILE, 'c+');

        if ($handle === false) {
            throw new RuntimeException('Cannot open cards storage.');
        }

        try {
            flock($handle, LOCK_EX);

            $cards = json_decode(
                (string) stream_get_contents($handle),
                true,
                512,
                JSON_THROW_ON_ERROR
            );

            $updated = false;

            foreach ($cards as &$card) {
                if ($card['id'] === $id && $this->canChangeStatus($card, $newStatus)) {
                    $card['status'] = $newStatus;
                    $card['status_changed_by'] = $changedBy;
                    $card['status_changed_at'] = date('Y-m-d H:i:s');
                    $updated = true;
                }
            }
            unset($card);

            if ($updated) {
                $json = json_encode($cards, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);

                ftruncate($handle, 0);
                rewind($handle);
                fwrite($handle, $json . PHP_EOL);
                fflush($handle);
            }

            return $updated;
        } finally {
            flock($handle, LOCK_UN);
            fclose($handle);
        }
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