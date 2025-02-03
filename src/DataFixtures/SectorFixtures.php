<?php

namespace App\DataFixtures;

use App\Entity\Sector;
use App\Entity\Company;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class SectorFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $sectors = [
            'Technologie',
            'Finance',
            'Santé',
            'Éducation',
            'Commerce de détail',
            'Industrie manufacturière',
            'Construction',
            'Transport',
            'Agriculture',
            'Médias et divertissement',
            'Énergie',
            'Conseil',
            'Immobilier',
            'Tourisme et hôtellerie',
            'Aéronautique',
        ];

        foreach ($sectors as $sectorName) {
            $sector = new Sector();
            $sector->setName($sectorName);
            $manager->persist($sector);
        }

        $manager->flush();
    }

    public function getOrder(): int
    {
        return 1;
    }
}
