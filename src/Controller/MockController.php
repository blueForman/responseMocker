<?php


namespace ResponseMocker\Controller;


use ResponseMocker\Repository\ResponseRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class MockController
{
    /**
     * @var ResponseRepository
     */
    private $repository;

    public function __construct(ResponseRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAction(string $fileLocation, string $path, Request $request): Response
    {
        $components = $request->getRequestUri();
        $urlComponents = explode($path, $components);
        $getParameters = array_pop($urlComponents);

        return  $this->repository->findGetDataForQuery($fileLocation, $getParameters);
    }

    public function deleteAction(string $fileLocation, string $path, Request $request): Response
    {
        $components = $request->getRequestUri();
        $urlComponents = explode($path, $components);
        $getParameters = array_pop($urlComponents);

        return  $this->repository->findDeleteDataForQuery($fileLocation, $getParameters);
    }

    public function postAction(string $fileLocation, string $path, Request $request): Response
    {
        $components = $request->getRequestUri();
        $urlComponents = explode($path, $components);
        $getParameters = array_pop($urlComponents);
        $postData = $request->request->all();

        return  $this->repository->findPostDataForQuery($fileLocation, $getParameters, $postData);
    }

    public function patchAction(string $fileLocation, string $path, Request $request): Response
    {
        $components = $request->getRequestUri();
        $urlComponents = explode($path, $components);
        $getParameters = array_pop($urlComponents);
        $postData = $request->request->all();

        return  $this->repository->findPatchDataForQuery($fileLocation, $getParameters, $postData);
    }

}