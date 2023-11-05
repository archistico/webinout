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

        // Entrata

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

        $lavoretti = new \App\Entity\Micro();
        $lavoretti->setNome("Lavoretti");
        $lavoretti->setInvio(false);
        $lavoretti->setPadre($hobbies);
        $manager->persist($lavoretti);

        $restituzioni_famiglia = new \App\Entity\Micro();
        $restituzioni_famiglia->setNome("Restituzioni");
        $restituzioni_famiglia->setInvio(false);
        $restituzioni_famiglia->setPadre($famiglia_entrata);
        $manager->persist($restituzioni_famiglia);

        $regali_famiglia = new \App\Entity\Micro();
        $regali_famiglia->setNome("Regali");
        $regali_famiglia->setInvio(false);
        $regali_famiglia->setPadre($famiglia_entrata);
        $manager->persist($regali_famiglia);

        $restituzioni_amici = new \App\Entity\Micro();
        $restituzioni_amici->setNome("Restituzioni");
        $restituzioni_amici->setInvio(false);
        $restituzioni_amici->setPadre($amici_entrata);
        $manager->persist($restituzioni_amici);

        $regali_amici = new \App\Entity\Micro();
        $regali_amici->setNome("Regali");
        $regali_amici->setInvio(false);
        $regali_amici->setPadre($amici_entrata);
        $manager->persist($regali_amici);

        // Uscite

        $pranzo = new \App\Entity\Micro();
        $pranzo->setNome("Pranzo");
        $pranzo->setInvio(false);
        $pranzo->setPadre($personale);
        $manager->persist($pranzo);

        $cena = new \App\Entity\Micro();
        $cena->setNome("Cena");
        $cena->setInvio(false);
        $cena->setPadre($personale);
        $manager->persist($cena);

        $colazione = new \App\Entity\Micro();
        $colazione->setNome("Colazione");
        $colazione->setInvio(false);
        $colazione->setPadre($personale);
        $manager->persist($colazione);

        $bar = new \App\Entity\Micro();
        $bar->setNome("Bar");
        $bar->setInvio(false);
        $bar->setPadre($personale);
        $manager->persist($bar);

        $supermercato = (new \App\Entity\Micro())
                ->setNome("Supermercato")
                ->setInvio(false)
                ->setPadre($personale);
        $manager->persist($supermercato);

        $carburante_auto = (new \App\Entity\Micro())
                ->setNome("Carburante auto")
                ->setInvio(false)
                ->setPadre($personale);
        $manager->persist($carburante_auto);

        $carburante_moto = (new \App\Entity\Micro())
                ->setNome("Carburante moto")
                ->setInvio(false)
                ->setPadre($personale);
        $manager->persist($carburante_moto);

        $serate_amici = (new \App\Entity\Micro())
                ->setNome("Uscite")
                ->setInvio(false)
                ->setPadre($amici_uscita);
        $manager->persist($serate_amici);

        $serate_famiglia = (new \App\Entity\Micro())
                ->setNome("Uscite")
                ->setInvio(false)
                ->setPadre($famiglia_uscita);
        $manager->persist($serate_famiglia);

        $abbonamenti_tv = (new \App\Entity\Micro())
                ->setNome("Abbonamenti tv")
                ->setInvio(false)
                ->setPadre($personale);
        $manager->persist($abbonamenti_tv);

        $telefonia = (new \App\Entity\Micro())
                ->setNome("Telefonia")
                ->setInvio(false)
                ->setPadre($personale);
        $manager->persist($telefonia);

        $internet = (new \App\Entity\Micro())
                ->setNome("Internet")
                ->setInvio(false)
                ->setPadre($personale);
        $manager->persist($internet);

        $costoCC = (new \App\Entity\Micro())
                ->setNome("Costo C/C")
                ->setInvio(false)
                ->setPadre($personale);
        $manager->persist($costoCC);

        $costoBonifico = (new \App\Entity\Micro())
                ->setNome("Costo bonifico")
                ->setInvio(false)
                ->setPadre($personale);
        $manager->persist($costoBonifico);

        $sport = (new \App\Entity\Micro())
                ->setNome("Sport")
                ->setInvio(false)
                ->setPadre($personale);
        $manager->persist($sport);

        $piano_accumulo = (new \App\Entity\Micro())
                ->setNome("Piano di accumulo")
                ->setInvio(true)
                ->setPadre($personale);
        $manager->persist($piano_accumulo);

        $gas = (new \App\Entity\Micro())
                ->setNome("Gas")
                ->setInvio(false)
                ->setPadre($personale);
        $manager->persist($gas);

        $acqua = (new \App\Entity\Micro())
                ->setNome("Acqua")
                ->setInvio(false)
                ->setPadre($personale);
        $manager->persist($acqua);

        $elettricita = (new \App\Entity\Micro())
                ->setNome("Elettricità")
                ->setInvio(false)
                ->setPadre($personale);
        $manager->persist($elettricita);

        $autostrada = (new \App\Entity\Micro())
                ->setNome("Autostrada")
                ->setInvio(false)
                ->setPadre($personale);
        $manager->persist($autostrada);

        $abbigliamento = (new \App\Entity\Micro())
                ->setNome("Abbigliamento")
                ->setInvio(false)
                ->setPadre($personale);
        $manager->persist($abbigliamento);

        $corsi = (new \App\Entity\Micro())
                ->setNome("Corsi")
                ->setInvio(false)
                ->setPadre($personale);
        $manager->persist($corsi);

        $libri = (new \App\Entity\Micro())
                ->setNome("Libri")
                ->setInvio(false)
                ->setPadre($personale);
        $manager->persist($libri);

        $vacanza = (new \App\Entity\Micro())
                ->setNome("Vacanza")
                ->setInvio(false)
                ->setPadre($personale);
        $manager->persist($vacanza);

        $commercialista = (new \App\Entity\Micro())
                ->setNome("Commercialista")
                ->setInvio(false)
                ->setPadre($personale);
        $manager->persist($commercialista);

        $bollo_auto = (new \App\Entity\Micro())
                ->setNome("Bollo auto")
                ->setInvio(false)
                ->setPadre($personale);
        $manager->persist($bollo_auto);

        $bollo_moto = (new \App\Entity\Micro())
                ->setNome("Bollo moto")
                ->setInvio(false)
                ->setPadre($personale);
        $manager->persist($bollo_moto);

        $assicurazione_auto = (new \App\Entity\Micro())
                ->setNome("Assicurazione auto")
                ->setInvio(false)
                ->setPadre($personale);
        $manager->persist($assicurazione_auto);

        $assicurazione_moto = (new \App\Entity\Micro())
                ->setNome("Assicurazione moto")
                ->setInvio(false)
                ->setPadre($personale);
        $manager->persist($assicurazione_moto);

        $visite_mediche = (new \App\Entity\Micro())
                ->setNome("Visite mediche")
                ->setInvio(false)
                ->setPadre($personale);
        $manager->persist($visite_mediche);

        $hosting = (new \App\Entity\Micro())
                ->setNome("Hosting")
                ->setInvio(false)
                ->setPadre($personale);
        $manager->persist($hosting);

        $manutenzione_auto = (new \App\Entity\Micro())
                ->setNome("Manutenzione auto")
                ->setInvio(false)
                ->setPadre($personale);
        $manager->persist($manutenzione_auto);

        $manutenzione_moto = (new \App\Entity\Micro())
                ->setNome("Manutenzione moto")
                ->setInvio(false)
                ->setPadre($personale);
        $manager->persist($manutenzione_moto);

        $manutenzione_casa = (new \App\Entity\Micro())
                ->setNome("Manutenzione casa")
                ->setInvio(false)
                ->setPadre($personale);
        $manager->persist($manutenzione_casa);

        $tasse = (new \App\Entity\Micro())
                ->setNome("Tasse")
                ->setInvio(true)
                ->setPadre($personale);
        $manager->persist($tasse);

        $Imu = (new \App\Entity\Micro())
                ->setNome("Imu")
                ->setInvio(true)
                ->setPadre($personale);
        $manager->persist($Imu);

        $rifiuti = (new \App\Entity\Micro())
                ->setNome("Rifiuti")
                ->setInvio(true)
                ->setPadre($personale);
        $manager->persist($rifiuti);

        $prodotti_casa = (new \App\Entity\Micro())
                ->setNome("Prodotti per la casa")
                ->setInvio(false)
                ->setPadre($personale);
        $manager->persist($prodotti_casa);

        $prodotti_bagno = (new \App\Entity\Micro())
                ->setNome("Prodotti per il bagno")
                ->setInvio(false)
                ->setPadre($personale);
        $manager->persist($prodotti_bagno);

        $bolli = (new \App\Entity\Micro())
                ->setNome("Bolli")
                ->setInvio(false)
                ->setPadre($personale);
        $manager->persist($bolli);

        $pensione = (new \App\Entity\Micro())
                ->setNome("Pensione")
                ->setInvio(false)
                ->setPadre($personale);
        $manager->persist($pensione);

        $spese_condominiali = (new \App\Entity\Micro())
                ->setNome("Spese condominiali")
                ->setInvio(false)
                ->setPadre($personale);
        $manager->persist($spese_condominiali);

        $tagliando_auto = (new \App\Entity\Micro())
                ->setNome("Tagliando auto")
                ->setInvio(false)
                ->setPadre($personale);
        $manager->persist($tagliando_auto);

        $tagliando_moto = (new \App\Entity\Micro())
                ->setNome("Tagliando moto")
                ->setInvio(false)
                ->setPadre($personale);
        $manager->persist($tagliando_moto);

        $riparazioni = (new \App\Entity\Micro())
                ->setNome("Riparazioni")
                ->setInvio(false)
                ->setPadre($personale);
        $manager->persist($riparazioni);

        $meccanico_auto = (new \App\Entity\Micro())
                ->setNome("Meccanico auto")
                ->setInvio(false)
                ->setPadre($personale);
        $manager->persist($meccanico_auto);

        $meccanico_moto = (new \App\Entity\Micro())
                ->setNome("Meccanico moto")
                ->setInvio(false)
                ->setPadre($personale);
        $manager->persist($meccanico_moto);

        $aggiornamenti = (new \App\Entity\Micro())
                ->setNome("Aggiornamenti")
                ->setInvio(false)
                ->setPadre($personale);
        $manager->persist($aggiornamenti);

        $software = (new \App\Entity\Micro())
                ->setNome("Software")
                ->setInvio(false)
                ->setPadre($personale);
        $manager->persist($software);

        $video_game = (new \App\Entity\Micro())
                ->setNome("Video game")
                ->setInvio(false)
                ->setPadre($personale);
        $manager->persist($video_game);

        $arredo = (new \App\Entity\Micro())
                ->setNome("Arredo")
                ->setInvio(false)
                ->setPadre($personale);
        $manager->persist($arredo);

        $smartphone = (new \App\Entity\Micro())
                ->setNome("Smartphone")
                ->setInvio(false)
                ->setPadre($personale);
        $manager->persist($smartphone);

        $elettrodomestici = (new \App\Entity\Micro())
                ->setNome("Elettrodomestici")
                ->setInvio(false)
                ->setPadre($personale);
        $manager->persist($elettrodomestici);

        $pc_accessori = (new \App\Entity\Micro())
                ->setNome("PC e accessori")
                ->setInvio(false)
                ->setPadre($personale);
        $manager->persist($pc_accessori);

        $auto = (new \App\Entity\Micro())
                ->setNome("Auto")
                ->setInvio(false)
                ->setPadre($personale);
        $manager->persist($auto);

        $moto = (new \App\Entity\Micro())
                ->setNome("Moto")
                ->setInvio(false)
                ->setPadre($personale);
        $manager->persist($moto);

        $bicicletta = (new \App\Entity\Micro())
                ->setNome("Bicicletta")
                ->setInvio(false)
                ->setPadre($personale);
        $manager->persist($bicicletta);

        $impianti = (new \App\Entity\Micro())
                ->setNome("Impianti")
                ->setInvio(false)
                ->setPadre($personale);
        $manager->persist($impianti);

        $imprevisti = (new \App\Entity\Micro())
                ->setNome("Imprevisti")
                ->setInvio(false)
                ->setPadre($personale);
        $manager->persist($imprevisti);

        $albergo = (new \App\Entity\Micro())
                ->setNome("Albergo")
                ->setInvio(true)
                ->setPadre($lavoro_uscita);
        $manager->persist($albergo);

        $ristorante = (new \App\Entity\Micro())
                ->setNome("Ristorante")
                ->setInvio(true)
                ->setPadre($lavoro_uscita);
        $manager->persist($ristorante);

        $materiale = (new \App\Entity\Micro())
                ->setNome("Materiale")
                ->setInvio(true)
                ->setPadre($lavoro_uscita);
        $manager->persist($materiale);

        // ------------------
        // TIPO PAGAMENTI
        // ------------------

        $tipo_pagamento_contanti = (new \App\Entity\TipoPagamento())
            ->setDescrizione("Contanti");
        $manager->persist($tipo_pagamento_contanti);

        $tipo_pagamento_bonifico = (new \App\Entity\TipoPagamento())
            ->setDescrizione("Bonifico");
        $manager->persist($tipo_pagamento_bonifico);

        $tipo_pagamento_bancomat = (new \App\Entity\TipoPagamento())
            ->setDescrizione("Bancomat");
        $manager->persist($tipo_pagamento_bancomat);

        $tipo_pagamento_paypal = (new \App\Entity\TipoPagamento())
            ->setDescrizione("Paypal");
        $manager->persist($tipo_pagamento_paypal);

        $tipo_pagamento_cartaprepagata = (new \App\Entity\TipoPagamento())
            ->setDescrizione("Carta prepagata");
        $manager->persist($tipo_pagamento_cartaprepagata);

        $tipo_pagamento_cartacredito = (new \App\Entity\TipoPagamento())
            ->setDescrizione("Carta di credito");
        $manager->persist($tipo_pagamento_cartacredito);

        $tipo_pagamento_bollettinopostale = (new \App\Entity\TipoPagamento())
            ->setDescrizione("Bollettino postale");
        $manager->persist($tipo_pagamento_bollettinopostale);

        $manager->flush();
    }
}
