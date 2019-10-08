<?php
namespace Frontastic\Catwalk\TwigTasticBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Domain\Tastic;

class TasticExtension extends AbstractExtension
{
    private $context;

    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('classnames', [$this, 'classnames']),
            new TwigFunction('translate', [$this, 'translate']),
            new TwigFunction('completeTasticData', [$this, 'completeTasticData']),
        ];
    }

    /**
     * Mimics the bahaviour of https://www.npmjs.com/package/classnames but in
     * pure elegance 💃
     */
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

    /**
     * Mimics `paas/catwalk/src/js/app/configurationResolver.js`
     */
    public function completeTasticData(array $tastics, Tastic $tastic, object $data): array
    {
        $fields = $this->getFields($tastics, $tastic->tasticType);

        $values = [];
        foreach ($fields as $name => $field) {
            $values[$name] = $tastic->configuration->$name ?? $field->default;

            if ($field->type === 'stream') {
                $values[$name] = $data->stream->{$tastic->configuration->$name} ?? null;
            } elseif (isset($data->$name)) {
                $values[$name] = $data->$name;
            }

            if ($field->translatable) {
                $values[$name] = $this->translate($values[$name]);
            }
        }

        return $values;
    }

    protected function getFields(array $tastics, string $tasticType): array
    {
        $tasticSchema = null;
        foreach ($tastics as $tastic) {
            if ($tastic->tasticType === $tasticType) {
                $tasticSchema = $tastic->configurationSchema;
                break;
            }
        }

        if (!$tasticSchema) {
            return [];
        }

        $fields = [];
        foreach ($tasticSchema['schema'] as $tab) {
            foreach ($tab['fields'] as $field) {
                $fields[$field['field']] = (object) [
                    'default' => $field['default'] ?? null,
                    'type' => $field['type'],
                    'translatable' => $field['translatable'] ?? false,
                ];
            }
        }

        return $fields;
    }

    /**
     * Mimics `paas/libraries/common/src/js/translate.js`
     */
    public function translate($input): string
    {
        if (is_scalar($input)) {
            return (string) $input;
        }

        $input = (array) $input;
        if (isset($input[$this->context->locale])) {
            return (string) $input[$this->context->locale];
        }

        if (isset($input[$this->context->project->defaultLocale])) {
            return (string) $input[$this->context->project->defaultLocale];
        }

        return (string )reset($input) ?? '';
    }
}
