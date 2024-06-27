<?php

declare(strict_types=1);

return [
    'preset' => 'symfony',
    'exclude' => [
        'src/Migrations',
        'src/Kernel.php',
    ],
    'add' => [],
    'remove' => [
        \PHP_CodeSniffer\Standards\Generic\Sniffs\Formatting\SpaceAfterNotSniff::class,
        \NunoMaduro\PhpInsights\Domain\Sniffs\ForbiddenSetterSniff::class,
        \SlevomatCodingStandard\Sniffs\Commenting\UselessFunctionDocCommentSniff::class,
        \SlevomatCodingStandard\Sniffs\Commenting\DocCommentSpacingSniff::class,
        \SlevomatCodingStandard\Sniffs\Classes\SuperfluousInterfaceNamingSniff::class,
        \SlevomatCodingStandard\Sniffs\Classes\SuperfluousExceptionNamingSniff::class,
        \SlevomatCodingStandard\Sniffs\ControlStructures\DisallowYodaComparisonSniff::class,
        \NunoMaduro\PhpInsights\Domain\Insights\ForbiddenTraits::class,
        \NunoMaduro\PhpInsights\Domain\Insights\ForbiddenNormalClasses::class,
        \SlevomatCodingStandard\Sniffs\Classes\SuperfluousTraitNamingSniff::class,
        \SlevomatCodingStandard\Sniffs\Classes\ForbiddenPublicPropertySniff::class,
        \NunoMaduro\PhpInsights\Domain\Insights\CyclomaticComplexityIsHigh::class,
        \NunoMaduro\PhpInsights\Domain\Insights\ForbiddenDefineFunctions::class,
        \NunoMaduro\PhpInsights\Domain\Insights\ForbiddenFinalClasses::class,
        \NunoMaduro\PhpInsights\Domain\Insights\ForbiddenGlobals::class,
        \PHP_CodeSniffer\Standards\Squiz\Sniffs\Commenting\FunctionCommentSniff::class,
        \SlevomatCodingStandard\Sniffs\TypeHints\ReturnTypeHintSniff::class,
        \SlevomatCodingStandard\Sniffs\Commenting\InlineDocCommentDeclarationSniff::class,
        \SlevomatCodingStandard\Sniffs\Classes\ModernClassNameReferenceSniff::class,
        \PHP_CodeSniffer\Standards\Generic\Sniffs\CodeAnalysis\UselessOverridingMethodSniff::class,
        \SlevomatCodingStandard\Sniffs\TypeHints\DeclareStrictTypesSniff::class,
        \SlevomatCodingStandard\Sniffs\TypeHints\ParameterTypeHintSniff::class,
        \SlevomatCodingStandard\Sniffs\TypeHints\PropertyTypeHintSniff::class,
        \SlevomatCodingStandard\Sniffs\Arrays\TrailingArrayCommaSniff::class
    ],
    'config' => [
        \PHP_CodeSniffer\Standards\Generic\Sniffs\Files\LineLengthSniff::class => [
            'lineLimit' => 120,
            'absoluteLineLimit' => 160,
        ],
        \SlevomatCodingStandard\Sniffs\Commenting\InlineDocCommentDeclarationSniff::class => [
            'exclude' => [
                'src/Exception/BaseException.php',
            ],
        ],
        \SlevomatCodingStandard\Sniffs\ControlStructures\AssignmentInConditionSniff::class => [
            'enabled' => false,
        ],
    ],
    'requirements' => [
        'min-quality' => 80,
        'min-complexity' => 50,
        'min-architecture' => 75,
        'min-style' => 95,
        'disable-security-check' => false,
    ],
    'threads' => null
];
