<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ListController extends Controller
{
    /**
     * @Route("/todo_list")
     */
    public function todoListAction(Request $request)
    {
      return new Response(
          '<html><body>Todo List</body></html>'
      );
    }
}
