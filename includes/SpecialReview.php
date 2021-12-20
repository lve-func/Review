<?php

class SpecialReview extends SpecialPage
{
    public function __construct() {
        parent::__construct( 'Review' );
    }

    public function execute( $subPage ) {
        $this->setHeaders();
        $this->getOutput()->enableOOUI();
        $this->getOutput()->addHTML( '<div id = "special-page-content"></div>' );
        $this->getOutput()->addModules( 'ext.Review.specialPage' );
        $this->getOutput()->addModuleStyles( 'mediawiki.diff.styles' );
    }
}