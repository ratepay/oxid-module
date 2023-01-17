<?php

/**
 *
 * Copyright (c) Ratepay GmbH
 *
 *For the full copyright and license information, please view the LICENSE
 *file that was distributed with this source code.
 */

/**
 * Generate iterable list of pi_ratepay_logs model objects
 * @extends oxList
 */
class pi_ratepay_LogsList extends oxList
{

    /**
     * Core table name
     *
     * @var string
     */
    protected $_sCoreTable = 'pi_ratepay_logs';

    /**
     * List Object class name
     *
     * @var string
     */
    protected $_sObjectsInListName = 'pi_ratepay_Logs';

    /**
     * Generic function for loading the list with where clause and order by
     *
     * @param string $where
     * @param array $orderBy example: array(array('column' => 'order_id', 'direction' => 'asc'))
     * @return pi_ratepay_LogsList
     */
    public function getFilteredList($where = null, $orderBy = null)
    {
        $listObject = $this->getBaseObject();
        $fieldList = $listObject->getSelectFields();
        $query = "select $fieldList from " . $listObject->getViewName();

        if ($where !== null) {
            $query .= " where $where ";
        }

        if ($orderBy !== null) {
            $lastArrayItem = end($orderBy);
            $addition = ', ';

            $query .= ' order by ';

            foreach ($orderBy as $orderByItem) {
                if ($orderByItem == $lastArrayItem)
                    $addition = '';
                $query .= $orderByItem['column'] . ' ' . $orderByItem['direction'] . $addition;
            }
        }

        $this->selectString($query);

        return $this;
    }

}
