<?php

use MediaWiki\MediaWikiServices;

class MappingService {
    public function getReviewersByCat( $catID ) {
        $lb = MediaWikiServices::getInstance()->getDBLoadBalancer();
        $dbr = $lb->getConnectionRef( DB_REPLICA );

        $rows = $dbr->select(
            "review_mapping", [
                "id",
                "user_id"
        ], [
            "cat_id = " . $catID
        ] );

        $result = [];

        foreach( $rows as $row ) {
            array_push( $result, [
                "id" => $row->id,
                "user_id" => $row->user_id
            ] );
        }

        return $result;
    }

    public function insertNewRow( $userID, $catID ) : bool {
        $lb = MediaWikiServices::getInstance()->getDBLoadBalancer();
        $dbr = $lb->getConnectionRef( DB_REPLICA );
        $dbw = $lb->getConnectionRef( DB_PRIMARY );

        $rows = $dbr->select(
            "review_mapping", [
                "id"
        ], [
            "user_id = " . $userID,
            "cat_id = " . $catID
        ] );

        // Failed since such entry is not unique
        if ( count( $rows ) !== 0 )
            return false;

        $dbw->insert(
            "review_mapping", [
                "user_id" => $userID,
                "cat_id" => $catID
        ] );

        return true;
    }

    public function deleteRow( $userID, $catID ) : void {
        $lb = MediaWikiServices::getInstance()->getDBLoadBalancer();
        $dbw = $lb->getConnectionRef( DB_PRIMARY );

        $dbw->delete(
            "review_mapping", [
                "user_id = " . $userID,
                "cat_id = " . $catID
            ]
        );
    }
}