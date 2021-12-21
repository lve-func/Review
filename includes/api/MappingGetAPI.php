<?php

use MediaWiki\MediaWikiServices;
use Wikimedia\ParamValidator\ParamValidator;

class MappingGetAPI extends ApiBase {
    public function execute(): void {
        $this->checkUserRightsAny( [ "review-admin" ] );

        $catID = $this->getParameter("cat_id");
        $service = MediaWikiServices::getInstance()->getService( "Review.MappingService" );
        $result["data"] = $service->getReviewersByCat( $catID );

        $this->getResult()->addValue( null, "response", $result );
    }

    protected function getAllowedParams() {
        return array(
            "cat_id" => array(
                ParamValidator::PARAM_TYPE => "integer",
                ParamValidator::PARAM_REQUIRED => true
            )
        );
    }

    public final function getResultProperties(): array {
        return array(
            "data" => array()
        );
    }
}