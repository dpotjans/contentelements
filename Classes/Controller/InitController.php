<?php

namespace FX\FxContentelements;

/**
 * (c) 2019 FXFlat Wertpapierhandelsbank GmbH <dominik.potjans@fxflat.com>
 * All rights reserved
 *
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Localization\Exception\FileNotFoundException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\DebugUtility;

class InitController {

    private $fceConfPath;
    private $configurationPath;
    private $typoScriptPath;
    private $fceList;
    private $tbl = 'tx_gridelements_backend_layout';

    public function installResources(){

        $this->getPaths();
        $this->getAvailableFCEs();

        foreach($this->fceList as $fce){
            $this->installFCE($fce);
        }

    }

    private function getAvailableFCEs(){
        $this->fceList = '';
        $files = scandir($this->fceConfPath);
        $regExp = '/^(fce-)(.*)(.json)/m';
        $matches = array();
        foreach($files as $file){
            if(preg_match($regExp, $file)){
                $matches[] = $file;
            };
        }
        $this->fceList = $matches;
    }

    private function getPaths(){
        $this->configurationPath = Environment::getPublicPath().'/typo3conf/ext/fx_contentelements/Configuration/';
        $this->fceConfPath = $this->configurationPath.'Json/';
        $this->typoScriptPath = $this->configurationPath.'TypoScript/';
    }

    private function installFCE($fce){

        //TODO: fix FCE-icons fce-config json-file (database entry)

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($this->tbl);
        $file = $this->fceConfPath.$fce;
        $conf = json_decode(file_get_contents($file),true);

        $values = [
            'pid' => 0,
            'tstamp' => time(),
            'crdate' => time(),
            'cruser_id' => 1,
            'title' => $conf['title'],
            'description' => $conf['description'],
            'config' => $conf['config'],
            'pi_flexform_ds_file' => $conf['pi_flexform_ds_file'],
            'icon' => $conf['icon']
        ];

        //Avoid duplicates
        $count = $queryBuilder->count('title')
                ->from($this->tbl)
                ->where($queryBuilder->expr()->eq('title', $queryBuilder->createNamedParameter($conf['title'], \PDO::PARAM_STR))
                )->execute()->fetchColumn(0);

        if($count === 0){
            $queryBuilder->insert($this->tbl)->values($values)->execute();
            $this->createTypoScript($fce,$queryBuilder->getConnection()->lastInsertId());
        }
    }

    private function createTypoScript($fce,$id){
        $filePointer = $this->typoScriptPath.'gridelements-fce.typoscript';
        $templateFile = str_replace('json','html',$fce);

        $tsSnippet  = "\ntt_content.gridelements_pi1.20.10.setup.".$id." < lib.gridelements.defaultGridSetup\n";
        $tsSnippet .= "tt_content.gridelements_pi1.20.10.setup.".$id." {\n\t cObject = FLUIDTEMPLATE\n";
        $tsSnippet .= "\tcObject.file = EXT:fx_contentelements/Resources/Private/Templates/".ucfirst($templateFile)."\n}";

        if(file_exists($filePointer)) {
            $handle = fopen($filePointer,'a');
            fwrite($handle, $tsSnippet);
            fclose($handle);
        } else {
            throw new FileNotFoundException('TypoScript-File: '.$filePointer.' not found');
        }
    }
}
