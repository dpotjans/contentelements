<?php
namespace FX\Services;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;

class ImageService {
    public function getFalImageSrc(int $uid) : String {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('sys_file_reference');
        $uid_local = (int) $queryBuilder->select('uid_local')
            ->from('sys_file_reference')
            ->setMaxResults(1)
            ->where($queryBuilder->expr()->eq('uid',$queryBuilder->createNamedParameter($uid, \PDO::PARAM_INT)))
            ->execute()->fetchColumn(0);

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('sys_file');
        return (string) $queryBuilder->select('identifier')
            ->from('sys_file')
            ->setMaxResults(1)
            ->where($queryBuilder->expr()->eq('uid',$queryBuilder->createNamedParameter($uid_local, \PDO::PARAM_INT)))
            ->execute()->fetchColumn(0);

    }

    public function getFalImage(int $uid, String $tablename) {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('sys_file_reference');
        return $queryBuilder->select('uid')->from('sys_file_reference')
            ->where($queryBuilder->expr()->eq('tablenames',$queryBuilder->createNamedParameter($tablename, \PDO::PARAM_STR)))
            ->andWhere($queryBuilder->expr()->eq('uid_foreign',$queryBuilder->createNamedParameter($uid, \PDO::PARAM_INT)))
            ->execute()->fetchAll();
    }
}