<?php

use MediaWiki\MediaWikiServices;
use Wikimedia\ParamValidator\ParamValidator;

class MappingDeleteAPI extends ApiBase {
    public function execute(): void {
        $this->checkUserRightsAny( [ "review-admin" ] );

        $userID = $this->getParameter("user_id");
        $catID = $this->getParameter("cat_id");
        $service = MediaWikiServices::getInstance()->getService( "Review.MappingService" );
        $service->deleteRow( $userID, $catID );
    }

    protected function getAllowedParams() {
        return array(
            "user_id" => array(
                ParamValidator::PARAM_TYPE => "integer",
                ParamValidator::PARAM_REQUIRED => true
            ),
            "cat_id" => array(
                ParamValidator::PARAM_TYPE => "integer",
                ParamValidator::PARAM_REQUIRED => true
            )
        );
    }
}