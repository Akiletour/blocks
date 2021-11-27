# Sage 9 Blocks and fields

Simply create a wrapper to your block and add the fields you need.

## Requirements

- Sage 9
- PHP <= 7.2
- Advanced Custom Fields

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

## Frontend

Create a new file into the resources/views/blocks folder.

```blade
<div class="{{ $block->classes }}">
    <h2>{{ $text }}</h2>
</div>
```
