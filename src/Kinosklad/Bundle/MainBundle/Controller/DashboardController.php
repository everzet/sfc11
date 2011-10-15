<?php

namespace Kinosklad\Bundle\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DashboardController extends Controller
{
    public function showAction()
    {
        return $this->render('KinoskladMainBundle:Dashboard:show.html.twig');
    }
}
