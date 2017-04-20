<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\TodoItem;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class ToDoController extends Controller
{
  /**
  * @Route("/todo_new")
  */
  public function newAction(Request $request)
  {
    // create a todo
    $todo = New TodoItem();
    $todo->setState(true);

    $form = $this->createFormBuilder($todo)
    ->add('name', TextType::class)
    ->add('description', TextType::class)
    ->add('save', SubmitType::class, array('label' => 'Create Todo'))
    ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->persist($todo);
      $em->flush();

      return $this->redirectToRoute('todo');
    }

    return $this->render('todo/form.html.twig', array(
      'form' => $form->createView(),
    ));
  }

    /**
     * @Route("/todo", name="todo")
     */
    public function todoListAction(Request $request)
    {
      $repository = $this->getDoctrine()->getRepository('AppBundle:TodoItem');

      // find *all* todo items
      $todos = $repository->findAll();

      return $this->render('todo/list.html.twig', array(
        'todos' => $todos,
      ));
    }

    /**
    * Matches /todo_edit/*
    *
    * @Route("/todo_edit/{slug}", name="todo_edit")
    */
    public function editAction($slug, Request $request)
    {
      $todo = $this->getDoctrine()
      ->getRepository('AppBundle:TodoItem')
      ->find($slug);

      if (!$todo) {
        throw $this->createNotFoundException(
          'No todo found for id '.$slug
        );
      }

      $form = $this->createFormBuilder($todo)
      ->add('name', TextType::class)
      ->add('description', TextType::class)
      ->add('save', SubmitType::class, array('label' => 'Modify Todo'))
      ->getForm();

      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $this->redirectToRoute('todo');
      }

      return $this->render('todo/form.html.twig', array(
        'form' => $form->createView(),
      ));
    }

    /**
    * Matches /todo_delete/*
    *
    * @Route("/todo_delete/{slug}", name="todo_delete")
    */
    public function deleteAction($slug)
    {
      $todo = $this->getDoctrine()
      ->getRepository('AppBundle:TodoItem')
      ->find($slug);

      if (!$todo) {
        throw $this->createNotFoundException(
          'No todo found for id '.$slug
        );
      }

      $em = $this->getDoctrine()->getManager();
      $em->remove($todo);
      $em->flush();

      return $this->redirectToRoute('todo');
    }

}
