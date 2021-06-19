<?php


namespace App\Serializer;


use App\Exception\FormException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class FormExceptionNormalizer implements NormalizerInterface
{
    /**
     * @param FormException $exception
     * @param null          $format
     * @param array         $context
     *
     * @return array
     */
    public function normalize($exception, $format = null, array $context = [])
    {
        $data   = [];
        $errors = $exception->getErrors();

        foreach ($errors as $error) {
            $data[$error->getOrigin()->getName()][] = $error->getMessage();
        }

        return $data;
    }

    /**
     * @param mixed $data
     * @param null  $format
     *
     * @return bool
     */
    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof FormException;
    }
}