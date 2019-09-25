<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\ContributionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Startpage extends AbstractController
{
    private $contributionService;

    public function __construct(ContributionService $contributionService)
    {
        $this->contributionService = $contributionService;
    }

    public function index()
    {
        return $this->render('startpage.html.twig', [
            'contributions' => $this->contributionService->getTotalNumberOfContributions(),
        ]);
    }
}
