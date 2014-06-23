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
$boxes['logo'] = _mkBoxStub('');
$boxes['aspects'] = _mkBoxStub('ASPECTS');
$boxes['skills'] = _mkBoxStub('SKILLS');
$boxes['skillmodes'] = _mkBoxStub('SKILL MODES');
$boxes['extras'] = _mkBoxStub('EXTRAS');
$boxes['stunts'] = _mkBoxStub('STUNTS');
$boxes['stressphysical'] = _mkBoxStub('PHYSICAL STRESS');
$boxes['stressmental'] = _mkBoxStub('MENTAL STRESS');

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
