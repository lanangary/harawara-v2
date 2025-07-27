<?php

namespace HarawaraTheme\ACF;

/**
 * Processes and saves ACF field values from frontend form submissions
 */
class FieldValueProcessor
{
    private int $page_id;
    private int $user_id;

    public function __construct()
    {
        // Ensure ACF is available
        if (!function_exists('update_field')) {
            throw new \Exception('ACF plugin is not available');
        }
    }

    /**
     * Process form submission and update ACF fields
     */
    public function processFormSubmission(array $form_data, int $page_id, int $user_id): array
    {
        $this->page_id = $page_id;
        $this->user_id = $user_id;

        $result = [
            'success' => true,
            'data' => [],
            'errors' => []
        ];

        try {
            // Validate user permissions
            if (!$this->canUserEditPage($user_id, $page_id)) {
                throw new \Exception('You do not have permission to edit this page.');
            }

            // Process each field
            foreach ($form_data as $field_name => $value) {
                // Skip non-ACF fields
                if (in_array($field_name, ['subscriber_page_nonce', 'subscriber_page_update', 'elementor_template_id', 'page_slug', 'page_content'])) {
                    continue;
                }

                // Skip fields that end with _remove (these are handled within the main field processing)
                if (substr($field_name, -7) === '_remove') {
                    continue;
                }

                $processed_value = $this->processFieldValue($field_name, $value);
                
                // Only include in result if there's an actual change or explicit action
                if ($processed_value !== false) {
                    $result['data'][$field_name] = $processed_value;
                }
            }

            // Process file fields that might not be in POST data
            foreach ($_FILES as $field_name => $file_data) {
                // Skip if we already processed this field
                if (array_key_exists($field_name, $result['data'])) {
                    continue;
                }
                
                // Get field config to determine field type
                $field_config = $this->getFieldConfig($field_name);
                if (!$field_config) {
                    continue;
                }
                
                $field_type = $field_config['type'] ?? 'unknown';
                
                // Process the file field
                $processed_value = null;
                if ($field_type === 'file') {
                    $processed_value = $this->processFileValue('', $field_config, $field_name);
                } elseif ($field_type === 'gallery') {
                    $processed_value = $this->processGalleryValue('', $field_config, $field_name);
                }
                
                if ($processed_value !== false) {
                    $result['data'][$field_name] = $processed_value;
                }
            }

            // Note: ACF field updates are handled by the calling controller
            // This allows for proper handling of template changes and data preservation

        } catch (\Exception $e) {
            $result['success'] = false;
            $result['errors'][] = $e->getMessage();
        }

        return $result;
    }

    /**
     * Process a single field value based on its type
     */
    private function processFieldValue(string $field_name, $value)
    {
        // Get field configuration
        $field_config = $this->getFieldConfig($field_name);
        
        if (!$field_config) {
            return null; // Field not found or not editable
        }

        $field_type = $field_config['type'] ?? 'text';

        // Sanitize and validate based on field type
        switch ($field_type) {
            case 'text':
                return $this->processTextValue($value, $field_config);
            
            case 'textarea':
                return $this->processTextareaValue($value, $field_config);
            
            case 'wysiwyg':
                return $this->processWysiwygValue($value, $field_config);
            
            case 'image':
                return $this->processImageValue($value, $field_config);
            
            case 'file':
                return $this->processFileValue($value, $field_config, $field_name);
            
            case 'gallery':
                return $this->processGalleryValue($value, $field_config, $field_name);
            
            case 'select':
                return $this->processSelectValue($value, $field_config);
            
            case 'checkbox':
                return $this->processCheckboxValue($value, $field_config);
            
            case 'radio':
                return $this->processRadioValue($value, $field_config);
            
            case 'number':
                return $this->processNumberValue($value, $field_config);
            
            case 'email':
                return $this->processEmailValue($value, $field_config);
            
            case 'url':
                return $this->processUrlValue($value, $field_config);
            
            case 'date_picker':
            case 'date_time_picker':
                return $this->processDateValue($value, $field_config);
            
            case 'color_picker':
                return $this->processColorValue($value, $field_config);
            
            case 'link':
                return $this->processLinkValue($value, $field_config);
            
            case 'repeater':
                return $this->processRepeaterValue($value, $field_config);
            
            case 'flexible_content':
                return $this->processFlexibleContentValue($value, $field_config);
            
            default:
                return $this->processGenericValue($value, $field_config);
        }
    }

    /**
     * Get field configuration
     */
    private function getFieldConfig(string $field_name): ?array
    {
        // First try to get field object directly
        $field = get_field_object($field_name, $this->page_id);
        
        if ($field) {
            return $field;
        }
        
        // If direct lookup fails, get field info from field groups
        $field_groups = acf_get_field_groups();
        
        foreach ($field_groups as $group) {
            // Check if this group applies to our page
            if ($this->groupAppliesTo($group, $this->page_id)) {
                $fields = acf_get_fields($group['key']);
                
                foreach ($fields as $field_config) {
                    if ($field_config['name'] === $field_name) {
                        return $field_config;
                    }
                }
            }
        }

        return null;
    }
    
    /**
     * Check if field group applies to page
     */
    private function groupAppliesTo(array $group, int $page_id): bool
    {
        if (empty($group['location'])) {
            return false;
        }
        
        $page = get_post($page_id);
        $page_template = get_page_template_slug($page_id);
        
        foreach ($group['location'] as $rule_group) {
            $match = true;
            foreach ($rule_group as $rule) {
                $param = $rule['param'] ?? '';
                $operator = $rule['operator'] ?? '==';
                $value = $rule['value'] ?? '';
                
                switch ($param) {
                    case 'post_type':
                        if (!($page->post_type == $value)) $match = false;
                        break;
                    case 'page_template':
                        if (!($page_template == $value)) $match = false;
                        break;
                    case 'post':
                        if (!($page->ID == $value)) $match = false;
                        break;
                    default:
                        $match = false;
                }
                
                if (!$match) break;
            }
            if ($match) return true;
        }
        
        return false;
    }

    /**
     * Process text field value
     */
    private function processTextValue($value, array $field_config): string
    {
        $value = sanitize_text_field($value);
        
        // Validate length
        $max_length = $field_config['maxlength'] ?? null;
        if ($max_length && strlen($value) > $max_length) {
            $value = substr($value, 0, $max_length);
        }

        return $value;
    }

    /**
     * Process textarea field value
     */
    private function processTextareaValue($value, array $field_config): string
    {
        return sanitize_textarea_field($value);
    }

    /**
     * Process WYSIWYG field value
     */
    private function processWysiwygValue($value, array $field_config): string
    {
        return wp_kses_post($value);
    }

    /**
     * Process image field value
     */
    private function processImageValue($value, array $field_config): ?int
    {
        if (empty($value)) {
            return null;
        }

        // Handle file upload
        if (is_array($value) && isset($value['tmp_name'])) {
            return $this->handleFileUpload($value, $field_config);
        }

        // Handle existing image ID
        if (is_numeric($value)) {
            return intval($value);
        }

        return null;
    }

    /**
     * Process file field value
     */
    private function processFileValue($value, array $field_config, string $field_name): ?int
    {
        // Check for file upload in $_FILES
        if (isset($_FILES[$field_name]) && $_FILES[$field_name]['error'] === UPLOAD_ERR_OK) {
            return $this->handleFileUpload($_FILES[$field_name], $field_config);
        }
        
        // Check for removal request
        if (isset($_POST[$field_name . '_remove']) && $_POST[$field_name . '_remove'] == '1') {
            return null; // Remove existing file
        }
        
        // Keep existing value if no new file uploaded and no removal requested
        $current_value = get_field($field_name, $this->page_id);
        
        if ($current_value) {
            if (is_array($current_value) && isset($current_value['ID'])) {
                return intval($current_value['ID']);
            } elseif (is_numeric($current_value)) {
                return intval($current_value);
            }
        }

        // Return false to maintain current value (ACF will keep existing value)
        return false;
    }

    /**
     * Process gallery field value
     */
    private function processGalleryValue($value, array $field_config, string $field_name): array
    {
        $gallery_ids = [];
        
        // Check for file uploads in $_FILES
        if (isset($_FILES[$field_name]) && is_array($_FILES[$field_name]['tmp_name'])) {
            foreach ($_FILES[$field_name]['tmp_name'] as $index => $tmp_name) {
                if ($_FILES[$field_name]['error'][$index] === UPLOAD_ERR_OK) {
                    $file_data = [
                        'name' => $_FILES[$field_name]['name'][$index],
                        'type' => $_FILES[$field_name]['type'][$index],
                        'tmp_name' => $tmp_name,
                        'error' => $_FILES[$field_name]['error'][$index],
                        'size' => $_FILES[$field_name]['size'][$index]
                    ];
                    
                    $file_id = $this->handleFileUpload($file_data, $field_config);
                    if ($file_id) {
                        $gallery_ids[] = $file_id;
                    }
                }
            }
        }
        
        // Handle existing images (with removal support)
        $current_value = get_field($field_name, $this->page_id);
        if ($current_value && is_array($current_value)) {
            $remove_ids = isset($_POST[$field_name . '_remove']) ? $_POST[$field_name . '_remove'] : [];
            
            foreach ($current_value as $item) {
                $image_id = null;
                if (is_array($item) && isset($item['ID'])) {
                    $image_id = intval($item['ID']);
                } elseif (is_numeric($item)) {
                    $image_id = intval($item);
                }
                
                // Only keep if not marked for removal
                if ($image_id && !in_array($image_id, $remove_ids)) {
                    $gallery_ids[] = $image_id;
                }
            }
        }

        return $gallery_ids;
    }

    /**
     * Process select field value
     */
    private function processSelectValue($value, array $field_config): mixed
    {
        $choices = $field_config['choices'] ?? [];
        $multiple = $field_config['multiple'] ?? false;

        if ($multiple) {
            if (!is_array($value)) {
                return [];
            }

            $valid_values = [];
            foreach ($value as $item) {
                if (array_key_exists($item, $choices)) {
                    $valid_values[] = $item;
                }
            }
            return $valid_values;
        } else {
            return array_key_exists($value, $choices) ? $value : '';
        }
    }

    /**
     * Process checkbox field value
     */
    private function processCheckboxValue($value, array $field_config): mixed
    {
        if ($field_config['multiple'] ?? false) {
            return is_array($value) ? $value : [];
        } else {
            return !empty($value);
        }
    }

    /**
     * Process radio field value
     */
    private function processRadioValue($value, array $field_config): string
    {
        $choices = $field_config['choices'] ?? [];
        return array_key_exists($value, $choices) ? $value : '';
    }

    /**
     * Process number field value
     */
    private function processNumberValue($value, array $field_config): ?float
    {
        if (empty($value) && $value !== '0') {
            return null;
        }

        $value = floatval($value);
        
        // Validate min/max
        $min = $field_config['min'] ?? null;
        $max = $field_config['max'] ?? null;

        if ($min !== null && $value < $min) {
            $value = $min;
        }
        if ($max !== null && $value > $max) {
            $value = $max;
        }

        return $value;
    }

    /**
     * Process email field value
     */
    private function processEmailValue($value, array $field_config): string
    {
        $value = sanitize_email($value);
        return is_email($value) ? $value : '';
    }

    /**
     * Process URL field value
     */
    private function processUrlValue($value, array $field_config): string
    {
        $value = esc_url_raw($value);
        return filter_var($value, FILTER_VALIDATE_URL) ? $value : '';
    }

    /**
     * Process date field value
     */
    private function processDateValue($value, array $field_config): string
    {
        if (empty($value)) {
            return '';
        }

        // Try to parse the date
        $timestamp = strtotime($value);
        if ($timestamp === false) {
            return '';
        }

        return date('Y-m-d H:i:s', $timestamp);
    }

    /**
     * Process color field value
     */
    private function processColorValue($value, array $field_config): string
    {
        return sanitize_hex_color($value);
    }

    /**
     * Process link field value
     */
    private function processLinkValue($value, array $field_config): array
    {
        if (!is_array($value)) {
            return ['url' => '', 'title' => '', 'target' => ''];
        }

        return [
            'url' => esc_url_raw($value['url'] ?? ''),
            'title' => sanitize_text_field($value['title'] ?? ''),
            'target' => in_array($value['target'] ?? '', ['_blank', '_self']) ? $value['target'] : '_self'
        ];
    }

    /**
     * Process repeater field value
     */
    private function processRepeaterValue($value, array $field_config): array
    {
        if (!is_array($value)) {
            return [];
        }

        $processed_rows = [];
        foreach ($value as $row) {
            if (is_array($row)) {
                $processed_row = [];
                foreach ($row as $key => $row_value) {
                    $processed_row[$key] = $this->sanitizeRepeaterField($row_value);
                }
                $processed_rows[] = $processed_row;
            }
        }

        return $processed_rows;
    }

    /**
     * Process flexible content field value
     */
    private function processFlexibleContentValue($value, array $field_config): array
    {
        if (!is_array($value)) {
            return [];
        }

        $processed_layouts = [];
        foreach ($value as $layout) {
            if (is_array($layout) && isset($layout['acf_fc_layout'])) {
                $processed_layout = [
                    'acf_fc_layout' => sanitize_text_field($layout['acf_fc_layout'])
                ];

                foreach ($layout as $key => $layout_value) {
                    if ($key !== 'acf_fc_layout') {
                        $processed_layout[$key] = $this->sanitizeFlexibleContentField($layout_value);
                    }
                }

                $processed_layouts[] = $processed_layout;
            }
        }

        return $processed_layouts;
    }

    /**
     * Process generic field value
     */
    private function processGenericValue($value, array $field_config): mixed
    {
        if (is_string($value)) {
            return sanitize_text_field($value);
        }

        if (is_array($value)) {
            return array_map([$this, 'sanitizeGenericField'], $value);
        }

        return $value;
    }

    /**
     * Handle file upload
     */
    private function handleFileUpload(array $file_data, array $field_config): ?int
    {
        // Ensure WordPress file upload functions are available
        if (!function_exists('wp_handle_upload')) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
        }
        // Validate file type
        $allowed_types = $field_config['mime_types'] ?? '';
        if ($allowed_types) {
            $file_type = wp_check_filetype($file_data['name']);
            if (!in_array($file_type['type'], explode(',', $allowed_types))) {
                return null;
            }
        }

        // Validate file size
        $max_size = $field_config['max_size'] ?? 0;
        if ($max_size && $file_data['size'] > $max_size) {
            return null;
        }

        // Upload file
        $upload = wp_handle_upload($file_data, ['test_form' => false]);
        
        if (isset($upload['error'])) {
            return null;
        }

        // Create attachment
        $attachment = [
            'post_title' => sanitize_text_field($file_data['name']),
            'post_content' => '',
            'post_status' => 'inherit',
            'post_mime_type' => $upload['type']
        ];

        $attachment_id = wp_insert_attachment($attachment, $upload['file'], $this->page_id);
        
        if (is_wp_error($attachment_id)) {
            return null;
        }

        // Generate attachment metadata
        if (!function_exists('wp_generate_attachment_metadata')) {
            require_once(ABSPATH . 'wp-admin/includes/image.php');
        }
        $attachment_data = wp_generate_attachment_metadata($attachment_id, $upload['file']);
        wp_update_attachment_metadata($attachment_id, $attachment_data);

        return $attachment_id;
    }

    /**
     * Sanitize repeater field value
     */
    private function sanitizeRepeaterField($value): mixed
    {
        if (is_string($value)) {
            return sanitize_text_field($value);
        }

        if (is_array($value)) {
            return array_map([$this, 'sanitizeRepeaterField'], $value);
        }

        return $value;
    }

    /**
     * Sanitize flexible content field value
     */
    private function sanitizeFlexibleContentField($value): mixed
    {
        if (is_string($value)) {
            return sanitize_text_field($value);
        }

        if (is_array($value)) {
            return array_map([$this, 'sanitizeFlexibleContentField'], $value);
        }

        return $value;
    }

    /**
     * Sanitize generic field value
     */
    private function sanitizeGenericField($value): mixed
    {
        if (is_string($value)) {
            return sanitize_text_field($value);
        }

        if (is_array($value)) {
            return array_map([$this, 'sanitizeGenericField'], $value);
        }

        return $value;
    }

    /**
     * Check if user can edit the page
     */
    private function canUserEditPage(int $user_id, int $page_id): bool
    {
        $subscriber_page_id = get_user_meta($user_id, 'subscriber_page_id', true);
        return $subscriber_page_id == $page_id;
    }
} 