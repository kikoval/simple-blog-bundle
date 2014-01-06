<?php
namespace SimpleBlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use SimpleBlogBundle\Entity\Post;
use SimpleBlogBundle\Form\PostFormType;

class PostController extends Controller
{
    public function listAction()
    {
        $posts = $this->getDoctrine()
                    ->getRepository('SimpleBlogBundle:Post')->findAll();

        return $this->render('SimpleBlogBundle:Post:list.html.twig',
            array('posts' => $posts)
        );
    }

    public function newFormAction(Request $request)
    {
        $post = new Post();
        $form = $this->createForm(new PostFormType(), $post);

        $form->handleRequest($request);

        return $this->render('SimpleBlogBundle:Post:new.html.twig',
            array('form' => $form->createView())
        );
    }

    public function newAction(Request $request)
    {
        $post = new Post();
        $form = $this->createForm(new PostFormType(), $post);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            // redirect to the list of posts
            return $this->redirect($this->generateUrl('blog_home'));
        }

        return $this->newFormAction($request);
    }

    public function viewAction($id)
    {
        $post = $this->getDoctrine()
                    ->getRepository('SimpleBlogBundle:Post')->find($id);

        if ($post == NULL) {
            throw $this->createNotFoundException('No post found for id!');
        }

        $delete_form = $this->createFormBuilder($post)
        ->setMethod('DELETE')
        ->setAction($this->generateUrl('blog_post_delete',
            array('id' => $post->getId())))
            ->add('delete', 'submit')
            ->getForm();


        return $this->render('SimpleBlogBundle:Post:view.html.twig',
            array('post' => $post, 'delete_form' => $delete_form->createView())
        );
    }

    public function editFormAction($id, Request $request)
    {
        $post = $this->getDoctrine()
                    ->getRepository('SimpleBlogBundle:Post')->find($id);

        if ($post == NULL) {
            throw $this->createNotFoundException('No post found for id!');
        }

        $form = $this->createForm(new PostFormType(), $post,
            array('action' => $this->generateUrl('blog_post_update',
                                                  array('id' => $post->getId()))
            )
        );

        return $this->render('SimpleBlogBundle:Post:edit.html.twig',
            array('form' => $form->createView())
        );
    }

    public function updateAction($id, Request $request)
    {
        $post = $this->getDoctrine()
                    ->getRepository('SimpleBlogBundle:Post')->find($id);

        if ($post == NULL) {
            throw $this->createNotFoundException('No post found for id!');
        }

        $form = $this->createForm(new PostFormType(), $post);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $post->setUpdatedAt(new \DateTime());

            // save the entity
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            // redirect to the edited post
            return $this->redirect($this->generateUrl('blog_post_view',
                array('id' => $post->getId())
            ));
        }

        // otherwise return the form
        return $this->editAction($request);
    }

    public function deleteAction($id, Request $request)
    {
        $is_delete = $request->getMethod() == "DELETE";

        if (!$is_delete) {
            return $this->redirect($this->generateUrl('blog_home'));
        }

        $post = $this->getDoctrine()
                    ->getRepository('SimpleBlogBundle:Post')->find($id);

        if ($post != NULL) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($post);
            $em->flush();
        } else {
            throw $this->createNotFoundException('No post found for id!');
        }

        return $this->redirect($this->generateUrl('blog_home'));
    }
}
