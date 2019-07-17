<?php

namespace App\Core;

interface DBInterface
{

    /**
     * Select data from tables.
     *
     * @param array $fields
     * @param array $tables
     * @param array|null $conditions
     * @param array|null $groups
     * @param array|null $orders
     * @param array|null $limit
     * @return array
     * @throws Exception
     */
    public function select(array $fields, array $tables, array $conditions = null, array $groups = null, array $orders = null, array $limit = null);

    // /**
    //  * Insert.
    //  *
    //  * @param array $tables
    //  * @param array $values
    //  * @param string $sequenceName (optional)
    //  * @return integer|boolean Inserted record Id.
    //  * @throws Exception
    //  */
    public function insert(array $tables, array $values, $sequenceName=null);
    //
    // /**
    //  * Update.
    //  *
    //  * @param array $tables
    //  * @param array $values
    //  * @param array $conditions (optional)
    //  * @return integer Affected records.
    //  * @throws Exception
    //  */
    public function update(array $tables, array $values, array $conditions = null);
    //
    // /**
    //  * Delete.
    //  *
    //  * @param array $tables
    //  * @param array $conditions (optional)
    //  * @return integer Affected records.
    //  * @throws Exception
    //  */
    public function delete(array $tables, array $conditions = null);
    //
    // /**
    //  * Raw Query
    //  *
    //  * @param $query
    //  * @return bool|mysqli_result
    //  */
    public function raw($mysql);
}
