<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Task;
use AppBundle\Helper\AppHelper;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class Load3TaskData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $data = [
            [
                'name' => 'Развернуть тестовый сервер',
                'description' => 'Использовать ОС: CentOS 7, сервер nginx в связке с PHP-FPM ',
                'author' => '1',
                'status' => '1',
                'performer' => '2',
                'dateCreate' => '2017-05-18'

            ],
            [
                'name' => 'Оплатить счет',
                'description' => 'Счет № 485645164644 на сумму 100$',
                'author' => '2',
                'status' => '4',
                'performer' => '3',
                'dateCreate' => '2017-05-21'
            ],
            [
                'name' => 'Встретится с клиентом',
                'description' => 'Обсудить текущее состояние проекта. наметить планы очередного релиза',
                'author' => '3',
                'status' => '1',
                'performer' => '1',
                'dateCreate' => '2017-05-21'

            ],
            [
                'name' => 'проанализировать баг лист',
                'description' => 'Выбрать 20 наиболее важных багов, которые необходимо решить в первую очередь в порядке приоритета',
                'author' => '4',
                'status' => '3',
                'performer' => '2',
                'dateCreate' => '2017-05-23'

            ],
            [
                'name' => 'Сделать репликацию БД',
                'description' => 'Настроить репликацию БД проекта №12 на серверах A и C мастер - мастер',
                'author' => '4',
                'status' => '2',
                'performer' => '2',
                'dateCreate' => '2017-05-24'

            ],
            [
                'name' => 'Настроить бэкап сервера',
                'description' => 'Сделать бэкап на 4 утра по МСК всего сервера, хранить состояния за последнии 3 дня',
                'author' => '1',
                'status' => '2',
                'performer' => '2',
                'dateCreate' => '2017-05-24'

            ]
        ];



        foreach ($data as $row) {
            $task = new Task();

            $task->setName($row['name']);
            $task->setDescription($row['description']);
            $task->setAuthor(AppHelper::em()->getReference('AppBundle\Entity\User', $row['author']));
            $task->setStatus(AppHelper::em()->getReference('AppBundle\Entity\ListStatus', $row['status']));
            $task->setPerformer(AppHelper::em()->getReference('AppBundle\Entity\User', $row['performer']));
            $task->setDateCreate(new \DateTime($row['dateCreate']));

            $manager->persist($task);
        }

        $manager->flush();
    }
}