<?php
/**
 * @author David Leaman <fated@memethief.com>
 * 
 * Definition of the sections available for placement on character sheets
 */

$boxes = array();

/* "ID" section.
 * Here we can have player and character name, description, fate points, refresh, stuff like that. Probably a Dresden-style template would be here, or D&D-style character class.
 */
$boxes['id'] = _mkBoxStub('ID');

function _mkBoxStub($labels, $fields = array()) {
	list ($label, $sublabel) = (is_array($labels) ? $labels : array($labels, null));
	array_walk($fields, '_mkBoxStub');
	return array(
			'label' => $label,
			'sublabel' => $sublabel,
			'x' => null,
			'y' => null,
			'fields' => $fields,
			);
}
