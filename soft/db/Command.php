<?php

namespace soft\db;

use soft\helpers\SiteHelper;
use yii\db\Exception;

class Command extends \yii\db\Command
{

    public function execute()
    {
        try {
            return parent::execute();
        } catch (Exception $e) {
            if ($e->errorInfo[1] == 2006 || $e->errorInfo[1] == 2013) {
                $this->db->close();
                $this->log();
                $this->pdoStatement = null;
                $this->db->open();
                return parent::execute();
            }

            throw $e;
        }
    }

    protected function queryInternal($method, $fetchMode = null)
    {
        try {
            return parent::queryInternal($method, $fetchMode);
        } catch (Exception $e) {
            if ($e->errorInfo[1] == 2006 || $e->errorInfo[1] == 2013) {
                $this->db->close();
                $this->log();
                $this->pdoStatement = null;
                $this->db->open();
                return parent::queryInternal($method, $fetchMode);
            }

            throw $e;
        }
    }

    private function log(): void
    {
        SiteHelper::flashError(date("Y-m-d H:i:s") . ": MySQL server has gone away\n");
    }

}
