<@php

namespace {namespace};

use App\Repositories\BaseRepository;
<?php if ($model) { ?>
use {useModel};

<?php } ?>
class {class} extends BaseRepository implements {implements}
{
<?php if ($model) {?>
    public function __construct()
    {
        parent::__construct({nameModel}::class);
    }
<?php } ?>
}
