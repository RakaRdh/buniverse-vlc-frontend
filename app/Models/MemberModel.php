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

    const PEPPER = 'B3!21t454t03';

    public static function generateHash(string $password, string $email): array
    {
        $cleanEmail = strtolower(trim($email));
        $salt = hash('sha512', '[' . $cleanEmail . '>|<' . $password . ']');
        $hash = hash('sha512', $salt . self::PEPPER . $password . $salt);
        return ['salt' => $salt, 'hash' => $hash];
    }

    public static function verifyPassword(string $password, string $storedHash, string $salt): bool
    {
        $calculatedHash = hash('sha512', $salt . self::PEPPER . $password . $salt);
        return hash_equals($storedHash, $calculatedHash);
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
