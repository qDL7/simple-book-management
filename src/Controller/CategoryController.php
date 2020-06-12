<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\Category\CategoryCreateDTO;
use App\Entity\Category;
use App\Form\CategoryCreateType;
use App\Repository\CategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/category")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/list/{page<\d+>?1}", methods={"GET"}, name="category_list", priority=2)
     */
    public function list(
        Request $request,
        int $page,
        CategoryRepository $repository,
        PaginatorInterface $paginator
    ): Response {
        $limit = (int) $request->query->get('limit', 20);

        $pagination = $paginator->paginate(
            $repository->getOrderedQB(),
            $page,
            $limit
        );

        return $this->render('category/list.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/new", name="category_new", methods={"GET","POST"}, priority=1)
     */
    public function new(Request $request): Response
    {
        $dto = new CategoryCreateDTO();

        $form = $this->createForm(CategoryCreateType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entity = new Category($dto->getName());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($entity);
            $entityManager->flush();

            return $this->redirectToRoute('category_show', ['id' => $entity->getId()]);
        }

        return $this->render('category/new.html.twig', [
            'category' => $dto,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="category_show", methods={"GET"}, priority=1)
     */
    public function show(Category $category): Response
    {
        return $this->render('category/show.html.twig', [
            'category' => $category,
        ]);
    }

    /**
     * @Route("/{id}", name="category_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Category $category): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('category_list');
    }
}
