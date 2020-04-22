<?php

namespace App\DataFixtures;

use App\Entity\Marque;
use App\Entity\Modele;
use App\Entity\Voiture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $marque1 = new Marque();

        $marque1->setLibelle("Audi");
        $manager->persist($marque1);

        $marque2 = new Marque();
        $marque2->setLibelle("BMW");
        $manager->persist($marque2);

        $marque3 = new Marque();
        $marque3->setLibelle("Mercedes_Benz");
        $manager->persist($marque3);



        $modele1 = new Modele();
        $modele1->setLibelle("Audi_A3");
        $modele1->setPrixMoyen(450000);
        $modele1->setImage("modele1.jpg");
        $modele1->setMarque($marque1);
        $manager->persist($modele1);

        $modele2 = new Modele();
        $modele2->setLibelle("Audi_Q8");
        $modele2->setPrixMoyen(860000);
        $modele2->setImage("modele2.jpg");
        $modele2->setMarque($marque1);
        $manager->persist($modele2);

        $modele3 = new Modele();
        $modele3->setLibelle("Bmw_Serie_8");
        $modele3->setPrixMoyen(860000);
        $modele3->setImage("modele3.jpg");
        $modele3->setMarque($marque2);
        $manager->persist($modele3);


        $modele4 = new Modele();
        $modele4->setLibelle("AMG_GT");
        $modele4->setPrixMoyen(930000);
        $modele4->setImage("modele4.jpg");
        $modele4->setMarque($marque3);
        $manager->persist($modele4);

        $modele5 = new Modele();
        $modele5->setLibelle("Bmw_Z4");
        $modele5->setPrixMoyen(860000);
        $modele5->setImage("modele5.jpg");
        $modele5->setMarque($marque2);
        $manager->persist($modele5);

        $modele6 = new Modele();
        $modele6->setLibelle("Classe_GLA");
        $modele6->setPrixMoyen(790000);
        $modele6->setImage("modele6.jpg");
        $modele6->setMarque($marque3);
        $manager->persist($modele6);

        $modeles = [$modele1, $modele2, $modele3, $modele4, $modele5, $modele6];

        $faker = \Faker\Factory::create('fr_FR');
        foreach ($modeles as $m) {
            $rand = rand(3,6);
            for ($i=1; $i <= $rand ; $i++) {
                $voiture = new Voiture();
                //xx1234xx
                $voiture->setImmatriculation($faker->regexify('[A-Z]{2}[0-9]{3,4}[A-Z]{2}'))
                        ->setNbPortes($faker->randomElement($array = array ('2','4')))
                        ->setAnnee($faker->numberBetween($min = 1990, $max = 2020))
                        ->setModele($m);
                $manager->persist($voiture);

            }
        }




        $manager->flush();
    }
}
