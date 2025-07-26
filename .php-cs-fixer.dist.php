<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = (new Finder())
    ->in(__DIR__)
    ->exclude('bootstrap/cache')
    ->exclude('storage')
    ->exclude('node_modules')
    ->exclude('public')
    ->exclude('vendor')
    ->exclude('postgres_data');

return (new Config())
    ->setRules([
        '@PSR12' => true,

        // @array_notation
        'array_syntax' => [
            'syntax' => 'short',
        ],
        'no_multiline_whitespace_around_double_arrow' => true,
        'no_whitespace_before_comma_in_array'         => [
            'after_heredoc' => true,
        ],
        'normalize_index_brace'           => true,
        'trim_array_spaces'               => true,
        'whitespace_after_comma_in_array' => true,

        // @casing
        'magic_constant_casing'                   => true,
        'magic_method_casing'                     => true,
        'native_function_casing'                  => true,
        'native_function_type_declaration_casing' => true,

        // @cast_notation
        'cast_spaces' => [
            'space' => 'single',
        ],
        'no_unset_cast' => true,

        // @class_notation
        'class_attributes_separation' => [
            'elements' => [
                'method' => 'one',
            ],
        ],
        'ordered_class_elements' => [
            'order' => [
                'use_trait',
                'constant',
                'property',
                'construct',
                'destruct',
            ],
        ],

        // @control_structure
        'no_superfluous_elseif'       => true,
        'no_useless_else'             => true,
        'switch_continue_to_break'    => true,
        'trailing_comma_in_multiline' => [
            'elements' => [
                'arrays',
            ],
            'after_heredoc' => true,
        ],

        // @function_notation
        'function_typehint_space'                          => true,
        'lambda_not_used_import'                           => true,
        'nullable_type_declaration_for_default_null_value' => true,
        // Cannot use with Laravel because it has wrong types in PHPDocs for several predefined classes.
        // 'phpdoc_to_param_type'                             => true, // risky! experimental!
        'phpdoc_to_return_type'                            => true, // risky! experimental!
        'void_return'                                      => true, // risky!

        // @import
        'fully_qualified_strict_types' => true,
        'global_namespace_import'      => true,
        'no_unused_imports'            => true,
        'ordered_imports'              => [
            'imports_order'  => [
                'class',
                'function',
                'const',
            ],
            'sort_algorithm' => 'alpha', // override of PSR-12 default
        ],

        // @language_construct
        'single_space_after_construct' => true,

        // @namespace_notation
        'clean_namespace'                    => true,
        'no_leading_namespace_whitespace'    => true,

        // @operator
        'binary_operator_spaces' => [
            'operators' => [
                // '=>' => 'align_single_space_minimal',
                '=>' => 'align_single_space',
                // '=>' => 'align',
            ],
        ],
        'concat_space' => [
            'spacing' => 'one',
        ],
        'object_operator_without_whitespace' => true,
        'operator_linebreak'                 => [
            'only_booleans' => true,
        ],
        'ternary_to_elvis_operator'          => true, // risky!
        'ternary_to_null_coalescing'         => true,
        'unary_operator_spaces'              => true,

        // @php_unit
        'php_unit_method_casing'   => [
            'case' => 'snake_case',
        ],
        'php_unit_namespaced'      => true, // risky!
        'php_unit_test_annotation' => true, // risky!

        // @phpdoc
        'align_multiline_comment' => [
            'comment_type' => 'phpdocs_like',
        ],
        'no_empty_phpdoc'             => true,
        'no_superfluous_phpdoc_tags'  => [
            'allow_mixed'       => true,
            'remove_inheritdoc' => true,
        ],
        'phpdoc_indent'    => true,
        'phpdoc_line_span' => [
            'const'    => 'single',
            'property' => 'single',
        ],
        'phpdoc_no_access'                              => true,
        'phpdoc_no_alias_tag'                           => true,
        'phpdoc_no_useless_inheritdoc'                  => true,
        'phpdoc_scalar'                                 => true,
        'phpdoc_single_line_var_spacing'                => true,
        'phpdoc_trim'                                   => true,
        'phpdoc_trim_consecutive_blank_line_separation' => true,
        'phpdoc_types'                                  => true,
        'phpdoc_types_order'                            => [
            'sort_algorithm'  => 'none',
            'null_adjustment' => 'always_last',
        ],
        'phpdoc_var_annotation_correct_order' => true,
        'phpdoc_var_without_name'             => true,

        // @semicolon
        'no_empty_statement'                         => true,
        'no_singleline_whitespace_before_semicolons' => true,
        'space_after_semicolon'                      => true,

        // @strict
        'strict_param' => true, // risky!

        // @string_notation
        'single_quote'                     => true,
        'string_line_ending'               => true, // risky!

        // @whitespace
        'array_indentation'           => true,
        'heredoc_indentation'         => true,
        'method_chaining_indentation' => true,
        'no_extra_blank_lines'        => [
            'tokens' => [
                'break',
                'case',
                'continue',
                'curly_brace_block',
                'default',
                'extra',
                'parenthesis_brace_block',
                'return',
                'square_brace_block',
                'switch',
                'throw',
                'use',
                'use_trait',
            ],
        ],
        'no_spaces_around_offset' => true,
    ])
    ->setFinder($finder)
    ->setRiskyAllowed(true);
