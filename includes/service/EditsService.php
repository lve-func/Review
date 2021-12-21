<?php

use MediaWiki\MediaWikiServices;

class EditsService {
    public function handleNewEdit( $userID, $pageTitle, $pageText, $pageCategories, $parentPageID ) : bool {
        $lb = MediaWikiServices::getInstance()->getDBLoadBalancer();
        $dbr = $lb->getConnectionRef( DB_REPLICA );
        $rows = $dbr->select(
            "review_edit", [
            "id",
            "edit_status"
        ], [
            "user_id = " . $userID,
            "page_title = '" . $pageTitle . "'",
            "page_text = '" . $pageText . "'"
        ] );

        foreach ( $rows as $row ) {
            switch ( $row->status ) {
                // Such edit exists but it is already merged.
                case 0:
                    break;
                // Such edit exists and it is not merged.
                // That means user created non-unique edit.
                // No need to be inserting it as new one since
                // it is already on the list.
                case 1:
                    return false;
                // This edit is ready to be merged.
                // Update its status and don't intercept it.
                case 2:
                    $this->updateStatus( $row->id, 0 );
                    return false;
            }
        }

        $this->insertNew( $userID, $pageTitle, $pageText, $pageCategories, $parentPageID );

        return true;
    }

    private function insertNew( $userID, $pageTitle, $pageText, $pageCategories, $parentPageID ) : void {
        $lb = MediaWikiServices::getInstance()->getDBLoadBalancer();
        $dbw = $lb->getConnectionRef( DB_PRIMARY );

        $dbw->insert(
            "review_edit", [
            "user_id" => $userID,
            "page_title" => $pageTitle,
            "page_text" => $pageText,
            "edit_status" => 1
        ] );
    }

    private function updateStatus( $id, $newStatus ) : void {
        $lb = MediaWikiServices::getInstance()->getDBLoadBalancer();
        $dbw = $lb->getConnectionRef( DB_PRIMARY );

        $dbw->update( "review_edit", [
            "edit_status" => $newStatus
        ], "id = " . $id );
    }
}