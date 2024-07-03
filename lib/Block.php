<?php

namespace Plain;

/**
 * @package   Kirby Blocks Suite
 * @author    Roman Gsponer <kirby@plain-solutions.net>
 * @link      https://plain-solutions.net/
 * @copyright Roman Gsponer
 * @license   https://plain-solutions.net/license
 */

use Kirby\Cms\Block as KirbyBlock;
use Kirby\Form\FieldClass;
use Kirby\Toolkit\A;
use Plain\Blocks;

class Block extends KirbyBlock
{
    public const ITEMS_CLASS = BlockCollection::class;

    //protected $isSelected;
    //protected $isBlock = true;
    protected $controller;
    protected $extends;
    protected $subBlock;
    protected string $type = "";

    public function __construct(array $params = [])
    {
        //$this->isSelected = $params["isSelected"] ?? true;
        $this->extends = $params["extends"] ?? [];
        //$this->isBlock = BlocksCore::isBlock($params["type"] ?? "");
        $this->subBlock = BlocksCore::subBlock($params["type"] ?? "");

        parent::__construct($params);
    }

    public function __call(string $method, array $args = []): mixed
    {
        if ($this->hasMethod($method)) {
            return $this->callMethod($method, $args);
        }

        return $this->content()->get($method);
    }

    public function subBlock(): string
    {
        return $this->subBlock;
    }

    /*
    public function isSelected(): bool|null
    {
        return $this->isSelected;
    }

    public function isBlock(): bool
    {
        return $this->isBlock;
    }
    */

    public function controller(): array
    {
        $controller = parent::controller();

        /*
        if ($this->isBlock() === false) {
            $params = $controller["block"]->params;
            $params["type"] = $this->subBlock();
            $controller["block"] = KirbyBlock::factory($params);
            return $controller;
        }

        $controller["block"] = $this;
        */

        return $controller;
    }

    public function toHtml(array $extends = null): string
    {
        $snippet = "blocks/" . $this->type();
        $controller = $this->controller();

        if ($extends !== null) {
            $new_content = static::parseForContent($extends);
            $controller["content"] = $controller["content"]->update(
                $new_content
            );
        }

        $kirby = $this->parent()->kirby();

        return (string) $kirby->snippet($snippet, $controller, true);
    }

    public static function parseForContent($content)
    {
        foreach ($content as $key => $item) {
            $content[$key] = match (true) {
                $item instanceof \Kirby\Cms\File,
                $item instanceof \Kirby\Cms\Page => $item->id(),
                $item instanceof \Kirby\Cms\Files,
                $item instanceof \Kirby\Cms\Pages => $item->values(
                    fn($a) => $a->id()
                ),
                $item instanceof \Kirby\Content\Field => $item->value(),
                default => $item
            };
        }

        return $content;
    }
}
