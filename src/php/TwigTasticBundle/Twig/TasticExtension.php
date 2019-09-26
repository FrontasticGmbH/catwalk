<?php
namespace Frontastic\Catwalk\TwigTasticBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TasticExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('classnames', [$this, 'classnames']),
            new TwigFunction('completeTasticData', [$this, 'completeTasticData']),
        ];
    }

    public function classnames(): string
    {
        return join(
            ' ',
            array_unique(
                array_map(
                    'trim',
                    array_keys(
                        array_filter(
                            array_merge(
                                ...array_map(
                                    function ($argument): array {
                                        if (!$argument) {
                                            return [];
                                        }

                                        if (!is_array($argument)) {
                                            return [$argument => true];
                                        }

                                        $classnames = [];
                                        foreach ($argument as $key => $value) {
                                            if (is_string($key)) {
                                                $classnames[$key] = (bool) $value;
                                            } else {
                                                $classnames[$value] = true;
                                            }
                                        }
                                        return $classnames;
                                    },
                                    func_get_args()
                                )
                            )
                        )
                    )
                )
            )
        );
    }
}
