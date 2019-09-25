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

    public function getTotalNumberOfContributions(): int
    {
        return $this->cache->get('contributions', function (ItemInterface $item) {
            /** @var ResponseInterface $response */
            $response = $this->httpClient->request('GET', 'https://api.github.com/search/issues?q=%23SymfonyHackday+created:>'.$this->fromDate);
            $content = $response->toArray();

            $item->expiresAfter(60);

            return $content['total_count'];
        });
    }
}
