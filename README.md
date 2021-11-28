# Sage 9 Blocks and fields

Simply create a wrapper to your block and add the fields you need.

## Requirements

- Sage 9
- PHP <= 7.2
- Advanced Custom Fields

## Custom Fields

All the management of custom fields is driven by WordPlate ACF.

https://github.com/wordplate/extended-acf

## Installation

```bash
composer require inrage/blocks
```

### Create a block

First, create a new folder in your theme directory.

```
mkdir app/Blocks
```

And then create a new file Testing.php in that folder.

```php
<?php

namespace App\Blocks;

use inRage\Blocks\Block;
use WordPlate\Acf\Fields\Text;

class Testing extends Block
{
    public $title = 'Testing';
    public $description = 'Testing block';
    public $icon = 'format-image';

    protected function fields(): array
    {
        return [
            Text::make('Text', 'text')->required()
        ];
    }
}
```

### View

Create a new file into the resources/views/blocks folder.

```blade
<div class="{{ $block->classes }}">
    <h2>{{ $text }}</h2>
</div>
```

## Fields per view

If you need to use custom fields per page, you can do it directly with this package.

### Structure

To use this feature, you must create a subdirectory in the "app" directory, it must be named **Fields**.

Be careful, the name of your file/class must not be the same as the controllers.

We suggest you to use FrontPageField for example.

```php
<?php

namespace App\Fields;

use inRage\Blocks\Field;
use WordPlate\Acf\Fields\Text;
use WordPlate\Acf\Location;

class FrontpageFields extends Field
{
    public $title = 'Homepage management';

    protected function fields(): array
    {
        return [
            Text::make('title')->required()
        ];
    }

    protected function location(): Location
    {
        return Location::if('page_type', 'front_page');
    }
}
```
