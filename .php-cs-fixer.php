<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/tests')
    ->exclude('var')
    ->exclude('config')
    ->exclude('vendor');

return (new PhpCsFixer\Config())
    ->setParallelConfig(new PhpCsFixer\Runner\Parallel\ParallelConfig(4, 20))
    ->setRules([
        '@PSR12' => true,
        '@Symfony' => true,
        'full_opening_tag' => false,
        'phpdoc_var_without_name' => false,
        'phpdoc_to_comment' => false,
        'array_syntax' => ['syntax' => 'short'],
        'concat_space' => ['spacing' => 'one'],
        'binary_operator_spaces' => [
            'default' => 'single_space',
            'operators' => [
                '=' => 'single_space',
                '=>' => 'single_space',
            ],
        ],
        'blank_line_before_statement' => [
            'statements' => ['return']
        ],
        'cast_spaces' => ['space' => 'single'],
        'class_attributes_separation' => [
            'elements' => [
                'const' => 'none',
                'method' => 'one',
                'property' => 'none'
            ]
        ],
        'declare_equal_normalize' => ['space' => 'none'],
        'function_typehint_space' => true,
        'lowercase_cast' => true,
        'no_unused_imports' => true,
        'not_operator_with_successor_space' => true,
        'ordered_imports' => true,
        'phpdoc_align' => ['align' => 'left'],
        'phpdoc_no_alias_tag' => ['replacements' => ['type' => 'var', 'link' => 'see']],
        'phpdoc_order' => true,
        'phpdoc_scalar' => true,
        'single_quote' => true,
        'standardize_not_equals' => true,
        'trailing_comma_in_multiline' => ['elements' => ['arrays']],
        'trim_array_spaces' => true,
        'unary_operator_spaces' => true,
        'visibility_required' => ['elements' => ['const', 'method', 'property']],
        'multiline_whitespace_before_semicolons' => [
            'strategy' => 'no_multi_line',
        ],
        'method_chaining_indentation' => true,
        'class_definition' => [
            'single_item_single_line' => false,
            'multi_line_extends_each_single_line' => true,
        ],
    ])
    ->setRiskyAllowed(true)
    ->setFinder($finder)
    ->setUsingCache(false);
