<?php

if(TYPO3_MODE === 'BE') {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('fx_contentelements', 'Configuration/TypoScript', 'FXFlat Contentelements');
    //$GLOBALS['TBE_STYLES']['skins']['fx_contentelemenets']['stylesheetDirectories'] = ['EXT:fx_contentelements/Resources/Public/Css/BE'];
}