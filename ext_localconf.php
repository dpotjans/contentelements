<?php

if(TYPO3_MODE === 'BE') {

    /* Connects FX\FxContentelements\InitController::installResources to the
     * ExtensionManagementService signal hasInstalledExtension which is called
     * after the extension has been installed.
     */

    $class = 'TYPO3\\CMS\\Extbase\\SignalSlot\\Dispatcher';
    $dispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance($class);
    $dispatcher->connect(
        'TYPO3\\CMS\\Extensionmanager\\Service\\ExtensionManagementService',
        'hasInstalledExtensions',
        'FX\\FxContentelements\\InitController',
        'installResources'
    );

 /*   $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['fx_contentelements_update']
        = \FX\FxContentelements\Update::class;
*/
}