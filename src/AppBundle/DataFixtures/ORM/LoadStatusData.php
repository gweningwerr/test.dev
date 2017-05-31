<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\ListStatus;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class Load2StatusData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $data = ['новая','в работе','ожидает проверку','выполненая'];

        foreach ($data as $row) {
            $status = new ListStatus();

            $status->setName($row);

            $manager->persist($status);
        }

        $manager->flush();
    }
}