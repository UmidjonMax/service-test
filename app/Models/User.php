
<?php

class User
{
    private string $file;

    public function __construct()
    {
        $this->file = __DIR__
            . '/../../storage/users.json';
    }

    public function findByUsername(
        string $username
    ): ?array {

        if (!is_file($this->file)) {
            throw new RuntimeException(
                'User storage file not found.'
            );
        }

        $json = file_get_contents($this->file);

        if ($json === false) {
            throw new RuntimeException(
                'Unable to read user storage.'
            );
        }

        $users = json_decode(
            $json,
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        foreach ($users as $user) {
            if (
                isset($user['username'])
                && strcasecmp(
                    $user['username'],
                    trim($username)
                ) === 0
            ) {
                return $user;
            }
        }

        return null;
    }
}
