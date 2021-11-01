<?php
/**
 *
 * Copyright © 25.05.13 by the authors listed below.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @license GPL-2.0-or-later
 * @file
 *
 * @author Daniel Kinzler
 */
namespace MediaWiki\Extension\Example;

use Content;
use TextContent;

/**
 * Class XmlContent represents XML content.
 *
 * This is based on TextContent, and represents XML as a string.
 *
 * Using a text based content model is the simplest option, since it
 * allows the use of the standard editor and diff code, and does not require
 * serialization/unserialization.
 *
 * However, text based content is "dumb". It means that your code can't make use
 * of the XML structure, making it a bit pointless. If you want to use and manipulate
 * the XML structure, then it should be represented as a DOM inside the XmlContent.
 *
 * In that case, XmlContentHandler::serializeContent()
 * and XmlContentHandler::unserializeContent() would have to be used for
 * serializing resp. parsing the XML DOM.
 *
 * Also, a special editor might be needed to interact with the structure.
 *
 * @package DataPages
 */
class XmlContent extends TextContent {
	public const MODEL = 'xmldata';

	/** @inheritDoc */
	public function __construct( $text, $model_id = self::MODEL ) {
		parent::__construct( $text, $model_id );
	}

	/**
	 * Determines whether this content can be considered empty.
	 * For XML, we want to check whether there's any CDATA:
	 *
	 * @return bool
	 */
	public function isEmpty() {
		$text = trim( strip_tags( $this->getText() ) );
		return $text === '';
	}

	/**
	 * Determines whether this content should be counted as a "page" for the wiki's statistics.
	 * Here, we require it to be not-empty and not a redirect.
	 *
	 * @param bool|null $hasLinks
	 *
	 * @return bool
	 */
	public function isCountable( $hasLinks = null ) {
		return !$this->isEmpty() && !$this->isRedirect();
	}

	/**
	 * This is a last line of defense against storing invalid data.
	 * It can be used to check validity, as an alternative to doing so
	 * in XmlContentHandler::validateSave().
	 *
	 * Checking here has the advantage that this is ALWAYS called before
	 * the content is saved to the database, no matter whether the content
	 * was edited, imported, restored, or what.
	 *
	 * The downside is that it's too late here for meaningful interaction
	 * with the user, we can just abort the save operation, causing an internal
	 * error.
	 *
	 * @return bool
	 */
	public function isValid() {
		return parent::isValid();
	}

	/**
	 * Should return text relevant to the wiki's search index, for instance by stripping tags.
	 *
	 * @return string
	 */
	public function getTextForSearchIndex() {
		return strip_tags( $this->getText() );
	}

	/**
	 * Implement conversion to other content models.
	 * Text based models can per default be converted to all other text based models.
	 *
	 * @param string $toModel
	 * @param string $lossy
	 *
	 * @return string
	 */
	public function convert( $toModel, $lossy = '' ) {
		return parent::convert( $toModel, $lossy );
	}

	/**
	 * We could implement sections as XML elements based on their id attribute.
	 * If XmlContent was DOM based, that would be nice and easy.
	 *
	 * @param string|int $sectionId
	 *
	 * @return Content|bool|null
	 */
	public function getSection( $sectionId ) {
		return parent::getSection( $sectionId );
	}

	/**
	 * If we want to support sections, we also have to provide a way to substitute them,
	 * for section based editing.
	 *
	 * @param string|int|null|bool $sectionId
	 * @param Content $with
	 * @param string $sectionTitle
	 *
	 * @return Content|null
	 */
	public function replaceSection( $sectionId, Content $with, $sectionTitle = '' ) {
		return parent::replaceSection( $sectionId, $with, $sectionTitle );
	}
}
