<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
;

class CategorieFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // MACRO
        $entrata = new \App\Entity\Macro();
        $entrata->setNome("Entrata");
        $entrata->setInvio(true);
        $manager->persist($entrata);

        $uscita = new \App\Entity\Macro();
        $uscita->setNome("Uscita");
        $uscita->setInvio(true);       
        $manager->persist($uscita);

        // -------
        // MESO
        // -------

        // Entrate

        $lavoro_entrata = new \App\Entity\Meso();
        $lavoro_entrata->setNome("Lavoro");
        $lavoro_entrata->setInvio(true);
        $lavoro_entrata->setPadre($entrata);
        $manager->persist($lavoro_entrata);

        $hobbies = new \App\Entity\Meso();
        $hobbies->setNome("Hobbies");
        $hobbies->setInvio(false);
        $hobbies->setPadre($entrata);
        $manager->persist($hobbies);

        $famiglia_entrata = new \App\Entity\Meso();
        $famiglia_entrata->setNome("Famiglia");
        $famiglia_entrata->setInvio(false);
        $famiglia_entrata->setPadre($entrata);
        $manager->persist($famiglia_entrata);

        $amici_entrata = new \App\Entity\Meso();
        $amici_entrata->setNome("Amici");
        $amici_entrata->setInvio(false);
        $amici_entrata->setPadre($entrata);
        $manager->persist($amici_entrata);

        // Uscite

        $personale = new \App\Entity\Meso();
        $personale->setNome("Personale");
        $personale->setInvio(false);
        $personale->setPadre($uscita);
        $manager->persist($personale);

        $lavoro_uscita = new \App\Entity\Meso();
        $lavoro_uscita->setNome("Lavoro");
        $lavoro_uscita->setInvio(true);
        $lavoro_uscita->setPadre($uscita);
        $manager->persist($lavoro_uscita);

        $famiglia_uscita = new \App\Entity\Meso();
        $famiglia_uscita->setNome("Famiglia");
        $famiglia_uscita->setInvio(false);
        $famiglia_uscita->setPadre($uscita);
        $manager->persist($famiglia_uscita);

        $amici_uscita = new \App\Entity\Meso();
        $amici_uscita->setNome("Amici");
        $amici_uscita->setInvio(false);
        $amici_uscita->setPadre($uscita);
        $manager->persist($amici_uscita);

        // ---------
        // MICRO
        // ---------

        $stipendio = new \App\Entity\Micro();
        $stipendio->setNome("Stipendio");
        $stipendio->setInvio(true);
        $stipendio->setPadre($lavoro_entrata);
        $manager->persist($stipendio);

        $professione = new \App\Entity\Micro();
        $professione->setNome("Professione");
        $professione->setInvio(true);
        $professione->setPadre($lavoro_entrata);
        $manager->persist($professione);

        // $ = new \App\Entity\Micro();
        // $->setNome("");
        // $->setInvio();
        // $->setPadre($);
        // $manager->persist($);

        $manager->flush();
    }
}
