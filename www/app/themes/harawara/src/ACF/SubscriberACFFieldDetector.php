<?php

namespace HarawaraTheme\ACF;

/**
 * Detects ACF fields available on subscriber pages and maps them to frontend form fields
 */
class SubscriberACFFieldDetector
{
    private int $page_id;
    private int $user_id;

    public function __construct()
    {
        // Ensure ACF is available
        if (!function_exists('acf_get_field_groups')) {
            throw new \Exception('ACF plugin is not available');
        }
    }

    /**
     * Detect all ACF fields for a subscriber's page
     */
    public function detectFields(int $page_id, int $user_id): array
    {
        $this->page_id = $page_id;
        $this->user_id = $user_id;

        try {
            $field_groups = $this->getApplicableFieldGroups();
            $mapped_fields = $this->mapFieldsToComponents($field_groups);
            
            return [
                'field_groups' => $mapped_fields,
                'page_template' => get_page_template_slug($page_id),
                'permissions' => [
                    'can_edit_fields' => $this->canUserEditPage($user_id, $page_id),
                    'restricted_fields' => $this->getRestrictedFields()
                ]
            ];
        } catch (\Exception $e) {
            error_log('ACF Field Detection Error: ' . $e->getMessage());
            return [
                'field_groups' => [],
                'error' => 'Unable to detect fields for this page.',
                'error_code' => 'DETECTION_FAILED'
            ];
        }
    }

    /**
     * Get field groups that apply to this page
     */
    private function getApplicableFieldGroups(): array
    {
        $field_groups = acf_get_field_groups();
        $applicable_groups = [];

        foreach ($field_groups as $group) {
            if ($this->evaluateLocationRules($group, $this->page_id)) {
                // Get the actual fields for this group
                $group['fields'] = acf_get_fields($group['key']);
                $applicable_groups[] = $group;
            }
        }

        return $applicable_groups;
    }

    /**
     * Evaluate ACF location rules for a page
     */
    private function evaluateLocationRules(array $field_group, int $page_id): bool
    {
        $page = get_post($page_id);
        $page_template = get_page_template_slug($page_id);

        if (empty($field_group['location'])) {
            return false;
        }

        foreach ($field_group['location'] as $rule_group) {
            $match = true;
            foreach ($rule_group as $rule) {
                if (!$this->evaluateRule($rule, $page, $page_template)) {
                    $match = false;
                    break;
                }
            }
            if ($match) {
                return true;
            }
        }

        return false;
    }

    /**
     * Evaluate a single location rule
     */
    private function evaluateRule(array $rule, \WP_Post $page, string $page_template): bool
    {
        $param = $rule['param'] ?? '';
        $operator = $rule['operator'] ?? '==';
        $value = $rule['value'] ?? '';

        switch ($param) {
            case 'post_type':
                return $this->compareValues($page->post_type, $operator, $value);
            
            case 'page_template':
                return $this->compareValues($page_template, $operator, $value);
            
            case 'post':
                return $this->compareValues($page->ID, $operator, $value);
            
            case 'post_status':
                return $this->compareValues($page->post_status, $operator, $value);
            
            case 'post_category':
                $categories = wp_get_post_categories($page->ID);
                return $this->compareValues($categories, $operator, $value);
            
            default:
                return false;
        }
    }

    /**
     * Compare values based on operator
     */
    private function compareValues($actual, string $operator, $expected): bool
    {
        switch ($operator) {
            case '==':
                return $actual == $expected;
            case '!=':
                return $actual != $expected;
            case '>':
                return $actual > $expected;
            case '<':
                return $actual < $expected;
            case '>=':
                return $actual >= $expected;
            case '<=':
                return $actual <= $expected;
            case 'contains':
                if (is_array($actual)) {
                    return in_array($expected, $actual);
                }
                return strpos($actual, $expected) !== false;
            case '!contains':
                if (is_array($actual)) {
                    return !in_array($expected, $actual);
                }
                return strpos($actual, $expected) === false;
            default:
                return false;
        }
    }

    /**
     * Map ACF fields to frontend components
     */
    private function mapFieldsToComponents(array $field_groups): array
    {
        $mapped_groups = [];

        foreach ($field_groups as $group) {
            $mapped_fields = [];
            
            // Get fields if not already loaded
            $fields = $group['fields'] ?? acf_get_fields($group['key']);
            
            if (is_array($fields)) {
                foreach ($fields as $field) {
                    $mapped_field = $this->mapFieldToComponent($field);
                    if ($mapped_field) {
                        $mapped_fields[] = $mapped_field;
                    }
                }
            }

            if (!empty($mapped_fields)) {
                $mapped_groups[] = [
                    'key' => $group['key'],
                    'title' => $group['title'],
                    'description' => $group['description'] ?? '',
                    'fields' => $mapped_fields
                ];
            }
        }

        return $mapped_groups;
    }

    /**
     * Map a single ACF field to a frontend component
     */
    private function mapFieldToComponent(array $field): ?array
    {
        $field_type = $field['type'] ?? '';
        $field_key = $field['key'] ?? '';
        $field_name = $field['name'] ?? '';
        $field_label = $field['label'] ?? $field_name;

        // Get current field value
        $current_value = get_field($field_name, $this->page_id);

        // Map field type to UI component
        $ui_component = $this->getUIComponentForFieldType($field_type);
        
        if (!$ui_component) {
            return null; // Skip unsupported field types
        }

        return [
            'key' => $field_key,
            'name' => $field_name,
            'label' => $field_label,
            'type' => $field_type,
            'ui_component' => $ui_component,
            'config' => $this->getComponentConfig($field),
            'value' => $current_value,
            'editable' => $this->canUserEditField($field),
            'required' => $field['required'] ?? false,
            'instructions' => $field['instructions'] ?? ''
        ];
    }

    /**
     * Get UI component for ACF field type
     */
    private function getUIComponentForFieldType(string $field_type): ?string
    {
        $mappings = [
            'text' => 'SimpleTextInput',
            'textarea' => 'SimpleTextarea',
            'wysiwyg' => 'SimpleRichEditor',
            'image' => 'SimpleImageUpload',
            'file' => 'SimpleFileUpload',
            'gallery' => 'SimpleGalleryManager',
            'select' => 'SimpleDropdown',
            'checkbox' => 'SimpleCheckbox',
            'radio' => 'SimpleRadio',
            'date_time_picker' => 'SimpleDatePicker',
            'date_picker' => 'SimpleDatePicker',
            'time_picker' => 'SimpleTimePicker',
            'link' => 'SimpleLinkBuilder',
            'url' => 'SimpleUrlInput',
            'email' => 'SimpleEmailInput',
            'number' => 'SimpleNumberInput',
            'range' => 'SimpleRangeInput',
            'color_picker' => 'SimpleColorPicker',
            'flexible_content' => 'SimpleModuleBuilder',
            'repeater' => 'SimpleRepeaterInterface',
            'relationship' => 'SimpleContentSelector',
            'post_object' => 'SimpleContentSelector',
            'page_link' => 'SimpleContentSelector',
            'user' => 'SimpleUserSelector'
        ];

        return $mappings[$field_type] ?? null;
    }

    /**
     * Get component configuration for a field
     */
    private function getComponentConfig(array $field): array
    {
        $config = [
            'label' => $field['label'] ?? '',
            'description' => $field['instructions'] ?? '',
            'validation' => [
                'required' => $field['required'] ?? false,
                'max_length' => $field['maxlength'] ?? null,
                'min_length' => $field['minlength'] ?? null
            ],
            'ui_settings' => [
                'placeholder' => $field['placeholder'] ?? '',
                'default_value' => $field['default_value'] ?? ''
            ]
        ];

        // Add type-specific configurations
        switch ($field['type']) {
            case 'select':
                $config['choices'] = $field['choices'] ?? [];
                $config['multiple'] = $field['multiple'] ?? false;
                break;
            
            case 'image':
            case 'file':
                $config['validation']['file_types'] = $field['mime_types'] ?? '';
                $config['validation']['max_size'] = $field['max_size'] ?? '';
                break;
            
            case 'gallery':
                $config['validation']['max_files'] = $field['max'] ?? '';
                $config['validation']['file_types'] = $field['mime_types'] ?? '';
                break;
            
            case 'number':
                $config['validation']['min'] = $field['min'] ?? null;
                $config['validation']['max'] = $field['max'] ?? null;
                $config['validation']['step'] = $field['step'] ?? 1;
                break;
            
            case 'range':
                $config['validation']['min'] = $field['min'] ?? 0;
                $config['validation']['max'] = $field['max'] ?? 100;
                $config['validation']['step'] = $field['step'] ?? 1;
                break;
        }

        return $config;
    }

    /**
     * Check if user can edit this field
     */
    private function canUserEditField(array $field): bool
    {
        // Check if user owns the page
        if (!$this->canUserEditPage($this->user_id, $this->page_id)) {
            return false;
        }

        // Check if field is restricted
        $restricted_fields = $this->getRestrictedFields();
        if (in_array($field['key'], $restricted_fields)) {
            return false;
        }

        return true;
    }

    /**
     * Check if user can edit the page
     */
    private function canUserEditPage(int $user_id, int $page_id): bool
    {
        $subscriber_page_id = get_user_meta($user_id, 'subscriber_page_id', true);
        return $subscriber_page_id == $page_id;
    }

    /**
     * Get list of restricted fields that subscribers cannot edit
     */
    private function getRestrictedFields(): array
    {
        return [
            // Add any field keys that should be restricted
            // 'field_admin_only_field',
            // 'field_system_configuration'
        ];
    }
} 