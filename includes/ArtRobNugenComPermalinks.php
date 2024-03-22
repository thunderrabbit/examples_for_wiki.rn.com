<?php
// phpcs:disable MediaWiki.NamingConventions.LowerCamelFunctionsName.FunctionName

/**
 * ArtRobNugenComPermalinks for Example extension.
 *
 * @file
 */

namespace RobNugen;

require_once '/home/robuwikipix/art.robnugen.com/includes/mysql.php';
require_once '/home/robuwikipix/art.robnugen.com/includes/lilurl.php';

use Parser;
use PPFrame;

class ArtRobNugenComPermalinks implements
	\MediaWiki\Hook\ParserFirstCallInitHook
{

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
		$parser->setHook( 'permalink', [ self::class, 'renderTagNavigation' ] );
	}

	/**
	 * Parser hook handler for <permalink>
	 *
	 * @param string $input The content of the tag.
	 * @param array $args The attributes of the tag.
	 * @param Parser $parser Parser instance available to render
	 *  wikitext into html, or parser methods.
	 * @param PPFrame $frame Can be used to see what template
	 *  arguments ({{{1}}}) this hook was used with.
	 * @return string HTML to insert in the page.
	 */
	public static function renderTagNavigation( $input, array $args, Parser $parser, PPFrame $frame )
	{
		$base_url = "https://art.robnugen.com/";

		global $wgRequest;
		$prefix = "The permalink for this page is ";

		$fullURL = $wgRequest->getFullRequestURL();

		$actualURL = preg_replace('/\?(.)*/', '', $fullURL);	// wipe any URL params

		$mysql_safeURL = trim($actualURL);

		$lilurl = new \lilURL();

		$permalink_id = $lilurl->get_id($mysql_safeURL);

		if ($permalink_id != -1) {
			$permalink = $parser->recursiveTagParse($base_url . $permalink_id);
			return implode(" ", array($prefix, $permalink));
		} else {
			return "This page has no permalink on " . $parser->recursiveTagParse($base_url);
		}
	}
}
