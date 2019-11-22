<?php

declare(strict_types=1);

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class IncludeExtension extends AbstractExtension
{
    /** @var string */
    private $projectRoot;

    public function __construct(string $projectRoot)
    {
        $this->projectRoot = $projectRoot;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('includeAsset', [$this, 'includeAsset'], [
                'is_safe' => ['html'],
            ]),
        ];
    }

    public function includeAsset(string $file): string
    {
        return file_get_contents($this->projectRoot.'/build/'.$file);
    }

    public function getName(): string
    {
        return 'app_include';
    }
}
