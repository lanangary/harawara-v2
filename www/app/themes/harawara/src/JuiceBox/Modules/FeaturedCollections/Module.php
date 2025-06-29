<?php

namespace JuiceBox\Modules\FeaturedCollections;

use JuiceBox\Core\Module as JBModule;
use Timber\Timber;

class Module extends JBModule
{
    public function processModule()
    {
        $module_featured = $this->module['collections'];
 

        // Extract IDs using array_map
        $featured_id = array_map(function($featured) {
            return $featured->ID;
        }, $module_featured);

        // Query for featured posts
        $argsfeatured = [
            'post_type' => 'collections',
            'posts_per_page' => -1,
            'post__in' => $featured_id,
            'orderby' => 'post__in',
        ];
        
        $this->module['featured_content'] = Timber::get_posts($argsfeatured)->to_array();
    }
}
