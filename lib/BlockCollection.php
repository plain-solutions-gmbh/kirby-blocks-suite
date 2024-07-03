<?php

namespace Plain;

/**
 * @package   Kirby Blocks Suite
 * @author    Roman Gsponer <kirby@plain-solutions.net>
 * @link      https://plain-solutions.net/
 * @copyright Roman Gsponer
 * @license   https://plain-solutions.net/license
 */

use Kirby\Cms\Blocks as KirbyBlocks;
use Kirby\Content\Field;
use Kirby\Toolkit\A;

class BlockCollection extends KirbyBlocks
{
    public const ITEM_CLASS = Block::class;

    public static function parse(array|string|null $input): array
    {
        $input = parent::parse($input);

        //Wrap single content array
        if (array_key_exists("content", $input)) {
            $input = [$input];
        }

        //Block field works with associative array. Make indexed
        return array_values($input);
    }

    public static function parseField(Field $field, $extends = [])
    {
        $items = static::parse($field->value());

        $items = static::extractFromLayouts($items);

        $params = [
            "parent" => $field->parent(),
            "field" => $field,
            "options" => [
                "extends" => $extends,
            ],
        ];

        if (empty($items) === true || is_array($items) === false) {
            return new static();
        }

        if (is_array($params) === false) {
            throw new InvalidArgumentException("Invalid item options");
        }

        // create a new collection of blocks
        $collection = new static([], $params);

        foreach ($items as $item) {
            if (is_array($item) === false) {
                throw new InvalidArgumentException(
                    "Invalid data for " . static::ITEM_CLASS
                );
            }

            $class = static::ITEM_CLASS;

            // inject properties from the parent
            $item["field"] = $collection->field();
            $item["options"] = $params["options"] ?? [];
            $item["parent"] = $collection->parent();
            $item["siblings"] = $collection;
            $item["params"] = $item;

            $item = $class::factory($item);
            $collection->append($item->id(), $item);
        }

        return $collection->filter("isHidden", false);
    }
}
