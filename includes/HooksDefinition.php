<?php

use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;

class HooksDefinition {
    public function onMultiContentSave( MediaWiki\Revision\RenderedRevision $renderedRevision,
                                        MediaWiki\User\UserIdentity $user, CommentStoreComment $summary, $flag,
                                        Status $hookStatus ) {
        $revision = $renderedRevision->getRevision();
        $editNS = $revision->getPage()->getNamespace();

        if ( $editNS != NS_MAIN )
            return true;

        $content = $revision->getContent( SlotRecord::MAIN, RevisionRecord::RAW );

        if (!( $content instanceof TextContent )) {
            $hookStatus->fatal( new RawMessage( "Something went wrong. Cannot save this page." ) );
            return false;
        }

        $userID = $user->getId();
        $pageTitle = $renderedRevision->getRevisionParserOutput()->getTitleText();
        $pageText = $content->getText();
        $pageCategories = $renderedRevision->getRevisionParserOutput()->getCategories();
        $parentPageID = $revision->getParentId();

        $editService = MediaWikiServices::getInstance()->getService( "Review.EditsService" );
        $serviceResponse = $editService->handleNewEdit( $userID, $pageTitle, $pageText, $pageCategories,
            $parentPageID );

        // No interception is needed. Allow this edit to pass.
        if ( !$serviceResponse )
            return true;

        $redirectURL = Title::newFromText( "Special:Review", NS_SPECIAL )->getFullURL();
        RequestContext::getMain()->getOutput()->redirect( $redirectURL );

        $hookStatus->fatal( new RawMessage( "Page will get handled by Review extension." ) );
        return false;
    }
}