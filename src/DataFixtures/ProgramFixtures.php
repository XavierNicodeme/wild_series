<?php

namespace App\DataFixtures;

use App\Entity\Program;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    public Slugify $slugify;

    public function __construct(Slugify $slugify)
    {
        $this->slugify = $slugify;
    }

    public function load(ObjectManager $manager): void
    {
        $program = new Program();
        $program->setTitle('The Walking Dead');
        $program->setSummary('Des zombies envahissent la terre');
        $program->setPoster('https://fr.web.img3.acsta.net/pictures/21/04/19/14/51/5593951.jpg');
        $program->setCategory($this->getReference('category_0'));
        $program->setOwner($this->getReference('contributor'));
        for($i = 0; $i < count(ActorFixtures::ACTORS); $i++) {
            $program->addActor($this->getReference('actor_' . $i));
        }
        $slug = $this->slugify->generate($program->getTitle());
        $program->setSlug($slug);
        $manager->persist($program);
        $manager->flush();
        $this->addReference('program', $program);
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            ActorFixtures::class,
            CategoryFixtures::class,
        ];
    }
}
