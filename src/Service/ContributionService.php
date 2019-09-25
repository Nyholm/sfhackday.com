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

    public function __construct(HttpClientInterface $httpClient, CacheInterface $cache)
    {
        $this->httpClient = $httpClient;
        $this->cache = $cache;
    }

    public function getTotalNumberOfContributions(): int
    {
        $httpClient = $this->httpClient;

        return $this->cache->get('contributions', function (ItemInterface $item) use ($httpClient) {
            /** @var ResponseInterface $response */
            $response = $httpClient->request('GET', 'https://api.github.com/search/issues?q=%23SyfmonyHackday+created:>2019-11-20');
            $content = $response->toArray();

            $item->expiresAfter(60);

            return $content['total_count'];
        });
    }
}
