<?php

namespace JuiceBox\Core;

use Timber\ImageHelper as TimberImageHelper;
use function Env\env;

class ImageHelper extends TimberImageHelper {

    public static function letterbox( $src, $w, $h, $color = false, $force = false, $use_timber = false) {
        if ($src instanceof Image) {
            $src = $src->src();
        }

        if (empty(env('CLOUDINARY_URL', '')) || $use_timber) {
            return Timber::compile('letterbox.twig', [
                'src' => $src,
                'w' => $w,
                'h' => $h,
                'color' => strpos($color, '#') === 0 ? $color : '#' . $color,
                'force' => $force
            ]);
        }

        $base_url = env('CLOUDINARY_URL') . '/image/fetch/';

        $base_filters = 'c_pad';

        $filters = $base_filters . ',b_rgb:' . str_replace('#', '', $color);

        if ($w && is_numeric($w)) {
            $filters .= ',w_' . $w;
        }

        if ($h && is_numeric($h)) {
            $filters .= ',h_' . $h;
        }

        return $base_url . $filters . '/' . $src;
    }

    public static function resize( $src, $w = 0, $h = 0, $filters = 'c_fill,g_auto', $use_timber = false) {
        if ($src instanceof Image) {
            $src = $src->src();
        }

        if (empty(env('CLOUDINARY_URL', '')) || $use_timber) {
            // Maintaining backwards compat.
            if ($filters == 'c_fill,g_auto') {
                $filters = 'center';
            }
            $crop = $filters;
            return Timber::compile('resize.twig', [
                'src' => $src,
                'w' => $w,
                'h' => $h,
                'filters' => $filters
            ]);
        }

        $base_url = env('CLOUDINARY_URL') . '/image/fetch/';

        // Filters to use on every image.
        $filters .= ',w_' . $w . ',h_' . $h;
    }

    public static function get_image_sizes($image, $image_tablet, $image_mobile, $module_options, $sizes) {
        $module_options = is_array($module_options) ? $module_options : [];

        $desktop_size = isset($module_options['image_size']) && $module_options['image_size'] !== 'custom' ? $module_options['image_size'] : 'full';

        $tablet_size = isset($sizes['tablet']) ? $sizes['tablet'] : 'large';
        $mobile_size = isset($sizes['mobile']) ? $sizes['mobile'] : 'large';

        $image_mobile_src = $image_mobile ?: $image;
        $image_tablet_src = $image_tablet ?: $image;

        $desktop_image = self::get_image_src($image, $desktop_size);
        $tablet_image = self::get_image_src($image_tablet_src, $tablet_size);
        $mobile_image = self::get_image_src($image_mobile_src, $mobile_size);

        if (isset($module_options['image_size']) && $module_options['image_size'] === 'custom' && !empty($module_options['custom_image_size']['width']) && !empty($module_options['custom_image_size']['height'])) {
            $desktop_image = self::resize_image($image, $module_options['custom_image_size']['width'], $module_options['custom_image_size']['height']);
        }

        if (is_array($sizes)) {
            foreach ($sizes as $device => $size) {
                if (!empty($size)) {
                    $image_var = $device . '_image';
                    if (!empty($size['width']) && !empty($size['height'])) {
                        $context[$image_var] = self::resize_image($image, $size['width'], $size['height']);
                    } else {
                        $context[$image_var] = self::get_image_src($image, $size);
                    }
                }
            }
        }

        return [
            'desktop_image' => $desktop_image,
            'tablet_image' => $tablet_image,
            'mobile_image' => $mobile_image,
        ];
    }

    private static function get_image_src($image, $size)
    {
        $image_src = (new Image($image))->src($size);

        if (empty($image_src)) {
            return '';
        }

        return $image_src;
    }

    private static function resize_image($image, $width, $height) {
        return self::resize((new Image($image))->src('full'), $width, $height);
    }
}
