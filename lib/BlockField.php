<?php

namespace Plain;

/**
 * @package   Kirby Blocks Suite
 * @author    Roman Gsponer <kirby@plain-solutions.net>
 * @link      https://plain-solutions.net/
 * @copyright Roman Gsponer
 * @license   https://plain-solutions.net/license
 */

//use Kirby\Form\Field\BlocksField as KirbyBlocksField;
//use Kirby\Form\Field as KirbyField;
// use Kirby\Cms\Block as KirbyBlock;
use Kirby\Toolkit\A;
use Kirby\Toolkit\Str;
use Kirby\Data\Json;
use Kirby\Form\FieldClass;
use Kirby\Form\Form;

// use Kirby\Toolkit\Str;
use Kirby\Cms\ModelWithContent;
use Kirby\Exception\InvalidArgumentException;

use Kirby\Exception\NotFoundException;

class BlockField extends FieldClass
{
    protected Fieldset $fieldset;
    protected bool $pretty;
    protected mixed $value = [];

    public function __construct(array $params = [])
    {
        if (array_key_exists("fieldset", $params) === false) {
            throw new NotFoundException("The fieldset props not set.");
        }

        $this->setFieldset($params["fieldset"], [
            "parent" => $params["model"] ?? App::instance()->site(),
            "tabs" => $params["tabs"] ?? [],
            "fields" => $params["fields"] ?? [],
        ]);

        parent::__construct($params);

        $this->setPretty($params["pretty"] ?? false);
    }

    public function fieldset(): Fieldset
    {
        return $this->fieldset;
    }

    public function fields(): array
    {
        return $this->fieldset()->fields();
    }

    public function blockType(): string
    {
        return $this->fieldset->id();
    }

    public function fill(mixed $value = null): void
    {
        //Read from field. Extract content
        if (is_array($value) === false) {
            $value = Json::decode((string) $value);
            $value = $value["content"] ?? [];
        }

        $values = $this->form($value)->values();

        $this->value = $values;
    }

    public function pretty(): bool
    {
        return $this->pretty;
    }

    public function props(): array
    {
        return [
            "tabs" => $this->tabs(),
            "fieldset" => $this->fieldset()->toArray(),
        ] + parent::props();
    }

    public function routes(): array
    {
        $field = $this;

        return [
            [
                "pattern" => "fields/(:any)/(:all?)",
                "method" => "ALL",
                "action" => function (
                    string $fieldName,
                    string $path = null
                ) use ($field) {
                    $field = $field->form()->field($fieldName);

                    $fieldApi = $this->clone([
                        "routes" => $field->api(),
                        "data" => array_merge($this->data(), [
                            "field" => $field,
                        ]),
                    ]);

                    return $fieldApi->call(
                        $path,
                        $this->requestMethod(),
                        $this->requestData()
                    );
                },
            ],
        ];
    }

    public function store(mixed $value): mixed
    {
        if (empty($value) === true) {
            return "";
        }

        $content = $this->form((array) $value)->content();

        $value = [
            "type" => $this->blockType(),
            "content" => $content,
        ];

        return $this->valueToJson($value, $this->pretty());
    }

    public function label(): string
    {
        //Assume label prop is not set -> Take fieldset name
        if (Str::ucfirst($this->name) === $this->label) {
            return $this->fieldset()->name();
        }
        return parent::label();
    }

    public function tabs(): array
    {
        $fieldset = $this->fieldset();
        return $fieldset->tabs();
    }

    public function form(array $value = []): Form
    {
        return new Form([
            "fields" => $this->fields(),
            "model" => $this->model,
            "strict" => true,
            "values" => $value,
        ]);
    }

    protected function setPretty(bool $pretty = false): void
    {
        $this->pretty = $pretty;
    }

    protected function setFieldset(string|array $fieldset, array $params): void
    {
        $fieldsets = Fieldsets::factory(A::wrap($fieldset), $params);

        $this->fieldset = $fieldsets->first();
    }

    public function validations(): array
    {
        return [
            "block" => function ($value) {
                $form = $this->form($value);
                $fieldset = $this->fieldset();

                foreach ($form->fields() as $field) {
                    $errors = $field->errors();

                    // rough first validation
                    if (empty($errors) === false) {
                        //Todo: Do better exception (without block definition)
                        throw new InvalidArgumentException([
                            "key" => "blocks.validation",
                            "data" => [
                                "field" => $field->label(),
                                "fieldset" => $fieldset->name(),
                            ],
                        ]);
                    }
                }
            },
        ];
    }
}
