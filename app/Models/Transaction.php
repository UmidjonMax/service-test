
<?php

class Transaction
{
    public function getAll(): array
    {
        return [
            [
                'id' => 1001,
                'card_number' => '8600123456789012',
                'type' => 'PURCHASE',
                'amount' => 150000,
                'currency' => 'UZS',
                'status' => 'SUCCESS',
                'response_code' => '000',
                'created_at' => '2026-10-26 14:25:10'
            ],
            [
                'id' => 1002,
                'card_number' => '9860123456789012',
                'type' => 'WITHDRAWAL',
                'amount' => 500000,
                'currency' => 'UZS',
                'status' => 'FAILED',
                'response_code' => '051',
                'created_at' => '2026-10-26 13:48:22'
            ],
            [
                'id' => 1003,
                'card_number' => '5614681234567890',
                'type' => 'PURCHASE',
                'amount' => 250000,
                'currency' => 'UZS',
                'status' => 'SUCCESS',
                'response_code' => '000',
                'created_at' => '2026-10-26 12:15:45'
            ],
            [
                'id' => 1004,
                'card_number' => '8600987654321098',
                'type' => 'TRANSFER',
                'amount' => 75000,
                'currency' => 'UZS',
                'status' => 'PENDING',
                'response_code' => '-1',
                'created_at' => '2026-10-25 18:30:00'
            ],
            [
                'id' => 1005,
                'card_number' => '8600123456789012',
                'type' => 'PURCHASE',
                'amount' => 320000,
                'currency' => 'UZS',
                'status' => 'FAILED',
                'response_code' => '096',
                'created_at' => '2026-10-25 16:05:12'
            ],
            [
                'id' => 1006,
                'card_number' => '9860123456789012',
                'type' => 'TRANSFER',
                'amount' => 100000,
                'currency' => 'UZS',
                'status' => 'SUCCESS',
                'response_code' => '000',
                'created_at' => '2026-10-24 11:20:00'
            ]
        ];
    }

    public function search(
        string $query = '',
        string $status = '',
        string $dateFrom = '',
        string $dateTo = ''
    ): array {
        $transactions = $this->getAll();

        return array_values(array_filter(
            $transactions,
            function (array $transaction) use (
                $query,
                $status,
                $dateFrom,
                $dateTo
            ): bool {
                $query = strtolower(trim($query));

                $matchesQuery = $query === ''
                    || str_contains(
                        strtolower((string) $transaction['id']),
                        $query
                    )
                    || str_contains(
                        strtolower($transaction['card_number']),
                        $query
                    )
                    || str_contains(
                        strtolower($transaction['type']),
                        $query
                    );

                $matchesStatus = $status === ''
                    || $transaction['status'] === $status;

                $transactionDate = substr(
                    $transaction['created_at'],
                    0,
                    10
                );

                $matchesFrom = $dateFrom === ''
                    || $transactionDate >= $dateFrom;

                $matchesTo = $dateTo === ''
                    || $transactionDate <= $dateTo;

                return $matchesQuery
                    && $matchesStatus
                    && $matchesFrom
                    && $matchesTo;
            }
        ));
    }
    public function countLastMinuteByStatus(string $status): int
{
    $transactions = $this->getAll();

    $now = new DateTimeImmutable();
    $oneMinuteAgo = $now->modify('-1 minute');

    return count(
        array_filter(
            $transactions,
            function (array $transaction) use (
                $status,
                $oneMinuteAgo,
                $now
            ): bool {

                if ($transaction['status'] !== $status) {
                    return false;
                }

                $transactionTime = DateTimeImmutable::createFromFormat(
                    'Y-m-d H:i:s',
                    $transaction['created_at']
                );

                if ($transactionTime === false) {
                    return false;
                }

                return $transactionTime >= $oneMinuteAgo
                    && $transactionTime <= $now;
            }
        )
    );
}
}
