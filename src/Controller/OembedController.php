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
        if (!$url = $request->get("url")) {
            throw new BadRequestException("The parameter 'url' is missing");
        }

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new BadRequestException("The parameter 'url' is invalid");
        }

        $embed = new Embed();
        $infos = $embed->get($url);
        if ($infos->code === null) {
            throw new BadRequestException("The URL provided has no integration code");
        }

        return $this->render('@EasyFields/crud/field/oembed.html.twig', [
            "url" => $url,
        ]);
    }
}
