<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $Avis = new Avis();
        // $Avis->setName('Avis #1')
        //     ->setDescription('Bonjour tres bien je recommande')
            

        $manager->flush();
    }
}
