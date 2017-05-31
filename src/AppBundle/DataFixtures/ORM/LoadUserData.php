<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;

class Load1UserData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // пароль у всех пользователей 123456
        $users = [
            [
                'file' => '1.jpg',
                'sex' => 1,
                'username' => 'Ivan',
                'salt' => null,
                'password' => '$2y$13$WPSyJarcLYRdNlXy3LUvseyNibFAXIt8SevcaqEaXEGjUINUdzYBu',
                'username_canonical' => 'Ivan',
                'email' => 'Ivan@test.ru',
                'email_canonical' => 'Ivan@test.ru',
                'birth' => '1990-12-11',
                'city' => 'Moscow',
                'address' => 'Street 312',
                'phone' => '99999999999'
            ],
            [
                'file' => '2.jpg',
                'sex' => 1,
                'username' => 'Petr',
                'salt' => null,
                'password' => '$2y$13$WPSyJarcLYRdNlXy3LUvseyNibFAXIt8SevcaqEaXEGjUINUdzYBu',
                'username_canonical' => 'Petr',
                'email' => 'Petr@test.ru',
                'email_canonical' => 'Petr@test.ru',
                'birth' => '1988-10-23',
                'city' => 'Novosibirsk',
                'address' => 'Street 976',
                'phone' => '8888888888'
            ],
            [
                'file' => '3.jpg',
                'sex' => 2,
                'username' => 'Elena',
                'salt' => null,
                'password' => '$2y$13$WPSyJarcLYRdNlXy3LUvseyNibFAXIt8SevcaqEaXEGjUINUdzYBu',
                'username_canonical' => 'Elena',
                'email' => 'Elena@test.ru',
                'email_canonical' => 'Elena@test.ru',
                'birth' => '1993-07-01',
                'city' => 'Saint-Petersburg',
                'address' => 'Street 265',
                'phone' => '7777777777'
            ],
            [
                'file' => '4.jpg',
                'sex' => 1,
                'username' => 'Vasilij',
                'salt' => null,
                'password' => '$2y$13$WPSyJarcLYRdNlXy3LUvseyNibFAXIt8SevcaqEaXEGjUINUdzYBu',
                'username_canonical' => 'Vasilij',
                'email' => 'Vasilij@test.ru',
                'email_canonical' => 'Vasilij@test.ru',
                'birth' => '1975-09-14',
                'city' => 'Moscow',
                'address' => 'Street 786',
                'phone' => '6666666666'
            ],
            [
                'file' => '5.jpg',
                'sex' => 2,
                'username' => 'Sveta',
                'salt' => null,
                'password' => '$2y$13$WPSyJarcLYRdNlXy3LUvseyNibFAXIt8SevcaqEaXEGjUINUdzYBu',
                'username_canonical' => 'Sveta',
                'email' => 'Sveta@test.ru',
                'email_canonical' => 'Sveta@test.ru',
                'birth' => '1984-04-05',
                'city' => 'Saint-Petersburg',
                'address' => 'Street 475',
                'phone' => '5555555555'
            ]
        ];

        foreach ($users as $row) {
            $user = new User();

            $user->setUsername($row['username']);
            $user->setSalt($row['salt']);
            $user->setPassword($row['password']);
            $user->setEmail($row['email']);
            $user->setPassword($row['password']);
            $user->setBirth(new \DateTime($row['birth']));
            $user->setCity($row['city']);
            $user->setAddress($row['address']);
            $user->setPhone($row['phone']);
            $user->setEnabled(1);
            $user->setRoles([]);
            $user->setSex($row['sex']);
            $user->setChangeInfo();
            $user->setFileName($row['file']);

            $manager->persist($user);
        }

        $manager->flush();
    }
}