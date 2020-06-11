<?php

namespace App\Exceptions;

use App\Utils\CustomResponse;
use Illuminate\Support\Facades\Lang;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait ApiExceptionTrait
{
    public function apiException($request, $e)
    {

        if ($this->isModel($e)) {
            return $this->ModelResponse($e);
        }

        if ($this->isHttp($e)) {
            return $this->HttpResponse($e);
        }

        return parent::render($request, $e);
    }

    protected function isModel($e)
    {
        return $e instanceof ModelNotFoundException;
    }

    protected function isHttp($e)
    {
        return $e instanceof NotFoundHttpException;
    }

    protected function ModelResponse($e)
    {
        return CustomResponse::setFailResponse($e->getMessage() ?: 'Model Not Found', Response::HTTP_NOT_FOUND);
    }

    protected function HttpResponse($e)
    {
        return CustomResponse::setFailResponse(Lang::get('errors.route.not_found'), Response::HTTP_NOT_FOUND);
    }
}
