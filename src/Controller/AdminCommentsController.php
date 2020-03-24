<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\AdminCommentType;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCommentsController extends AbstractController
{
    /**
     * @Route("/admin/comments/{page<\d+>?1}", name="admin_comment_index")
     */
    public function index(CommentRepository $repository,$page,PaginationService $paginationService )
    {
        $paginationService->setEntityClass(Comment::class)
            ->setPage($page)
            ->setLimit(5);

        return $this->render('admin/comment/index.html.twig', [
            'pagination' => $paginationService
        ]);
    }

    /**
     * @Route("/admin/comments/{id}/edit",name="admin_comment_edit")
     */


    public function edit(Request $request,Comment $comment,EntityManagerInterface $manager){


        $form = $this->createForm(AdminCommentType::class,$comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le commentaire numero <strong>{$comment->getId()}</strong>a bien été modifié!!!"
            );


        }

        return $this->render('admin/comment/edit.html.twig',[
            'comment' => $comment,
            'form' => $form->createView()
        ]);


    }

    /**
     * permet de supprimer une annonce
     * @Route("/admin/comments/{id}/delete",name="admin_comment_delete")
     * @return Response
     *
     */

    public function delete(Comment $comment,EntityManagerInterface $manager){

        $commentaireId = $comment->getId();
        $manager->remove($comment);
        $manager->flush();


        $this->addFlash(
            'success',
            "le commentaire <strong>{$commentaireId}</strong> à bien été supprimer"
        );

      return  $this->redirectToRoute('admin_comment_index');

    }
}
