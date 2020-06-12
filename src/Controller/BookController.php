<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\Book\BookCreateDTO;
use App\DTO\Book\BookEditDTO;
use App\Entity\Book;
use App\Form\BookCreateType;
use App\Form\BookEditType;
use App\Repository\BookRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/book")
 */
class BookController extends AbstractController
{
    /**
     * @Route("/list/{page<\d+>?1}", methods={"GET"}, name="book_list", priority=2)
     */
    public function list(
        Request $request,
        int $page,
        BookRepository $repository,
        PaginatorInterface $paginator
    ): Response {
        $limit = (int) $request->query->get('limit', 20);

        $pagination = $paginator->paginate(
            $repository->getOrderedQB(),
            $page,
            $limit
        );

        return $this->render('book/list.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/new", name="book_new", methods={"GET","POST"}, priority=1)
     */
    public function new(Request $request): Response
    {
        $dto = new BookCreateDTO();

        $form = $this->createForm(BookCreateType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entity = new Book($dto->getName(), $dto->getCategories());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($entity);
            $entityManager->flush();

            return $this->redirectToRoute('book_show', ['id' => $entity->getId()]);
        }

        return $this->render('book/new.html.twig', [
            'book' => $dto,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="book_show", methods={"GET"}, priority=1)
     */
    public function show(Book $book): Response
    {
        return $this->render('book/show.html.twig', [
            'book' => $book,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="book_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Book $book): Response
    {
        $dto = BookEditDTO::createFromEntity($book);

        $form = $this->createForm(BookEditType::class, $dto);
        $form->handleRequest($request);

        $id = $book->getId();

        if ($form->isSubmitted() && $form->isValid()) {
            $book->updateByDTO($dto);

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('book_show', ['id' => $id]);
        }

        return $this->render('book/edit.html.twig', [
            'id' => $id,
            'book' => $dto,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="book_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Book $book): Response
    {
        if ($this->isCsrfTokenValid('delete'.$book->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($book);
            $entityManager->flush();
        }

        return $this->redirectToRoute('book_list');
    }
}
