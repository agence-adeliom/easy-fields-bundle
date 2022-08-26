<?php

namespace Adeliom\EasyFieldsBundle\Controller;

use Embed\Embed;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OembedController extends AbstractController
{
    public function index(Request $request): Response
    {
        $url = $request->get('url');

        if (!$url) {
            try {
                $content = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
                $url = $content['url'] ?? null;
            } catch (\Exception) {
                $url = null;
            }
        }

        if (!$url) {
            throw new BadRequestException("The parameter 'url' is missing");
        }

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new BadRequestException("The parameter 'url' is invalid");
        }

        $embed = new Embed();
        $infos = $embed->get($url);
        if (null === $infos->code) {
            throw new BadRequestException('The URL provided has no integration code');
        }

        return $this->render('@EasyFields/crud/field/oembed.html.twig', [
            'url' => $url,
        ]);
    }
}
