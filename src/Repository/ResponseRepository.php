<?php


namespace ResponseMocker\Repository;


use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ResponseRepository
{
    public function findGetDataForQuery(string $fileLocation, string $queryData): JsonResponse
    {
        $fileInfo = new \SplFileInfo($fileLocation);

        if (!$fileInfo->isFile()) {
            throw new NotFoundHttpException();
        }

        /** @var string $serializedContent */
        $serializedContent = file_get_contents($fileInfo->getRealPath());

        $content = json_decode($serializedContent, true);

        //var_dump($content);

        if (!isset($content[$queryData])) {
            throw new NotFoundHttpException();
        }

        $responseData = $content[$queryData];

        return new JsonResponse($responseData['content'], $responseData['statusCode']);
    }
}