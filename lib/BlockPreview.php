<?php

namespace Plain;

/**
 * @package   Kirby Blocks Suite
 * @author    Roman Gsponer <kirby@plain-solutions.net>
 * @link      https://plain-solutions.net/
 * @copyright Roman Gsponer
 * @license   https://plain-solutions.net/license
 */

use Kirby\Toolkit\A;
use Kirby\Toolkit\Str;
use Kirby\Exception\InvalidArgumentException;
use Kirby\Form\Field;

class blockPreview
{
    protected mixed $params;
    protected string|null $template;
    protected array $fields = [];
    protected string|null $tab = null;
    protected array|null $name;
    protected array|null $label;
    protected array|null $icon;
    protected array|null $image = null;
    protected array|null $width = null;

    public function __construct(mixed $params, array $fields, array $tabs)
    {
        // Set all fields for preview
        if ($params === true) {
            $params = ["fields" => array_keys($fields)];
        }

        // Only set preview template
        if (is_array($params) === false) {
            $params = ["template" => $params];
        }

        $this->params = $params;

        $this->template = $params["template"] ?? null;

        $this->name = $this->wrap(
            $params["name"] ?? ($params["title"] ?? null)
        );
        $this->label = $this->wrap($params["label"] ?? null);
        $this->icon = $this->wrap($params["icon"] ?? null);
        $this->setImage($params["image"] ?? null);
        $this->setWidth($params["width"] ?? null);

        // Set preview tabs
        if (array_key_exists("tab", $params)) {
            $this->setTab($params["tab"], $tabs);
            return $this;
        }

        $this->setFields(
            $params["fields"] ?? ($params["field"] ?? []),
            $fields
        );

        return $this;
    }

    private function setFields(array|string $previewFields, array $fields): void
    {
        foreach (A::wrap($previewFields) as $key) {
            $this->fields[$key] = match (array_key_exists($key, $fields)) {
                true => $fields[$key],
                default => [
                    "type" => "info",
                    "theme" => "negative",
                    "text" => "Preview field '{$key}' is not present.",
                ]
            };
        }
    }

    private function setTab($tabName, array $tabs): void
    {
        if ($tabName === true) {
            $tabName = "content";
        }

        $tab = $tabs[$tabName] ?? null;

        if ($tab !== null) {
            $this->tab = $tabName;
            $this->fields = $tab["fields"];
            return;
        }

        throw new InvalidArgumentException(
            "The preview tab '{$tabName}' is not present."
        );
    }

    private function setWidth(string|array|null $params): void
    {
        $params = $this->wrap($params);

        if ($params !== null) {
            if (array_key_exists("options", $params)) {
                $params["format"] ??= "options";
            }

            $params["format"] ??= "columns";
        }

        $this->width = $params;
    }

    private function setImage(string|array|null $params): void
    {
        $params = $this->wrap($params);

        if ($params !== null) {
            $params["fit"] ??= "cover";
            $params["ratio"] ??= "1/1";
            $params["back"] ??= "pattern";
        }

        $this->image = $params;
    }

    private function wrap(string|array|null $params): array|null
    {
        if ($params === null) {
            return null;
        }
        if (is_array($params) === false) {
            return ["field" => $params];
        }
        return $params;
    }

    public function template(): string|null
    {
        if ($this->hasPreview()) {
            return "fields";
        }
        return $this->template;
    }

    public function tab(): string|null
    {
        return $this->tab;
    }

    public function fields(): array
    {
        return $this->fields;
    }

    public function name(): array|null
    {
        return $this->name;
    }

    public function width(): array|null
    {
        return $this->width;
    }

    public function label(): array|null
    {
        return $this->label;
    }

    public function image(): array|null
    {
        return $this->image;
    }

    public function icon(): array|null
    {
        return $this->icon;
    }

    public function hasPreview(): bool
    {
        return count($this->fields) > 0;
    }

    public function toArray(): array
    {
        return [
            "template" => $this->template(),
            "tab" => $this->tab(),
            "fields" => $this->fields(),
            "name" => $this->name(),
            "label" => $this->label(),
            "image" => $this->image(),
            "icon" => $this->icon(),
            "width" => $this->width(),
        ];
    }
}
