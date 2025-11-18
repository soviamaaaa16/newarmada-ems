<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Shield\Entities\User;

class seed extends Seeder
{
    public function run()
    {
        $users = model('UserModel');

        // ===================================
        // 1. USER ADMIN
        // ===================================
        $admin = new User([
            'username' => 'administrator',
            'email' => 'admin@example.com',
            'password' => 'admin123',
        ]);
        $users->save($admin);

        // Ambil ID user yang baru dibuat
        $adminUser = $users->findById($users->getInsertID());

        // Tambahkan ke group admin
        $adminUser->addGroup('admin');

        echo "✓ Admin user created (admin@example.com / admin123)\n";


        // ===================================
        // 2. USER BIASA
        // ===================================
        // $user = new User([
        //     'username' => 'M.9360',
        //     'email' => 'user@example.com',
        //     'password' => 'luthfi123',
        // ]);
        // $users->save($user);

        // $regularUser = $users->findById($users->getInsertID());
        // $regularUser->addGroup('user');

        // echo "✓ Regular user created (user@example.com / user123)\n";


        // ===================================
        // 3. MULTIPLE DUMMY USERS
        // ===================================
        // $dummyUsers = [
        //     [
        //         'username' => 'john_doe',
        //         'email' => 'john@example.com',
        //         'password' => 'password123',
        //         'group' => 'user',
        //     ],
        //     [
        //         'username' => 'jane_smith',
        //         'email' => 'jane@example.com',
        //         'password' => 'password123',
        //         'group' => 'user',
        //     ],
        //     [
        //         'username' => 'manager',
        //         'email' => 'manager@example.com',
        //         'password' => 'manager123',
        //         'group' => 'admin',
        //     ],
        // ];

        // foreach ($dummyUsers as $userData) {
        //     $newUser = new User([
        //         'username' => $userData['username'],
        //         'email' => $userData['email'],
        //         'password' => $userData['password'],
        //     ]);

        //     $users->save($newUser);
        //     $createdUser = $users->findById($users->getInsertID());
        //     $createdUser->addGroup($userData['group']);

        //     echo "✓ User {$userData['username']} created ({$userData['email']})\n";
        // }


        // ===================================
        // 4. USER DENGAN DATA TAMBAHAN (OPSIONAL)
        // ===================================
        // Jika tabel users punya kolom tambahan
        /*
        $userWithExtra = new User([
            'username' => 'custom_user',
            'email'    => 'custom@example.com',
            'password' => 'custom123',
        ]);
        $users->save($userWithExtra);

        // Update kolom tambahan di tabel users
        $this->db->table('users')
            ->where('id', $users->getInsertID())
            ->update([
                'phone' => '08123456789',
                'address' => 'Jakarta',
                'status' => 'active'
            ]);
        */

        echo "\n✅ All dummy users created successfully!\n";
    }
}