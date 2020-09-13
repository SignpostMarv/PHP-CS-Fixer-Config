<?php
/**
* PHP-CS-Fixer Configuration.
*
* @author SignpostMarv
*/
declare(strict_types=1);

namespace SignpostMarv\CS;

use function array_filter;
use function array_reduce;
use function is_dir;
use function is_file;
use PhpCsFixer\Config as BaseConfig;
use PhpCsFixer\Finder as DefaultFinder;
use function preg_replace;
use function str_replace;

class Config extends BaseConfig
{
	const DEFAULT_RULES = [
		'@Symfony' => true,
		'align_multiline_comment' => false,
		'array_syntax' => [
			'syntax' => 'short',
		],
		'backtick_to_shell_exec' => true,
		'class_definition' => [
			'multi_line_extends_each_single_line' => true,
			'single_item_single_line' => true,
			'single_line' => false,
		],
		'class_keyword_remove' => false,
		'combine_consecutive_issets' => true,
		'combine_consecutive_unsets' => true,
		'combine_nested_dirname' => true,
		'compact_nullable_typehint' => false,
		'concat_space' => [
			'spacing' => 'one',
		],
		'declare_strict_types' => true,
		'dir_constant' => true,
		'ereg_to_preg' => true,
		'error_suppression' => false,
		'fopen_flag_order' => true,
		'function_to_constant' => true,
		'global_namespace_import' => [
			'import_classes' => true,
			'import_constants' => true,
			'import_functions' => true,
		],
		'heredoc_to_nowdoc' => true,
		'implode_call' => true,
		'include' => false,
		'is_null' => false,
		'linebreak_after_opening_tag' => true,
		'list_syntax' => [
			'syntax' => 'short',
		],
		'logical_operators' => true,
		'mb_str_functions' => true,
		'modernize_types_casting' => true,
		'native_constant_invocation' => true,
		'native_function_invocation' => true,
		'no_alias_functions' => true,
		'no_multiline_whitespace_before_semicolons' => true,
		'no_php4_constructor' => true,
		'no_short_echo_tag' => true,
		'no_unreachable_default_argument_value' => true,
		'no_unset_cast' => true,
		'no_useless_else' => true,
		'no_useless_return' => true,
		'non_printable_character' => true,
		'not_operator_with_space' => true,
		'not_operator_with_successor_space' => false,
		'ordered_class_elements' => true,
		'ordered_imports' => true,
		'php_unit_construct' => true,
		'php_unit_dedicate_assert' => [
			'target' => 'newest',
		],
		'php_unit_dedicate_assert_internal_type' => [
			'target' => 'newest',
		],
		'php_unit_method_casing' => [
			'case' => 'snake_case',
		],
		'php_unit_mock' => true,
		'php_unit_mock_short_will_return' => true,
		'php_unit_namespaced' => true,
		'php_unit_no_expectation_annotation' => true,
		'php_unit_ordered_covers' => true,
		'php_unit_strict' => true,
		'php_unit_test_case_static_method_calls' => true,
		'php_unit_test_class_requires_covers' => false,
		'phpdoc_add_missing_param_annotation' => true,
		'phpdoc_align' => false,
		'phpdoc_indent' => true,
		'phpdoc_no_alias_tag' => [
			'type' => 'var',
			'link' => 'see',
		],
		'phpdoc_no_empty_return' => true,
		'phpdoc_no_useless_inheritdoc' => true,
		'phpdoc_order' => true,
		/*
		* does not currently appear to handle unions correctly
		'phpdoc_to_param_type' => true,
		'phpdoc_to_return_type' => true,
		*/
		'pow_to_exponentiation' => true,
		'protected_to_private' => true,
		'psr0' => false,
		'psr4' => true,
		'random_api_migration' => true,
		'return_type_declaration' => [
			'space_before' => 'one',
		],
		'semicolon_after_instruction' => true,
		'set_type_to_cast' => true,
		'simplified_null_return' => true,
		'single_line_throw' => false,
		'static_lambda' => true,
		'strict_comparison' => true,
		'strict_param' => true,
		'ternary_to_null_coalescing' => true,
	];

	final public function __construct(array $inDirs)
	{
		parent::__construct(
			str_replace(
				'\\',
				' - ',
				(string) preg_replace(
					'/([a-z0-9])([A-Z])/',
					'$1 $2',
					static::class
				)
			)
		);
		$this->setRiskyAllowed(true);
		$this->setUsingCache(true);
		$this->setRules(static::RuntimeResolveRules());
		$this->setIndent("\t");
		$this->setLineEnding("\n");

		$finder = new DefaultFinder();
		$finder->ignoreUnreadableDirs();

		$faffing = [];

		foreach (
			array_reduce(
				$inDirs,
				(
					static function (
						DefaultFinder $finder,
						string $directory
					) : DefaultFinder {
						if (true === is_file($directory)) {
							return $finder->append([$directory]);
						}

						return $finder->in($directory);
					}
				),
				$finder
			)->getIterator() as $finder_faff
		) {
			$faffing[] = $finder_faff;
		}

		$this->setFinder($faffing);
	}

	public static function createWithPaths(string ...$paths) : self
	{
		return new static(array_filter($paths, static function (string $path) : bool {
			return is_dir($path) || is_file($path);
		}));
	}

	/**
	 * Resolve rules at runtime.
	 */
	protected static function RuntimeResolveRules() : array
	{
		return (array) static::DEFAULT_RULES;
	}
}
