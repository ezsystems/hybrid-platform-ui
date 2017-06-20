<?php
return PhpCsFixer\Config::create()
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'concat_space' => ['spacing' => 'one'],
        'array_syntax' => false,
        'simplified_null_return' => false,
        'phpdoc_align' => false,
        'phpdoc_separation' => false,
        'phpdoc_to_comment' => false,
        'cast_spaces' => false,
        'blank_line_after_opening_tag' => false,
        'single_blank_line_before_namespace' => false,
        'phpdoc_annotation_without_dot' => false,
        'phpdoc_no_alias_tag' => false,
        'space_after_semicolon' => false,
        // disabled as it will rewrite the phpspec methods, that don't use visibility
        'visibility_required' => false,
    ])
    ->setRiskyAllowed(true)
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in([__DIR__ . '/src', __DIR__ . '/spec'])
            ->exclude([
                'docs',
                'vendor',
            ])
            ->files()->name('*.php')
    )
;
