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
use Kirby\Toolkit\I18n;
use Kirby\Toolkit\Str;
use Kirby\Cms\Fieldsets as KirbyFieldsets;
use Kirby\Cms\Blueprint;
use Kirby\Exception\InvalidArgumentException;

class Fieldsets extends KirbyFieldsets
{
    public const ITEM_CLASS = Fieldset::class;

    protected static array $tabs;

    // Fix confusion $id <-> $type
    protected static function createFieldsets(array $params): array
    {
        $fieldsets = [];
        $groups = [];
        $plain_fieldsets = [];

        foreach ($params as $id => $extends) {
            // Skip fieldset
            if ($extends === false) {
                continue;
            }

            $type = $extends["type"] ?? $id;

            if (is_string($extends)) {
                $type = $extends;
                $extends = [];
            }

            $id = is_int($id) ? $type : $id;

            //Make id for keys useable
            $id = Str::slug($id, "_");

            //Extract groups
            if ($type === "group") {
                $result = static::createFieldsets($extends["fieldsets"] ?? []);
                $fieldsets = array_merge($fieldsets, $result["fieldsets"]);
                $label = $extends["label"] ?? Str::ucfirst($type);

                $groups[$id] = [
                    "label" => I18n::translate($label, $label),
                    "name" => $id,
                    "open" => $extends["open"] ?? true,
                    "sets" => array_column($result["fieldsets"], "id"),
                ];
            } else {


                //Get fieldset
                $fieldset = Blueprint::extend("blocks/" . $type);

                //Be sure type is set
                $fieldset["type"] ??= $type;

                //We use id as identifire instead of type
                $fieldset["id"] = $id;

                //Make shure a label is given (cause tab title)
                $fieldset["label"] ??= $fieldset["title"] ?? $fieldset["name"] ?? Str::ucfirst($id);

                //Set position from extend
                if (array_key_exists('position', $extends)) {
                    $fieldset['position'] = $extends['position'];
                    unset($extends['position']);
                }

                //Set fieldset (for tab injection)
                $plain_fieldsets[$id] = $fieldset;

                //Move Fields to content tab
                $fieldset = static::parseTabs($fieldset);
                //Adding tabs
                if (empty(static::$tabs) === false) {
                    $fieldset = static::addTabs($fieldset, static::$tabs);
                }
                
                //Extend the fieldset
                if (empty($extends) === false) {
                    $extends = static::parseTabs($extends);
                    $fieldset = A::merge(
                        $fieldset,
                        $extends
                    );
                }


                $fieldsets[$id] = $fieldset;
            }
        }

        return [
            "fieldsets" => $fieldsets,
            "groups" => $groups,
            "plain_fieldsets" => $plain_fieldsets
        ];
    }

    public static function factory(
        array $items = null,
        array $params = []
    ): static {

        static::$tabs = empty($params['tabs'])
            ? []
            : static::createFieldsets($params['tabs'])['plain_fieldsets'];

        return parent::factory($items, $params);
    }

    protected static function parseTabs(array $fieldset): array
    {

        $tabs = $fieldset["tabs"] ?? [];

        // Put all field into content tab and add it BEFORE the tabs
        // Now you can use field AND tabs props together
        if (array_key_exists("fields", $fieldset)) {
            $fieldset["tabs"] = A::merge(
                [
                    "content" => [
                        "label" => $fieldset["title"] ?? 'Content',
                        "fields" => $fieldset["fields"],
                    ],
                ],
                $tabs ?? []
            );
            unset($fieldset['fields']);
        }

        return $fieldset;
    }

    protected static function addTabs(array $fieldset, array $toAdd): array
    {

        foreach ($toAdd as $id => $tab) {

            $tabs = $fieldset['tabs'] ?? [];

            if (array_key_exists('tabs', $tab)) {
                throw new InvalidArgumentException(
                    "Only fields are allowed in additional tabs. Tabs are given in '{$id}'"
                );
            }

            $fieldset['tabs'] = match ($tab["position"] ?? "") {
                //Add tab before
                "before" => A::merge([$id => $tab], $tabs),
                //Add tab after
                default => A::merge($tabs, [$id => $tab])
            };

        }

        return $fieldset;
    }

    public function findByKey(string $key)
    {
        // In case the fielset type is passed
        $key = Str::slug($key, "_");
        return $this->get($key);
    }
}
