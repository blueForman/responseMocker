<?php


namespace ResponseMocker\Controller;


use Symfony\Component\HttpFoundation\Response;

final class MockController
{
    public function getAction(): Response
    {
        return new Response('bok');
    }

    public function deleteAction(): Response
    {
        return new Response('bok');
    }

    public function postAction(): Response
    {
        return new Response('bok');
    }

    public function patchAction(): Response
    {
        return new Response('bok');
    }

}