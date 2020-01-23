<?php

namespace FX\FxContentelements\ViewHelpers;

use FX\Services\ImageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class BackgroundImageViewHelper extends AbstractViewHelper {

    public function initializeArguments() {
        $this->registerArgument('image','string','Bildresource',true);
    }

    public static function renderStatic(array $arguments,\Closure $renderChildrenClosure,RenderingContextInterface $renderingContext) {
        if(!is_numeric($arguments['image']) || $arguments['image'] === 0){
            return;
        } else {
            $imgService = GeneralUtility::makeInstance(ImageService::class);
            return 'fileadmin/'.$imgService->getFalImageSrc($arguments['image']);
        }
    }
}