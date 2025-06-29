<?php

namespace JuiceBox\PostType;

use JuiceBox\PostType\CustomPostType;

class Collections extends CustomPostType
{
    protected static $postType = 'collections';

    protected static $postName = 'Collections';

    protected static $singularName = 'Collection';

    protected static $pluralName = 'Collection Items';

    protected static $public = false;

    protected static $supports = array(
        'title'
    );
}
