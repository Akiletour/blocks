<?php

namespace inRage\Blocks;

use WordPlate\Acf\Location;

abstract class Fields
{
    /**
     * @var string $title
     * @var string $layout
     * @var array $hide_on_screen
     */
    public $title = '';
    public $hide_on_screen = [];
    public $layout = '';

    public function __construct()
    {
        $fields = wp_parse_args($this, [
            'title' => '',
            'layout' => '',
            'fields' => $this->fields(),
            'location' => [$this->location()],
            'hide_on_screen' => '',
        ]);

        register_extended_field_group($fields);
    }

    abstract protected function fields(): array;

    abstract protected function location(): Location;
}
