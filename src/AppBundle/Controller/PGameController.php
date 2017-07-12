<?php

// src/AppBundle/Controller/PGameController.php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PGameController extends Controller
{
    static $signTab = [
        0 => 'rock',
        1 => 'paper',
        2 => 'scissors',
        3 => 'spock',
        4 => 'lizard'
    ];

    /**
     * @Route("/pgame")
     */
    public function numberAction()
    {
        $signIdx = mt_rand(0, 4);
        $sign = PGameController::$signTab[$signIdx];

        return $this->render('pgame/play.html.twig', array(
            'sign' => $sign
        ));
        /*
        return new Response(
            '<html><body>Computer sign: ' . $sign . '</body></html>'
        );
        */
    }
}