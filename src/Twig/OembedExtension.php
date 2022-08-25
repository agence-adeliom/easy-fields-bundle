<?php

namespace Adeliom\EasyFieldsBundle\Twig;

use Embed\Embed;
use Embed\Extractor;
use Exception;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class OembedExtension extends AbstractExtension
{
    private ?Extractor $embed = null;

    /**
     * @var mixed
     */
    private $url;

    /**
     * @return TwigFilter[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('oembed_html', \Closure::fromCallable(fn ($url) => $this->getCode($url)), ['is_safe' => ['html']]),
            new TwigFilter('oembed_size', \Closure::fromCallable(fn ($url): ?array => $this->getDimensions($url))),
        ];
    }

    public function getFunctions(): array
    {
        return [
            // new TwigFunction('oembed', [$this, 'getOembed']),
        ];
    }

    public function getOembed($url)
    {
        if (!$this->embed || $url != $this->url) {
            try {
                $this->url = $url;
                $this->embed = (new Embed())->get($url);
            } catch (Exception) {
                return null;
            }
        }

        return $this->embed;
    }

    public function getCode($url)
    {
        if ($this->getOembed($url) && $code = $this->getOembed($url)->code) {
            return $code->html;
        }

        return null;
    }

    public function getDimensions($url): ?array
    {
        if ($this->getOembed($url) && $code = $this->getOembed($url)->code) {
            return [
                'width' => $code->width,
                'height' => $code->height,
                'ratio' => $code->ratio,
            ];
        }

        return null;
    }
}
