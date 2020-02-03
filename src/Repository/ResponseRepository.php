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
    public function findResponseDataForQuery(string $fileLocation, string $queryData, ?array $postData = []): JsonResponse
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
        $criteria = new Criteria();

        $criteria->where($eb->eq('queryString', $queryData));

        $responseData = $responses->matching($criteria);

        if (!empty($postData)) {
            $responseData = $this->findClosestMatch($responseData, $postData);
        } else {
            $responseData = $responseData->first();
        }

        //var_dump($content);

        if (empty($responseData)) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse($responseData['content'], $responseData['statusCode']);
    }

    private function findClosestMatch(ArrayCollection $responses, array $postData): array
    {
        foreach ($responses->getValues() as $response) {
            $matches = array_intersect_assoc($response, $postData);

            if (count($matches) === count($response)) {
                return $response;
            }
        }

        return [];
    }
}