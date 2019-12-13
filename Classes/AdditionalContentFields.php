<?php

/***************************************************************
 *  Copyright notice
 *  (c) 2019 Gregor Agnes (zwo3.de) <ga@zwo3.de>
 *  All rights reserved
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/


namespace Zwo3\MaskKesearchIndexer;

use mysql_xdevapi\DatabaseObject;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Core\Database\Query\Restriction\HiddenRestriction;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

class AdditionalContentFields
{

    public function modifyPageContentFields(&$fields, $pageIndexer)
    {
        // Add the field "subheader" from the tt_content table, which is normally not indexed, to the list of fields.
        $fields .= ",subheader";
    }

    public function modifyContentFromContentElement(string &$bodytext, array $ttContentRow, $pageIndexer)
    {
        // Add the content of the field "subheader" to $bodytext, which is, what will be saved to the index.
        $bodytext .= strip_tags($ttContentRow['subheader']);
    }

    private function getMaskFieldFromTtContent()
    {
        $link = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('sys_log');

        DebuggerUtility::var_dump(GeneralUtility::getIndpEnv($link->getDatabase())); exit;
        $sql = "SELECT COLUMN_NAME
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE table_name = 'tt_content'
    AND table_schema = 'YourDB'
    AND column_name LIKE 'tx_mask_%'";

        $statement = $link->prepare($sql);
        $statement->execute();
    }
}