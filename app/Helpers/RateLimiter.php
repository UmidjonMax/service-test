<?php

declare(strict_types=1);

class RateLimiter
{
    private string $dir;

    public function __construct()
    {
        $this->dir = __DIR__ . '/../../storage/ratelimit';

        if (!is_dir($this->dir)) {
            mkdir($this->dir, 0700, true);
        }
    }

    public function tooManyAttempts(string $key, int $maxAttempts): bool
    {
        return $this->read($key)['attempts'] >= $maxAttempts;
    }

    public function hit(string $key, int $lockSeconds): void
    {
        $data = $this->read($key);

        if ($data['attempts'] === 0) {
            $data['expires_at'] = time() + $lockSeconds;
        }

        $data['attempts']++;

        file_put_contents($this->path($key), json_encode($data), LOCK_EX);
    }

    public function availableIn(string $key): int
    {
        return max(0, $this->read($key)['expires_at'] - time());
    }

    public function clear(string $key): void
    {
        $file = $this->path($key);

        if (is_file($file)) {
            unlink($file);
        }
    }

    private function read(string $key): array
    {
        $file = $this->path($key);
        $data = is_file($file) ? json_decode((string) file_get_contents($file), true) : null;

        if (!is_array($data) || ($data['expires_at'] ?? 0) <= time()) {
            return ['attempts' => 0, 'expires_at' => 0];
        }

        return $data;
    }

    private function path(string $key): string
    {
        return $this->dir . '/' . hash('sha256', $key) . '.json';
    }
}