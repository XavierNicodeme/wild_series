<?php

namespace App\DataFixtures;

use App\Entity\Season;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public const SEASONS = [
        1 => [
            'year' => 2011,
            'description' => 'season 1',
        ],
        2 => [
            'year' => 2012,
            'description' => 'season 2',
        ],
        3 => [
            'year' => 2013,
            'description' => 'season 3',
        ],
        4 => [
            'year' => 2014,
            'description' => 'season 4',
        ],
        5 => [
            'year' => 2015,
            'description' => 'season 5',
        ],
    ];

    public function load(ObjectManager $manager): void
    {

        foreach(self::SEASONS as $seasonNumber => $seasonInfo) {
            $season = new Season();
            $season->setNumber($seasonNumber);
            $season->setYear($seasonInfo['year']);
            $season->setDescription($seasonInfo['description']);
            $season->setProgram($this->getReference('program'));
            $manager->persist($season);
            $this->addReference('season_' . $seasonNumber, $season);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ProgramFixtures::class,
        ];

    }
}
