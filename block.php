<?php

namespace inRage\Blocks;

/**
 * Sage Namespace
 */
function sage()
{
    // Determine if project namespace has been changed
    $sage = apply_filters('inrage/blocks/sage/namespace', 'App') . '\sage';

    // Return the function if it exists
    if (function_exists($sage)) {
        return $sage;
    }

    // Return false if function does not exist
    return false;
}

/**
 * Loader
 */
function loader()
{
    // Get Sage function
    $sage = sage();

    // Return if function does not exist
    if (!$sage) {
        return;
    }

    $loader = new Loader('Block');
    $container = $sage();

    foreach ($loader->getClassesToRun() as $class) {
        $block = $container->make($class);

        $block->registerBlockType();
    }
}

function fieldLoader()
{
    // Get Sage function
    $sage = sage();

    // Return if function does not exist
    if (!$sage) {
        return;
    }

    $loader = new Loader('Field');
    $container = $sage();

    foreach ($loader->getClassesToRun() as $class) {
        $container->make($class);
    }
}

/**
 * Hooks
 */
if (function_exists('add_action')) {
    add_action('init', __NAMESPACE__ . '\loader');
    add_action('init', __NAMESPACE__ . '\fieldLoader');
}
