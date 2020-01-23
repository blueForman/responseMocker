<?php


namespace ResponseMocker\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class MockController
{
    public function getAction(string $fileLocation, string $path, Request $request): Response
    {
        $components = $request->getRequestUri();
        $urlComponents = explode($path, $components);
        $getParameters = array_pop($urlComponents);
        return new Response($fileLocation);
    }

    public function deleteAction(string $fileLocation): Response
    {
        return new Response('bok');
    }

    public function postAction(string $fileLocation): Response
    {
        return new Response('bok');
    }

    public function patchAction(string $fileLocation): Response
    {
        return new Response('bok');
    }

}