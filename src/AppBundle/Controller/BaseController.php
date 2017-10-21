<?php

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class BaseController extends Controller
{
    protected function throw404($message = 'Page not found')
    {
        throw new NotFoundHttpException($message);
    }

    protected function throw400($message = 'Invalid JSONsdfsdfsdf body')
    {
        throw new HttpException(400, $message);
    }

    protected function throw422($message = 'Unprocessable Entity')
    {
        throw new UnprocessableEntityHttpException($message);
    }

    protected function createApiResponse($data, $statusCode = 200)
    {
        return new JsonResponse($data, $statusCode, array(
            'Content-Type' => 'application/json'
        ));
    }
}