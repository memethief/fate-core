<?php
/**
 * Default layout for FC character sheet
 *
 * @author David Leaman <fated@memethief.com>
 */

$boxes['id']['x'] = 0;
$boxes['id']['y'] = 0;
$boxes['id']['width'] = BOX_WIDTH_TWOTHIRDS;
$boxes['id']['height'] = BOX_HEIGHT_A_1;

$boxes['logo']['x'] = 'id';
$boxes['logo']['y'] =  0;
$boxes['logo']['width'] =  BOX_WIDTH_THIRD;
$boxes['logo']['height'] =  BOX_HEIGHT_A_1;

	/* "ASPECTS" section */
$boxes['aspects']['x'] = 0;
$boxes['aspects']['y'] =  "id";
$boxes['aspects']['width'] =  BOX_WIDTH_THIRD;
//$boxes['aspects']['height'] =  171;
$boxes['aspects']['height'] =  BOX_HEIGHT_C_2;

	/* SKILL MODES section */
$boxes['skillmodes']['x'] = "aspects";
$boxes['skillmodes']['y'] =  "id";
$boxes['skillmodes']['width'] =  BOX_WIDTH_TWOTHIRDS;
//$boxes['skillmodes']['height'] =  295;
//$boxes['skillmodes']['height'] =  325;
$boxes['skillmodes']['height'] = BOX_HEIGHT_A_2;

	/* EXTRAS section */
$boxes['extras']['x'] = 0;
$boxes['extras']['y'] =  "aspects";
$boxes['extras']['width'] =  BOX_WIDTH_THIRD;
//$boxes['extras']['height'] =  136;
$boxes['extras']['height'] =  BOX_HEIGHT_C_3;

	/* STUNTS section */
$boxes['stunts']['x'] = 0;
$boxes['stunts']['y'] =  "extras";
$boxes['stunts']['width'] =  BOX_WIDTH_THIRD;
//$boxes['stunts']['height'] =  137;
$boxes['stunts']['height'] =  BOX_HEIGHT_C_4;

	/* PHYSICAL STRESS section */
$boxes['stressphysical']['x'] = "stunts";
$boxes['stressphysical']['y'] =  "skillmodes";
$boxes['stressphysical']['width'] =  BOX_WIDTH_THIRD;
$boxes['stressphysical']['height'] =  BOX_HEIGHT_D_3;

	/* MENTAL STRESS section */
//$boxes['stressmental']['x'] = "stressphysical";
//$boxes['stressmental']['y'] =  "skillmodes";
$boxes['stressmental']['x'] = "stunts";
$boxes['stressmental']['y'] = "stressphysical";
$boxes['stressmental']['width'] =  BOX_WIDTH_THIRD;
$boxes['stressmental']['height'] =  BOX_HEIGHT_D_4;

	/* CONSEQUENCES section */
$boxes['consequences']['x'] = "stressphysical";
$boxes['consequences']['y'] = "skillmodes";
$boxes['consequences']['width'] =  BOX_WIDTH_THIRD;
$boxes['consequences']['height'] =  BOX_HEIGHT_A_3;

