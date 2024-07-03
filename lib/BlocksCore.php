<?php

namespace Plain;

/**
 * @package   Kirby Blocks Suite
 * @author    Roman Gsponer <kirby@plain-solutions.net>
 * @link      https://plain-solutions.net/
 * @copyright Roman Gsponer
 * @license   https://plain-solutions.net/license
 */

use Kirby\Cms\App;
use Kirby\Cms\Blueprint;
use Kirby\Data\Data;
use Kirby\Exception\NotFoundException;
use Kirby\Filesystem\F;
use Kirby\Filesystem\Dir;
use Kirby\Toolkit\A;
use Kirby\Toolkit\Str;

class BlocksCore
{
    //protected $blocks;

    //public static $loaded = [];
    public static $root;

    static function snippets()
    {
        static::$root = option(
            "plain.blocks.root",
            App::instance()->root("site") . "/blocks"
        );
        $snippets = [];

        foreach (Dir::dirs(static::$root) as $super) {
            if (substr($super, 0, 1) === "_") {
                continue;
            }
            foreach (Dir::read(static::root($super)) as $file) {
                if (substr($file, 0, 1) === "_") {
                    continue;
                }
                if (F::extension($file) === "php") {
                    $name = F::name($file);
                    $key = $super === $name ? $name : $super . "/" . $name;
                    $file = static::root($super) . "/" . $file;
                    $snippets["blocks/" . $key] = $file;
                }
            }
        }

        return $snippets;
    }

    static function blueprints()
    {
        static::$root = option(
            "plain.blocks.root",
            App::instance()->root("site") . "/blocks"
        );
        $blueprints = [];

        foreach (Dir::dirs(static::$root) as $super) {
            if (substr($super, 0, 1) === "_") {
                continue;
            }
            foreach (Dir::read(static::root($super)) as $file) {
                if (substr($file, 0, 1) === "_") {
                    continue;
                }

                $name = F::name($file);

                if (F::extension($file) === "yml") {
                    $key = $super === $name ? $name : $super . "/" . $name;
                    $file = static::root($super) . "/" . $file;
                    $blueprints["blocks/" . $key] = $file;
                }
            }
        }

        return $blueprints;
    }

    static function saveName($name)
    {
        return Str::lower(Str::replace($name, "/", "__"));
    }

    static function convertSaveName()
    {
        return Str::lower(Str::replace($name, "__", "/"));
    }

    static function displayName($name)
    {
        $devined = Str::replace(static::subBlockName($name), "_", " ");
        return Str::ucwords($devined);
    }

    static function subBlock($name)
    {
        return A::last(Str::split($name, "/"));
    }

    //Obsolet
    /*
    static function load(string $type, array $extends = [])
    {
        if (isset($extends["type"])) {
            $type = $extends["type"];
        }

        if (($extends["type"] ?? $type) === "group") {
            return $extends;
        }

        //Get from Cache
        $hash = md5(json_encode($type) . json_encode($extends));
        if (isset(static::$loaded[$hash]) === true) {
            return static::$loaded[$hash];
        }

        if (substr($type, 0, 7) === "blocks/") {
            //Blocks are Blocks
            if (empty($extends)) {
                $extends = $type;
            }
            $fieldset = Blueprint::extend($extends);
            $fieldset["label"] ??= Str::ucfirst(substr($type, 7));
        } else {
            $file = static::file($type, "yml");
            $fieldset = A::merge(Data::read($file), $extends);
        }

        $fieldset["image"] = static::image($type);
        $fieldset["type"] = $type;

        return static::$loaded[$hash] = $fieldset;
    }
    */

    /*
    static function isBlock($type)
    {
        return true;
        $type_split = explode("/", $type, 2);
        return $type_split[0] !== "blocks";
    }

    static function image($block)
    {
        if (!str_contains($block, "/")) {
            $block = $block . "/" . $block;
        }

        foreach (["png", "jpg", "jpeg", "webp", "svg"] as $extension) {
            $exists = !is_null(static::file($block, $extension, false));
            if ($exists) {
                return $block . "." . $extension;
            }
        }
        return null;
    }

    */

    static function root($block = "")
    {
        return static::$root . "/" . $block;
    }

    static function file($block, $extension = "php", $required = true)
    {
        // resolve main block
        if (!str_contains($block, "/")) {
            $block = $block . "/" . $block;
        }

        $file = static::root($block) . "." . $extension;

        if (F::exists($file) === true) {
            return $file;
        }

        if ($required) {
            throw new NotFoundException("Block {$block} not found.");
        }

        return null;
    }
}
