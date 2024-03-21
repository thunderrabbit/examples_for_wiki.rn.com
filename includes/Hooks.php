<?php
// phpcs:disable MediaWiki.NamingConventions.LowerCamelFunctionsName.FunctionName

/**
 * Hooks for Example extension.
 *
 * @file
 */

namespace MediaWiki\Extension\Example;

use MediaWiki\Permissions\PermissionManager;
use Parser;
use PPFrame;

class Hooks implements
	\MediaWiki\Hook\ParserFirstCallInitHook
{

	/** @var PermissionManager */
	private $permissionManager;

	/**
	 * @param PermissionManager $permissionManager example injected service
	 */
	public function __construct( PermissionManager $permissionManager ) {
		$this->permissionManager = $permissionManager;
	}


	/**
	 * Register parser hooks.
	 *
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/ParserFirstCallInit
	 * @see https://www.mediawiki.org/wiki/Manual:Parser_functions
	 * @param Parser $parser
	 * @throws \MWException
	 */
	public function onParserFirstCallInit( $parser ) {
		// Add the following to a wiki page to see how it works:
		// <permalink>test</permalink>
		// <permalink foo="bar" baz="quux">test content</permalink>
		$parser->setHook( 'permalink', [ self::class, 'parserTagPermalink' ] );
	}

	/**
	 * Parser hook handler for <permalink>
	 *
	 * @param string $data The content of the tag.
	 * @param array $attribs The attributes of the tag.
	 * @param Parser $parser Parser instance available to render
	 *  wikitext into html, or parser methods.
	 * @param PPFrame $frame Can be used to see what template
	 *  arguments ({{{1}}}) this hook was used with.
	 * @return string HTML to insert in the page.
	 */
	public static function parserTagPermalink($data, $attribs, $parser, $frame ) {
		$html = '<pre>Permalink yay</pre>';
		return $html;
	}
}
