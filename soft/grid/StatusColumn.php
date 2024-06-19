<?php

namespace soft\grid;

class StatusColumn extends DataColumn
{

    public $attribute = 'status';

    public $value = 'statusBadge';

    public $format = 'raw';

    public function init()
    {
        if ($this->filter === null) {



            $this->filter = [
                1 => 'Faol',
                0 => 'Nofaol',
            ];
        }
        parent::init();
    }

}
