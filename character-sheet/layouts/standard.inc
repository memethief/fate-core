<?php
/**
 * Default layout for FC character sheet
 *
 * @author David Leaman <fated@memethief.com>
 */

$boxes['id']['x'] = 0;
$boxes['id']['y'] = 0;
$boxes['id']['width'] = BOX_WIDTH_TWOTHIRDS;
$boxes['id']['height'] = 137;

$boxes['logo']['x'] = 'id';
$boxes['logo']['y'] =  0;
$boxes['logo']['width'] =  BOX_WIDTH_THIRD;
$boxes['logo']['height'] =  137;

	/* "ASPECTS" section */
$boxes['aspects']['x'] = 0;
$boxes['aspects']['y'] =  "id";
$boxes['aspects']['width'] =  BOX_WIDTH_THIRD;
$boxes['aspects']['height'] =  171;

	/* SKILL MODES section */
$boxes['skillmodes']['x'] = "aspects";
$boxes['skillmodes']['y'] =  "id";
$boxes['skillmodes']['width'] =  BOX_WIDTH_TWOTHIRDS;
$boxes['skillmodes']['height'] =  295;

	/* EXTRAS section */
$boxes['extras']['x'] = 0;
$boxes['extras']['y'] =  "aspects";
$boxes['extras']['width'] =  BOX_WIDTH_THIRD;
$boxes['extras']['height'] =  136;

	/* STUNTS section */
$boxes['stunts']['x'] = 0;
$boxes['stunts']['y'] =  "extras";
$boxes['stunts']['width'] =  BOX_WIDTH_THIRD;
$boxes['stunts']['height'] =  137;

	/* PHYSICAL STRESS section */
$boxes['stressphysical']['x'] = "stunts";
$boxes['stressphysical']['y'] =  "skillmodes";
$boxes['stressphysical']['width'] =  BOX_WIDTH_THIRD;
$boxes['stressphysical']['height'] =  64;

	/* MENTAL STRESS section */
$boxes['stressmental']['x'] = "stressphysical";
$boxes['stressmental']['y'] =  "skillmodes";
$boxes['stressmental']['width'] =  BOX_WIDTH_THIRD;
$boxes['stressmental']['height'] =  64;

