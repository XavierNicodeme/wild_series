<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public Slugify $slugify;
    public const CATEGORIES = [
        'Action',
        'Fantastique',
        'Comédie',
        'Manga',
        'Horreur',
    ];

    public function __construct(Slugify $slugify)
    {
        $this->slugify = $slugify;
    }

    public function load(ObjectManager $manager)
    {
        foreach (self::CATEGORIES as $key => $categoryName) {
            $category =  new Category();
            $category->setName($categoryName);
            $slug = $this->slugify->generate($category->getName());
            $category->setSlug($slug);
            $manager->persist($category);
            $this->addReference('category_' . $key, $category);
        }
        $manager->flush();
    }
}