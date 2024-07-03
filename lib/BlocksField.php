<?php

namespace Plain;

/**
 * @package   Kirby Blocks Suite
 * @author    Roman Gsponer <kirby@plain-solutions.net>
 * @link      https://plain-solutions.net/
 * @copyright Roman Gsponer
 * @license   https://plain-solutions.net/license
 */

use Kirby\Form\Field;
use Kirby\Form\Form;
use Kirby\Form\Field\BlocksField as KirbyBlocksField;
use Kirby\Cms\ModelWithContent;
use Kirby\Exception\InvalidArgumentException;
use Kirby\Toolkit\A;
use Kirby\Cms\App;
use Kirby\Toolkit\Str;

class BlocksField extends KirbyBlocksField
{
    protected array $tabs = [];

    public function __construct(array $params = [])
    {
        $this->params = $params;

        //Inject Tabs
        $this->tabs = A::wrap($params["tabs"] ?? []);

        parent::__construct($params);
    }

    public function blocks(): array
    {
        return array_keys($this->fieldsets()->toArray());
    }

    //Split blocksToValue. Now we can use it in router
    public function blockToValue($block, $to = "values"): array
    {
        $fieldsetName = $block["fieldset"] ?? $block["type"];
        $fieldset = $this->fieldset($fieldsetName);
        $fields = $fieldset->fields();
        $content = $block["content"] ?? null;

        // Get defaults from fields
        $content ??= A::map($fields, fn($a) => $a["default"] ?? "");

        // Clean and fill values
        $content = $this->form($fields, $content)->$to();

        return [
            "content" => $content,
            "type" => $fieldset->type(),
            "fieldset" => $fieldsetName,
            "id" => $block["id"] ?? Str::uuid(),
            "isHidden" => $block["isHidden"] ?? false,
        ];
    }

    public function blocksToValues($blocks, $to = "values"): array
    {
        $result = [];
        $fields = [];

        foreach ($blocks as $index => $block) {
            $block = $this->blockToValue($block, $to);

            if ($block !== null) {
                $result[$index] = $block;
            }
        }

        return $result;
    }

    public function pasteBlocks(array $blocks): array
    {
        $blocks = $this->blocksToValues($blocks);

        foreach ($blocks as $index => &$block) {
            $block["id"] = Str::uuid();

            try {
                // Try to get fieldset by fieldset name
                $this->fieldset($block["fieldset"] ?? $block["type"]);
            } catch (Throwable) {
                unset($blocks[$index]);
            }
        }

        return array_values($blocks);
    }

    public function routes(): array
    {
        $field = $this;

        $routes = parent::routes();

        //Override Route 'fieldsets/(:any)'
        $routes[2]["action"] = function (string $fieldsetName) use (
            $field
        ): array {
            // @KirbyCore: Why BlockFields class call Block::factory here?
            return $field->blockToValue([
                "fieldset" => $fieldsetName,
            ]);
        };

        return $routes;
    }

    public function fill($value = null): void
    {
        // Prevent from walking through all the parsing processes
        if (empty($value ?? "")) {
            $this->value = [];
            return;
        }

        $value = BlockCollection::parse($value);
        $value = array_values($value);

        $this->value = $this->blocksToValues($value);
    }

    protected function setFieldsets(
        string|array|null $fieldsets,
        ModelWithContent $model
    ): void {
        if (empty($fieldsets)) {
            throw new InvalidArgumentException("Missing block definition");
        }
        $fieldsets = A::wrap($fieldsets);

        $fieldsets = Fieldsets::factory($fieldsets, [
            "parent" => $model,
            "tabs" => $this->tabs,
        ]);

        $this->fieldsets = $fieldsets;
    }

    protected function setBlocks($blocks)
    {
        $this->blocks = A::wrap($blocks);
    }
}
