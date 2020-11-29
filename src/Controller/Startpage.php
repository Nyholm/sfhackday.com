<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\ContributionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class Startpage extends AbstractController
{
    /** @var ContributionService */
    private $contributionService;

    public function __construct(ContributionService $contributionService)
    {
        $this->contributionService = $contributionService;
    }

    public function index(): Response
    {
        return $this->render('startpage.html.twig', [
            'contributions' => $this->contributionService->getNumberOfContributionsToday(),
        ]);
    }
}
