<?php

use MediaWiki\MediaWikiServices;
use Wikimedia\ParamValidator\ParamValidator;

class MappingInsertAPI extends ApiBase {
    public function execute(): void {
        $this->checkUserRightsAny( [ "review-admin" ] );

        $userID = $this->getParameter("user_id");
        $catID = $this->getParameter("cat_id");
        $service = MediaWikiServices::getInstance()->getService( "Review.MappingService" );
        $result["success"] = $service->insertNewRow( $userID, $catID ) ? "true" : "false";

        $this->getResult()->addValue( null, "response", $result );
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

    public final function getResultProperties(): array {
        return array(
            "success" => array(
                ApiBase::PROP_TYPE => 'string',
                ApiBase::PROP_NULLABLE => true
            )
        );
    }
}