<?php

namespace App\Models;

use CodeIgniter\Model;

class MemberModel extends Model
{
    protected $table = 'tblmember';
    protected $primaryKey = 'memberID';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'password',
        'email',
        'fullname',
        'signupdate',
        'lastlogin',
        'status',
        'salt',
        'verify_token',
        'newsletter',
        'reg_source',
        'reg_media'
    ];

    public static function generateHash(string $password, string $email = ''): array
    {
        $salt = bin2hex(random_bytes(16));
        $hash = hash('sha256', $salt . $password);
        return ['salt' => $salt, 'hash' => $hash];
    }

    public static function verifyPassword(string $password, string $storedHash, string $salt = '', string $email = ''): bool
    {
        if (empty($storedHash)) {
            return false;
        }

        // 1. Primary: SHA-256 ($salt . $password)
        if (!empty($salt) && hash_equals($storedHash, hash('sha256', $salt . $password))) {
            return true;
        }

        // 2. Fallback Variant A: SHA-256 ($password . $salt)
        if (!empty($salt) && hash_equals($storedHash, hash('sha256', $password . $salt))) {
            return true;
        }

        // 3. Fallback Variant B: Pure SHA-256 ($password)
        if (hash_equals($storedHash, hash('sha256', $password))) {
            return true;
        }

        // 4. Fallback Variant C: Legacy VLC SHA-512 + pepper
        if (!empty($salt)) {
            $pepper = 'B3!21t454t03';
            if (hash_equals($storedHash, hash('sha512', $salt . $pepper . $password . $salt))) {
                return true;
            }
        }

        // 5. Fallback Variant D: Deterministik legacy SHA-512 jika email disertakan
        if (!empty($email)) {
            $cleanEmail = strtolower(trim($email));
            $detSalt = hash('sha512', '[' . $cleanEmail . '>|<' . $password . ']');
            $detHash = hash('sha512', $detSalt . 'B3!21t454t03' . $password . $detSalt);
            if (hash_equals($storedHash, $detHash)) {
                return true;
            }
        }

        return false;
    }

    public function registerMember(array $data): int|string|false
    {
        $email = strtolower(trim($data['email']));
        $hashData = self::generateHash($data['password'], $email);

        $memberData = [
            'fullname'    => trim($data['fullname']),
            'email'       => $email,
            'password'    => $hashData['hash'],
            'salt'        => $hashData['salt'],
            'status'      => 'active',
            'signupdate'  => date('Y-m-d H:i:s'),
            'lastlogin'   => date('Y-m-d H:i:s'),
            'reg_source'  => 'web_vlc',
            'reg_media'   => 'frontend',
            'newsletter'  => !empty($data['newsletter']) ? 1 : 0,
        ];

        return $this->insert($memberData);
    }
}
