<?php
/**
 * An extension that demonstrates how to create a new page action.
 *
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @copyright Copyright © 2005, Ævar Arnfjörð Bjarmason
 * @license GPL-2.0-or-later
 */

namespace MediaWiki\Extension\Example;

class MyActAction extends \FormlessAction {
	/** @inheritDoc */
	public function getName() {
		return 'myact';
	}

	/** @inheritDoc */
	protected function getDescription() {
		// Disable subtitle under page heading
		return '';
	}

	/** @inheritDoc */
	public function onView() {
		return null;
	}

	/** @inheritDoc */
	public function show() {
		parent::show();

		$this->getContext()->getOutput()->addWikiTextAsInterface(
			'This is a custom action for page [[' . $this->getTitle()->getText() . ']].'
		);
	}

}
