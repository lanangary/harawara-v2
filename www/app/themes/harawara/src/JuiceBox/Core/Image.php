<?php

namespace JuiceBox\Core;

use Timber\Image as TimberImage;

class Image extends TimberImage {
    /**
     * @var int
     */
    private $iid;

    /**
     * @param int $iid
     */
    public function __construct($iid)
    {
        $this->iid = $iid;

        parent::__construct($iid);
    }

    /**
     * @param string|array $size - See http://codex.wordpress.org/Function_Reference/wp_get_attachment_image_src
     * @return array|bool|string
     */
    public function src($size = 'full'): string
    {
        $image = wp_get_attachment_image_src($this->iid, $size);

        if (!$image || !is_array($image) || empty($image[0])) {
            return '';
        }

        return $image[0];
    }

    /**
     * @deprecated
     * @param string|array $size - See http://codex.wordpress.org/Function_Reference/wp_get_attachment_image_src
     * @return array|bool|string
     */
    public function get_src($size = 'full')
    {
        return $this->src($size);
    }
}
