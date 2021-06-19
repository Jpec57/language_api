<?php


namespace App\Factory;


use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class NormalizerFactory
{
    /**
     * @var NormalizerInterface[]
     */
    private iterable $normalizers;

    /**
     * NormalizerFactory constructor.
     *
     * @param iterable $normalizers
     */
    public function __construct(iterable $normalizers)
    {
        $this->normalizers = $normalizers;
    }

    /**
     * Returns the normalizer by supported data.
     *
     * @param mixed $data
     *
     * @return NormalizerInterface|null
     */
    public function getNormalizer(mixed $data): ?NormalizerInterface
    {
        foreach ($this->normalizers as $normalizer) {
            if ($normalizer instanceof NormalizerInterface && $normalizer->supportsNormalization($data)) {
                return $normalizer;
            }
        }

        return null;
    }
}