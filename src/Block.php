<?php

namespace inRage\Blocks;

use Illuminate\Support\Str;
use WordPlate\Acf\Location;
use function App\asset_path;

abstract class Block extends Field
{
    public $title = '';
    public $description = '';
    public $icon = '';

    private function getBlockName(): string
    {
        return Str::snake(class_basename($this), '-');
    }

    public function registerBlockType()
    {
        if (!function_exists('acf_register_block_type')) {
            return;
        }
        acf_register_block_type(wp_parse_args($this, [
            'name' => $this->getBlockName(),
            'title' => $this->title,
            'description' => $this->description,
            'render_callback' => [$this, 'acfBlockRenderCallback'],
            'category' => 'theme',
            'icon' => $this->icon,
            'enqueue_style' => asset_path('styles/' . $this->getBlockName() . '.css'),
            'supports' => [
                'jsx' => true,
            ],
            'example' => [
                'attributes' => [
                    'mode' => 'preview',
                    'data' => [
                        'image_path' => asset_path('images/blocks-previews/' . $this->getBlockName() . '.jpg'),
                    ]
                ]
            ],
        ]));
    }

    protected function location(): Location
    {
        return Location::if('block', 'acf/' . $this->getBlockName());
    }

    public function acfBlockRenderCallback($block, $content, $is_preview)
    {
        if ($is_preview && isset($block['data']['image_path'])) {
            echo '<img class="img-fluid" src="' . $block['data']['image_path'] . '"
            alt="' . $this->description . '" />';
            return;
        }

        if (!\App\locate_template('views/blocks/' . $this->getBlockName() . '.blade.php')) {
            echo '<div style="padding: 6px 16px; background-color: #c0392b; color: #fff; line-height: 1.4em">
                    /!\ No template found for <strong>' . $this->getBlockName() . '</strong> block.<br />
                    You must create a <strong>views/blocks/' . $this->getBlockName() . '.blade.php</strong> file.
                  </div>';
            return;
        }

        echo \App\template(
            \App\locate_template('views/blocks/' . $this->getBlockName() . '.blade.php'),
            ['block' => $block]
        );
    }
}
