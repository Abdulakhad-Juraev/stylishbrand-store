<?php

namespace common\modules\tempUser\query;

use common\modules\tempUser\models\TempUser;

/**
 * This is the ActiveQuery class for [[\common\modules\auth\models\TempUser]].
 *
 * @see TempUser
 */
class TempUserQuery extends \soft\db\ActiveQuery
{

    /**
     * {@inheritdoc}
     * @return TempUser[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
