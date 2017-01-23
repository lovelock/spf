<?php
/**
 * Created by PhpStorm.
 * User: Frost Wong <frostwong@gmail.com>
 * Date: 1/22/17
 * Time: 4:31 PM
 */

namespace App\Model\Table;


use Spw\Connection\Connection;

/**
 * @property Connection db
 */
class Aaa extends Base
{
    const TABLE = 'aaa';

    const COL_ID = 'id';
    const COL_NAME = 'name';

    public function fetchById($id)
    {
        return $this->db->from(self::TABLE)
            ->where([
                self::COL_ID => $id,
            ])
            ->select(self::COL_NAME);
    }
}