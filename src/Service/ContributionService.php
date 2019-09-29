<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ContributionService
{
    private $httpClient;
    private $cache;

    /** @var string YYYY-mm-dd */
    private $fromDate;

    public function __construct(HttpClientInterface $httpClient, CacheInterface $cache, string $fromDate)
    {
        $this->httpClient = $httpClient;
        $this->cache = $cache;
        $this->fromDate = $fromDate;
    }

    /**
     * Get the number of contributions since UTC yesterday midnight.
     * This is not perfect but it will give a good number if the Hackday take place in both Tokyo
     * and Vancouver.
     */
    public function getNumberOfContributionsToday(): int
    {
        $date = new \DateTime('yesterday');
        $date->setTime(0, 0, 0);

        return $this->fetchFromApi($date->format('Y-m-d'), 60);
    }

    /**
     * Get the total number of issues on Github marked with #SymfonyHackday since beginning of time.
     */
    public function getTotalNumberOfContributions(): int
    {
        return $this->fetchFromApi($this->fromDate, 180);
    }

    private function fetchFromApi(string $fromDate, int $cacheLifetime): int
    {
        return $this->cache->get(sha1('total_contributions_'.$fromDate), function (ItemInterface $item) use ($fromDate, $cacheLifetime) {
            /** @var ResponseInterface $response */
            $response = $this->httpClient->request('GET', 'https://api.github.com/search/issues?q=%23SymfonyHackday+created:>'.$fromDate);
            $content = $response->toArray();

            $item->expiresAfter($cacheLifetime);

            return $content['total_count'] ?? 0;
        });
    }
}
