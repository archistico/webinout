<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
;

class MacroFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $entrata = new \App\Entity\Macro();
        $entrata->setNome("Entrata");
        $entrata->setInvio(true);

        $manager->persist($entrata);

        $uscita = new \App\Entity\Macro();
        $uscita->setNome("Uscita");
        $uscita->setInvio(true);
        
        $manager->persist($uscita);

        $manager->flush();
    }
}
