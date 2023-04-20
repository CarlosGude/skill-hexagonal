<?php

namespace App\Infrastructure\Http\HealthCheck;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetController extends AbstractController
{
    #[Route('/', name: 'check', methods: ['GET'])]
    final public function get(): Response
    {
        return $this->json(['status' => 'ok', 'seed' => uniqid()]);
    }
}
