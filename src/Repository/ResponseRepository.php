<?php


namespace ResponseMocker\Repository;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\ExpressionBuilder;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
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

        $responses = new ArrayCollection($content);

        $eb = new ExpressionBuilder();
        $expression = $eb->eq('queryString', $queryData);
        $responseData = $responses->matching(new Criteria($expression))->first();

        //var_dump($content);

        if (empty($responseData)) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse($responseData['content'], $responseData['statusCode']);
    }

    public function findDeleteDataForQuery(string $fileLocation, string $queryData): JsonResponse
    {
        $fileInfo = new \SplFileInfo($fileLocation);

        if (!$fileInfo->isFile()) {
            throw new NotFoundHttpException();
        }

        /** @var string $serializedContent */
        $serializedContent = file_get_contents($fileInfo->getRealPath());

        $content = json_decode($serializedContent, true);

        $responses = new ArrayCollection($content);

        $eb = new ExpressionBuilder();
        $expression = $eb->eq('queryString', $queryData);
        $responseData = $responses->matching(new Criteria($expression))->first();

        //var_dump($content);

        if (empty($responseData)) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse($responseData['content'], $responseData['statusCode']);
    }
}