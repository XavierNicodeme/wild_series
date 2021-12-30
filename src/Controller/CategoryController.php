<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Program;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/category", name="category_")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(ManagerRegistry $managerRegistry): Response
    {
        $categories = $managerRegistry
            ->getRepository(Category::class)
            ->findAll();

        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/{categoryName}", methods={"GET"}, name="show")
     */
    public function show(string $categoryName, ManagerRegistry $managerRegistry): Response
    {
        $category = $managerRegistry
            ->getRepository(Category::class)
            ->findOneBy([
                'name' => $categoryName
            ]);

        $programs =$managerRegistry->getRepository(Program::class)
            ->findBy(
                ['category' => $category],
                ['id' => 'DESC'],
                3
            );

        if(!$category) {
            throw $this->createNotFoundException(
                'No Category with '. $categoryName . ' name in the Category\'table'
            );
        }

        return $this->render('category/show.html.twig', [
            'category' => $category,
            'programs' => $programs,
        ]);
    }

}