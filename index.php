<?php

load(
    [
        "Plain\\Block" => "/lib/Block.php",
        "Plain\\BlockCollection" => "/lib/BlockCollection.php",
        "Plain\\BlockField" => "/lib/BlockField.php",
        "Plain\\BlockPreview" => "/lib/BlockPreview.php",
        "Plain\\BlocksCore" => "/lib/BlocksCore.php",
        "Plain\\BlocksField" => "/lib/BlocksField.php",
        "Plain\\Fieldset" => "/lib/Fieldset.php",
        "Plain\\Fieldsets" => "/lib/Fieldsets.php",
    ],
    __DIR__
);

use Kirby\Cms\App;
use Kirby\Cms\Blocks;
use Kirby\Cms\Page;
use Kirby\Content\Content;
use Plain\BlockField;
use Plain\BlocksField;
use Plain\ImagetogglesField;
use Plain\BlocksCore;
use Plain\Block;
use Plain\BlockCollection;
use Kirby\Exception\InvalidArgumentException;

function block(
    $blockName = null,
    content|array $data = [],
    ?array $extends = null,
    array $params = []
) {
    if (is_array($blockName)) {
        $data = $blockName["content"];
        $blockName = $blockName["type"];
    }

    if (is_array($data)) {
        $data = Block::parseForContent($data);
    }

    if ($data instanceof Content) {
        $params["parent"] = $data->parent();
        $data = $data->toArray();
    }

    if ($extends !== null) {
        $data = A::merge($data, Block::parseForContent($extends));
    }

    $params["type"] = $blockName;
    $params["content"] = $data;
    $params["extends"] = $extends;
    $params["parent"] ??= page();

    return new Block($params);
}

Kirby::plugin("plain/blocks-Suite", [
    "fields" => [
        "block" => BlockField::class,
        "blocks" => BlocksField::class,
    ],
    "snippets" => BlocksCore::snippets(),
    "blueprints" => BlocksCore::blueprints(),
    "fieldMethods" => [
        "toBlocks" => function (...$args) {
            return BlockCollection::parseField(...$args);
        },
        "toBlock" => function (...$args) {
            $blocks = BlockCollection::parseField(...$args);
            return $blocks;
        },
    ]
]);
