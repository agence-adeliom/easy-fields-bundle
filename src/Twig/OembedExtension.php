<?php


namespace Adeliom\EasyFieldsBundle\Twig;

use Embed\Extractor;
use Exception;
use Embed\Embed;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class OembedExtension extends AbstractExtension
{
    private ?Extractor $embed = null;

    private mixed $url;

    /**
     * @return TwigFilter[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('oembed_html', [$this, 'getCode'], ["is_safe" => ["html"]]),
            new TwigFilter('oembed_size', [$this, 'getDimensions']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            //new TwigFunction('oembed', [$this, 'getOembed']),
        ];
    }

    public function getOembed($url){
        if(!$this->embed || $url != $this->url){
            try {
                $this->url = $url;
                $this->embed = (new Embed())->get($url);
            }catch (Exception){
                return null;
            }
        }
        return $this->embed;
    }

    public function getCode($url){
        if($this->getOembed($url) && $code = $this->getOembed($url)->code){
            return $code->html;
        }
        return null;
    }

    public function getDimensions($url): ?array{
        if($this->getOembed($url) && $code = $this->getOembed($url)->code){
            return [
                "width" => $code->width,
                "height" => $code->height,
                "ratio" => $code->ratio,
            ];
        }
        return null;
    }
}
