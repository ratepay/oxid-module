<?php

/**
 *
 * Copyright (c) Ratepay GmbH
 *
 *For the full copyright and license information, please view the LICENSE
 *file that was distributed with this source code.
 */

class Pi_Util_Encryption_OxEncryption extends Pi_Util_Encryption_EncryptionAbstract
{

    protected function _insertBankdataToDatabase($insertSql)
    {
        $oDb = oxDb::getDb();
        $oDb->Execute($insertSql);
    }

    protected function _selectBankdataFromDatabase($selectSql)
    {
        $oDb = oxDb::getDb();
        $sqlResult = $oDb->getAssoc($selectSql);

        $bankdata = array();

        foreach ($sqlResult as $userId => $decryptedData) {
            $bankdata = array(
                'userid' => $userId,
                'owner' => $this->_convertHexToBinary($decryptedData[0]),
                'accountnumber' => $this->_convertHexToBinary($decryptedData[1]),
                'bankcode' => $this->_convertHexToBinary($decryptedData[2]),
            );
        }

        return $bankdata;
    }
    
    protected function _selectUserIdFromDatabase($userSql)
    {
        $oDb = oxDb::getDb();
        $userId = $oDb->getOne($userSql);

        return $userId;
    }

}
