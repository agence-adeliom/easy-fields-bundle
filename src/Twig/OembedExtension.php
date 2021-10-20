<?php


namespace Adeliom\EasyFieldsBundle\Twig;

use Embed\Embed;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class OembedExtension extends AbstractExtension
{
    private $embed;
    private $url;

    public function getFilters()
    {
        return [
            new TwigFilter('oembed_html', [$this, 'getCode'], ["is_safe" => ["html"]]),
            new TwigFilter('oembed_size', [$this, 'getDimensions']),
        ];
    }

    public function getFunctions()
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
            }catch (\Exception $e){
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

    public function getDimensions($url){
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
