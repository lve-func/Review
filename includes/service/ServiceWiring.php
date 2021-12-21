<?php

use MediaWiki\MediaWikiServices;

return [
    "Review.EditsService" => function ( MediaWikiServices $services ) : EditsService {
        return new EditsService();
    },
    "Review.MappingService" => function ( MediaWikiServices $services ) : MappingService {
        return new MappingService();
    }
];