<?php

namespace Plain;

/**
 * @package   Kirby Blocks Suite
 * @author    Roman Gsponer <kirby@plain-solutions.net>
 * @link      https://plain-solutions.net/
 * @copyright Roman Gsponer
 * @license   https://plain-solutions.net/license
 */

use Kirby\Cms\Fieldset as KirbyFieldset;
use Kirby\Exception\InvalidArgumentException;
use Kirby\Cms\Blueprint;
use Kirby\Form\Form;
use Kirby\Toolkit\Str;
use Kirby\Toolkit\A;

class Fieldset extends KirbyFieldset
{
    public const ITEMS_CLASS = Fieldsets::class;

    // Use previewObj instead of $preview cause preview models are protectec by parent
    protected blockPreview $previewObj;

    /**
     * There is a confusion $name <-> $type
     *
     * That's why we can't use a fieldset multiple times.
     * And chars like '/' is ugly inside keys.
     *
     */

    public function __construct(array $params = [])
    {
        // ToDo: Check, in Create Tabs:
        // $this->fields = $params["fields"] ?? [];
        $params["id"] ??= $params["type"];

        // Reset preview
        $preview = $params["preview"] ?? null;
        $params["preview"] = null;

        parent::__construct($params);

        $this->id = $params["id"] ?? $params["type"];

        $this->previewObj = new blockPreview(
            $preview,
            $this->fields(),
            $this->tabs()
        );

        // Compatibility with original blocks preview
        $this->preview = $this->previewObj->template();

        //All fields are in preview -> disable edit button
        if ($preview === true) {
            $this->editable = false;
        }

        // Prevent from open drawer is preview
        if (
            $this->previewObj->hasPreview() &&
            array_key_exists("wysiwyg", $params) === false
        ) {
            $this->wysiwyg = true;
        }
    }

    protected function createFields(array $fields = []): array
    {
        //Check for conflicts
        $conflicts = array_intersect_key($this->fields, $fields);
        if ($conflicts) {
            $list = implode(", ", array_keys($conflicts));
            throw new InvalidArgumentException(
                "Multi use of field(s) '{$list}' in '" .
                    $this->name .
                    "' tab not allowed."
            );
        }
        return parent::createFields($fields);
    }

    public function previewObj(): blockPreview
    {
        return $this->previewObj;
    }

    public function toArray(): array
    {
        return parent::toArray() + [
            "previewObj" => $this->previewObj()->toArray(),
            // Use id as identifier in blocks
            "id" => $this->id(),
        ];
    }
}
