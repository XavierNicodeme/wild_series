<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public const EPISODES = [
        1 => [
            'title' => 'Episode',
            'synopsis' => 'Episode'
        ],
        2 => [
            'title' => 'Episode',
            'synopsis' => 'Episode'
        ],
        3 => [
            'title' => 'Episode',
            'synopsis' => 'Episode'
        ],
        4 => [
            'title' => 'Episode',
            'synopsis' => 'Episode'
        ],
        5 => [
            'title' => 'Episode',
            'synopsis' => 'Episode'
        ]
    ];

    public Slugify $slugify;

    public function __construct(Slugify $slugify)
    {
        $this->slugify = $slugify;
    }

    public function load(ObjectManager $manager): void
    {
        foreach(self::EPISODES as $number => $epInfo) {
            $episode = new Episode();
            $episode->setNumber($number);
            $episode->setTitle($epInfo['title'] . $number);
            $slug = $this->slugify->generate($episode->getTitle());
            $episode->setSlug($slug);
            $episode->setSynopsis($epInfo['synopsis'] . $number);
            for ($i = 1; $i <= count(SeasonFixtures::SEASONS); $i ++) {
                $episode->setSeason($this->getReference('season_' . $i));
            }
            $manager->persist($episode);
            $this->addReference('episode_' . $number, $episode);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            SeasonFixtures::class
        ];
    }
}
