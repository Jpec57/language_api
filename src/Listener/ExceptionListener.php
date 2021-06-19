<?php


namespace App\Listener;

use App\Entity\DTO\ApiResponse;
use App\Factory\NormalizerFactory;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class ExceptionListener
{
    /**
     * @var NormalizerFactory
     */
    private NormalizerFactory $normalizerFactory;

    /**
     * ExceptionListener constructor.
     *
     * @param NormalizerFactory $normalizerFactory
     */
    public function __construct(NormalizerFactory $normalizerFactory)
    {
        $this->normalizerFactory = $normalizerFactory;
    }


    /**
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        $request   = $event->getRequest();

        if (in_array('application/json', $request->getAcceptableContentTypes())) {
            $response = $this->createApiResponse($exception);
            $event->setResponse($response);
        }
    }

    /**
     * Creates the ApiResponse from any Exception
     *
     * @param \Throwable $throwable
     *
     * @return ApiResponse
     */
    private function createApiResponse(\Throwable $throwable): ApiResponse
    {
        $normalizer = $this->normalizerFactory->getNormalizer($throwable);
        $statusCode = $throwable instanceof HttpExceptionInterface ? $throwable->getStatusCode() : Response::HTTP_INTERNAL_SERVER_ERROR;
        try {
            $errors = $normalizer ? $normalizer->normalize($throwable) : [];
        } catch (\Exception | ExceptionInterface $e) {
            $errors = [];
        }
        return new ApiResponse($throwable->getMessage(), null, $errors, $statusCode);
    }
}