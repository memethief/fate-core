<?php 

$dlmlib_xml_svg = './XML_SVG';
set_include_path(".:$dlmlib_xml_svg:".get_include_path());
// Use PEAR's XML_SVG
//require_once ("$dlmlib_xml_svg/XML/SVG.php");
require_once ("XML/SVG.php");
//print "Success!\n";

// Some general properties
$dim = array(
	"width" => 960,
	"height" => 720,
);
define('BOX_HEIGHT',     21   );
define('BOX_CORNER',      3.5 );
define('BOX_PADDING_X',  10   );
define('BOX_PADDING_Y',   0   );
define('BOX_MARGIN',      9   );
define('SECTION_MARGIN', 18   );
define('TOP_MARGIN',     37.5 );
define('LEFT_MARGIN',    18   );

$dim['inner_width'] = $dim['width'] - 2*LEFT_MARGIN;
$dim['inner_height'] = $dim['height'] - 2*TOP_MARGIN;

$doc = XML_SVG_Document::getInstance();

// root element
$svg = XML_SVG_Root::getNew();
$svg->viewBox = "0.0 0.0 {$dim['width']} {$dim['height']}";

// image clipping path
$clipPath = $svg->appendChild(XML_SVG_ClipPath::getNew());
$clipPath->id = $clipId = "p.0";
$clipRect = XML_SVG_Rect::getNew(0, 0, $dim['width'], $dim['height']);
$clipPath->appendChild($clipRect);

$layer = XML_SVG_Group::getNew();
$svg->appendChild($layer);
$layer->clip_path = "url(#$clipId)";
$layer->appendChild(mkGuideBox(0,0,$dim['width'], $dim['height']));
$content = XML_SVG_Group::getNew(LEFT_MARGIN, TOP_MARGIN);
$content->appendChild(mkGuideBox(0,0,$dim['inner_width'], $dim['inner_height']));
setTransform($content);
$layer->appendChild($content);

/* "ID" section */
//$groupID = XML_SVG_Group::getNew(0, 0);
$groupID = mkSectionGroup("ID", 0, 0, 605, 137);
//$groupID->transform = "translate(19,37.5)";
$groupIDFields = XML_SVG_Group::getNew(0, BOX_HEIGHT+BOX_MARGIN);
$groupIDFields->id = "groupIDFields";
setTransform($groupIDFields);
$boxName = mkOpenBox("Name", 0, 0, 488, 21);
$boxDescription = mkOpenBox("Description", 0, $boxName, 488, 77);
$boxRefresh = mkOpenBox("Refresh", $boxName, 0, 108, 107);
$groupIDFields->appendChildren($boxName, $boxDescription, $boxRefresh);
$groupID->appendChild($groupIDFields);
$content->appendChild($groupID);

/* LOGO */
$groupLogo = XML_SVG_Group::getNew($groupID, 16);
setTransform($groupLogo);
//$logoImage = XML_SVG_Image::getNew('url(./powered-by.png)', 1608, 625);
$logoImage = XML_SVG_Image::getNew(false, 300, 105);
$logoImage->href = "data:image/png;base64," . get_logo();
$logoImage->preserveAspectRatio="none";
$groupLogo->appendChild($logoImage);
$content->appendChild($groupLogo);

error_log("groupID y/height/bottom = {$groupID->y}/{$groupID->height}/{$groupID->bottom}");

/* "ASPECTS" section */
$groupAspects = mkSectionGroup("ASPECTS", 0, $groupID, 296, 171);
setTransform($groupAspects);
$groupAspectsFields = XML_SVG_Group::getNew(0, BOX_HEIGHT+BOX_MARGIN);
setTransform($groupAspectsFields);
$aspectFields = array(
	mkOpenBox("High Concept", 0, 0, 296, 21),
	mkOpenBox("Trouble", 0, 30, 296, 21),
	mkOpenBox("", 0, 60, 296, 21),
	mkOpenBox("", 0, 90, 296, 21),
	mkOpenBox("", 0, 120, 296, 21),
);
$groupAspectsFields->appendChildren($aspectFields);
//$groupAspects = XML_SVG_Group::getNew(0, $groupID, SECTION_MARGIN);
//$groupAspects->appendChild(mkGuideBox(0,0,296, 171));
//$groupAspects->appendChild(mkClippedRect("ASPECTS", 0, 0, 296));
$groupAspects->appendChild($groupAspectsFields);
$content->appendChild($groupAspects);

/* SKILL MODES section */
$groupSkills = mkSectionGroup("SKILL MODES", $groupAspects, $groupID, 610, 171);
/*
 * width = 610
 * 4 columns
 * 3*box_margin = 27
 * total column width = 583 ~ 146 * 4 = 115 + 156*3
 * height = 171
 * inner height = 141 = 23.5*6
 * 6 rows
 * total row height = 21*6 + 15 = 21*6 + 3*5
 * .: gutter is 3px
 */
$groupModes = XML_SVG_Group::getNew(0,BOX_HEIGHT+BOX_MARGIN);
setTransform($groupModes);
$skillGutter = 3;
$skillBoxSkip = BOX_HEIGHT+$skillGutter;
$skillRankWidth = 115;
$skillFullWidth = 610;
$skillModeWidth = 156;
$groupSkillRanks = XML_SVG_Group::getNew(0,0);
setTransform($groupSkillRanks);
$groupSkillRanks->appendChildren(array(
	mkLabel("Superb (+5)",0,$skillBoxSkip,$skillFullWidth),
	mkLabel("Great (+4)",0,2*$skillBoxSkip,$skillFullWidth),
	mkLabel("Good (+3)",0,3*$skillBoxSkip,$skillFullWidth),
	mkLabel("Fair (+2)",0,4*$skillBoxSkip,$skillFullWidth),
	mkLabel("Average (+1)",0,5*$skillBoxSkip,$skillFullWidth),
));
$groupSkillModeGood = XML_SVG_Group::getNew($skillRankWidth+$skillGutter,0);
setTransform($groupSkillModeGood);
$groupSkillModeGood->appendChildren(array(
	mkOpenBox("Good Mode",0,0*$skillBoxSkip,$skillModeWidth),
	mkOpenBox("Specialized",0,1*$skillBoxSkip,$skillModeWidth),
	mkOpenBox("Focused",0,2*$skillBoxSkip,$skillModeWidth),
	mkOpenBox("Trained",0,3*$skillBoxSkip,$skillModeWidth),
));
$groupSkillModeFair = XML_SVG_Group::getNew($skillRankWidth+$skillModeWidth+2*BOX_MARGIN,0);
setTransform($groupSkillModeFair);
$groupSkillModeFair->appendChildren(array(
	mkOpenBox("Fair Mode",0,0*$skillBoxSkip,$skillModeWidth),
	mkOpenBox("Specialized",0,2*$skillBoxSkip,$skillModeWidth),
	mkOpenBox("Focused",0,3*$skillBoxSkip,$skillModeWidth),
	mkOpenBox("Trained",0,4*$skillBoxSkip,$skillModeWidth),
));
$groupSkillModeAverage = XML_SVG_Group::getNew($skillRankWidth+2*$skillModeWidth+3*BOX_MARGIN,0);
setTransform($groupSkillModeAverage);
$groupSkillModeAverage->appendChildren(array(
	mkOpenBox("Average Mode",0,0*$skillBoxSkip,$skillModeWidth),
	mkOpenBox("Specialized",0,3*$skillBoxSkip,$skillModeWidth),
	mkOpenBox("Focused",0,4*$skillBoxSkip,$skillModeWidth),
	mkOpenBox("Trained",0,5*$skillBoxSkip,$skillModeWidth),
));
$groupModes->appendChildren(array(
	$groupSkillRanks, 
	$groupSkillModeGood,
	$groupSkillModeFair,
	$groupSkillModeAverage,
));
$groupSkills->appendChildren($groupModes);
$content->appendChild($groupSkills);

/* EXTRAS section */
$groupExtras = mkSectionGroup("EXTRAS", 0, $groupAspects, 453, 137);
$groupExtras->appendChild(mkOpenBox(null,0,30,453, 107));
$content->appendChild($groupExtras);

/* STUNTS section */
$groupStunts = mkSectionGroup("STUNTS", $groupExtras, $groupAspects, 453, 137);
$groupStunts->appendChild(mkOpenBox(null,0,30,453, 107));
$content->appendChild($groupStunts);

/* PHYSICAL STRESS section */
$groupPhysicalStress = mkSectionGroup(array("PHYSICAL STRESS", "(Physique)"), 0, $groupExtras, 296, 64);
$content->appendChild($groupPhysicalStress);

/* MENTAL STRESS section */
$groupMentalStress = mkSectionGroup(array("MENTAL STRESS", "(Will)"), 0, $groupPhysicalStress, 296, 64);
$content->appendChild($groupMentalStress);

/* CONSEQUENCES section */
$groupConsequences = mkSectionGroup("CONSEQUENCES", $groupPhysicalStress, $groupStunts);
$content->appendChild($groupConsequences);

// all done.
print $doc->saveXML();


/* UTILITY FUNCTIONS */

function mkSectionGroup($titles, $right_of=null, $below_of=null, $width=null, $height=null) {
	//error_log(__METHOD__ . " BEGIN");
	
	// Some default values
	list($title, $subtitle) = is_array($titles)
		? $titles
		: array($titles, "");

	$group = XML_SVG_Group::getNew(false, false, SECTION_MARGIN);
	$group->id = "sectionGroup-" . str_replace(" ", "-", $title);
	//error_log("margin: (" . $group->marginx . ", " . $group->marginy . ")");
	$group->marginx = SECTION_MARGIN;
	$group->marginy = SECTION_MARGIN;
	//error_log("margin: (" . $group->marginx . ", " . $group->marginy . ")");
	
	// positioning
	if ($right_of === null) $right_of = 0;
	if ($below_of === null) $below_of = 0;
	$group->right_of = $right_of;
	$group->below_of = $below_of;
	//error_log("about to setTransform");
	setTransform($group);

	// dimensions
	global $dim;
	if ($width === null) $width = $dim['inner_width'] - $group->x;
	if ($height === null) $height = $dim['inner_height'] - $group->y;
	error_log("width: $width, height: $height");
	$group->width = $width;
	$group->height = $height;
	$group->appendChild(mkGuideBox(0, 0, $width, $height));

	// Title bar
	$group->appendChild(mkClippedRect($titles, 0, 0, $width));
	//error_log("group properties: (" . var_export($group->properties,true) . ")");
	//error_log("margin: (" . $group->marginx . ", " . $group->marginy . ")");
	error_log("groupID y/height/bottom/transform = {$group->y}/{$group->height}/{$group->bottom}/{$group->transform}");
	//error_log(__METHOD__ . " END");

	return $group;
}

function setGroupCoordinates(&$group, $right_of, $below_of, $withPadding=false) {
	$group->right_of = $right_of;
	$group->below_of = $below_of;
}

/**
 * Create a "clipped rectangle": a rectangle with two oposite corners 
 * truncated, used as a section header.
 *
 * @param $label text label. To omit the label, set this to "" or something 
 *        equal. Note that if you want the label to be the number 0, this means 
 *        that you should pass it as a string "0" rather than as an integer.
 * @param $x the left x-coordinate of the bounding rectangle
 * @param $y the top y-coordinate of the bounding rectangle
 * @param $width the width of the bounding rectangle
 * @param $height optional height of the bounding rectangle. Defaults to a 
 *        standard height.
 * @param $corner optional width (and height) of the clipped-off part of the 
 *        rectangle. Currently this is symmetric, i.e, the clipping is at 45 
 *        degrees.  
 * @return XML_SVG_Group containing a Path and a Text.
 */
function mkClippedRect($labels, $x, $y, $width, $height = BOX_HEIGHT, $corner = BOX_CORNER) {
	list($label, $sublabel) = is_array($labels)
		? $labels
		: array($labels, "");

	$rise = $height - $corner;
	$run = $width - $corner;

	$group =  XML_SVG_Group::getNew();
	$group->id = "clippedRect-" . str_replace(" ", "-", $label);
	$group->transform = "translate($x,$y)";

	$path = XML_SVG_Path::getNew(
		"m $corner,0 " .
		"l-$corner,$corner v$rise h$run " .
		"l$corner,-$corner v-$rise h-$run " .
		"z"
	);
	$path->fill = "#000000";
	$path->stroke_width = 2;
	$path->stroke = "#000000";
	$path->stroke_linejoin = "round";
	$path->stroke_linecap = "butt";
	$group->appendChild($path);

	if (!empty($label)) {
		$text = XML_SVG_Text::getNew();
		$text->x = BOX_PADDING_X;
		$text->y = BOX_PADDING_Y;
		$labelspan = XML_SVG_Tspan::getNew($label);
		setTextProperties($labelspan, 'section-header');
		$text->appendChild($labelspan);
		if (!empty($sublabel)) {
			$sublabelspan = XML_SVG_Tspan::getNew($sublabel);
			setTextProperties($sublabelspan, 'section-subheader');
			$text->appendChild($sublabelspan);
		}
		$group->appendChild($text);
	}

	return $group;
}

/**
 * Create an empty rectangle, with an optional light text label.
 *
 * @param $label text label. To omit the label, set this to "" or something 
 *        equal. Note that if you want the label to be the number 0, this means 
 *        that you should pass it as a string "0" rather than as an integer.
 * @param $x the left x-coordinate of the rectangle
 * @param $y the top y-coordinate of the rectangle
 * @param $width the width of the rectangle
 * @param $height optional height of the rectangle. Defaults to a standard 
 *        height.
 * @return XML_SVG_Group containing a Rect and optionally a Text.
 */
function mkOpenBox($label, $right_of, $below_of, $width, $height = BOX_HEIGHT) {
	/*
	if ($dx != 0) {
		$dx += BOX_MARGIN;
	}
	if ($dy != 0) {
		$dy += BOX_MARGIN;
	}
	$x = $dx;
	$y = $dy;
	 */
	$group =  XML_SVG_Group::getNew($right_of, $below_of, BOX_MARGIN);
	$group->id = "openBox-" . str_replace(" ", "-", $label);
	$group->width = $width;
	$group->height = $height;
	//$group->right_of = $right_of;
	//$group->below_of = $below_of;
	setTransform($group);
	//$group->transform = "translate($x,$y)";

	$rect = XML_SVG_Rect::getNew(0,0, $width, $height);
	$rect->fill = "none";
	$rect->stroke_width = 2;
	$rect->stroke = "#000000";
	$rect->stroke_linejoin = "round";
	$rect->stroke_linecap = "butt";
	$group->appendChild($rect);

	if (!empty($label)) {
		$text = XML_SVG_Text::getNew($label);
		$text->x = BOX_PADDING_X;
		$text->y = BOX_PADDING_Y;
		setTextProperties($text, 'box-label');
		$group->appendChild($text);
	}

	return $group;
}

/**
 * Create a text label.
 *
 * @param $label text label. To omit the label, set this to "" or something 
 *        equal. Note that if you want the label to be the number 0, this means 
 *        that you should pass it as a string "0" rather than as an integer.
 * @param $x the left x-coordinate of the rectangle
 * @param $y the top y-coordinate of the rectangle
 *        height.
 * @return XML_SVG_Group containing a Text.
 */
function mkLabel($label, $right_of, $below_of, $width, $height=BOX_HEIGHT) {
	global $mkLabelRow;
	$mkLabelRow ++;
	$group =  XML_SVG_Group::getNew($right_of, $below_of, BOX_MARGIN);
	$group->id = "openBox-" . str_replace(" ", "-", $label);
	setTransform($group);

	$rect = XML_SVG_Rect::getNew(0,0, $width, $height);
	$rect->fill = ($mkLabelRow % 2) ? "#eee" : "#fff";
	$rect->stroke = "none";
	$group->appendChild($rect);

	$text = XML_SVG_Text::getNew($label);
	$text->x = BOX_PADDING_X;
	$text->y = BOX_PADDING_Y;
	setTextProperties($text, 'label');
	$group->appendChild($text);

	return $group;
}

function mkGuideBox($x, $y, $width, $height) {
	$group =  XML_SVG_Group::getNew();
	$group->class = "guide-box";
	$group->transform = "translate($x,$y)";

	$rect = XML_SVG_Rect::getNew(0,0, $width, $height);
	$rect->fill = "none";
	$rect->stroke_width = 1;
	$rect->stroke = "#0000ff";
	$group->appendChild($rect);

	$rect = XML_SVG_Rect::getNew(-2,-2, $width+4, $height+4);
	$rect->fill = "none";
	$rect->stroke_width = 1;
	$rect->stroke = "#ff0000";
	$group->appendChild($rect);

	return $group;
}

/**
 * Given a string class, set fill, font face and other properties accordingly.
 *
 * @param &$element the element to modify
 * @param $class the class of text to assign. This may correspond with a CSS class, as the "class" attribute is set to this value.
 * @return XML_SVG_Element the original object
 */
function setTextProperties(XML_SVG_Element &$element, $class) {
	$element->class = $class;
	switch ($class) {
	case 'section-header':
		$element->fill = "white";
		$element->font_family = "Montserrat, sans-serif";
		$element->font_size = "15pt";
		$element->alignment_baseline = "before-edge";
		break;
	case 'section-subheader':
		$element->fill = "white";
		$element->font_family = "Montserrat, sans-serif";
		$element->font_size = "13pt";
		$element->alignment_baseline = "before-edge";
		break;
	case 'box-label':
		$element->fill = "#ccc";
		$element->font_family = "Montserrat, sans-serif";
		$element->font_size = "13pt";
		$element->alignment_baseline = "before-edge";
		break;
	case 'label':
		$element->fill = "#000";
		$element->font_family = "Montserrat, sans-serif";
		$element->font_size = "13pt";
		$element->alignment_baseline = "before-edge";
		break;
	default:
		// do nothing
		break;
	}
	return $element;
}

/**
 * Given an SVG element, calculate its transform.
 *
 * @param &$element the element to modify
 * @return XML_SVG_Element the original object
 */
function setTransform(XML_SVG_Element &$element) {
	//error_log("margin: (" . $element->marginx . ", " . $element->marginy . ")");
	//error_log("right-of: " . var_export($element->right_of, true));
	//error_log("below-of: " . var_export($element->below_of, true));
	$x = 0;
	if ($element->right_of) {
		$x = $element->right_of;
		//error_log("x: " . var_export($x, true));
	}
	if ($x instanceof XML_SVG_Element) {
		$x = $x->right + $element->marginx;
		//error_log($element->id . "] x: " . var_export($x, true));
	}
	$y = 0;
	if ($element->below_of) {
		$y = $element->below_of;
		//error_log($element->id . "] y: " . var_export($y, true));
	}
	if ($y instanceof XML_SVG_Element) {
		//error_log($element->id . "] y.bottom: " . var_export($y->bottom, true));
		$y = $y->bottom + $element->marginy;
		//error_log($element->id . "] y: " . var_export($y, true));
	}
	$element->x = $x;
	$element->y = $y;
	$element->transform = "translate($x, $y)";
	error_log("element {$element->id} y/height/bottom/transform = {$element->y}/{$element->height}/{$element->bottom}/{$element->transform}");
}

function get_logo() {
	return
	"iVBORw0KGgoAAAANSUhEUgAABkgAAAJxEAIAAADWQHVcAACAAElEQVR42uzd" .
	"X4hdZ78f9sPQC0EpvUja4MKxfOGb3kTEUHCJrDcXhZZCcyXESzVIEAWsISrH" .
	"Fx6J10WNNeSgnOlBc0oTCQZbtFOjCquq4ik5jkaa8YXaSAp21FqTE4Es609c" .
	"+Z2ZnprKknX0yq/V5PyOYb/sd23v9ay99n7WWp/PIV8OztFIs9efvdZ6vut5" .
	"fucFAAAAAAAAAAAAY/E7PgIAAAAAAAAAAIDxUNgCAAAAAAAAAAAYE4UtAAAA" .
	"AAAAAACAMVHYAgAAAAAAAAAAGBOFLQAAAAAAAAAAgDFR2AIAAAAAAAAAABgT" .
	"hS0AAAAAAAAAAIAxUdgCAAAAAAAAAAAYE4UtAAAA+AlL188cPjO1sba5tLnk" .
	"0wAAAAAAoAqFLQAAAPgJe7ZPn967ErUtnwYAAAAAAFUobAEAAEChS7NrO1Yv" .
	"/6ev/c3/4L/4j6O25TMBAAAAAKAKhS0AAAAodPT23PO541HYiowKl08GAAAA" .
	"AIA0ClsAAADwW3x55O6Nuzd6q1qRUeHy+QAAAAAAkEZhCwAAAH6L3sUQ+zPq" .
	"XD4lAAAAAADKUtgCAACA3+Jf/F//8vS/PF1U2Dp5bnF9cb9PCQAAAACAshS2" .
	"AAAAoNCBuZmFg2f7C1t7tk+f3rvyeOvJ9JNpnxIAAAAAAMNT2AIAAIBCF84v" .
	"31s+VDTPVvz/+pQAAAAAABiewhYAAAAUijm0igpbMf+WTwkAAAAAgOEpbAEA" .
	"AMBPOHlucX1xf1Ft6/P312dufudTAgAAAABgGApbAAAA8BO+PHL3xt0bRYWt" .
	"qHP5lAAAAAAAGIbCFgAAAAzlrYXDr87uLKptbaxtLm0u+ZQAAAAAABhMYQsA" .
	"AACGcml2bcfq5aLC1tL1M4fPTPmUAAAAAAAYTGELAACAzvn09fv3U//snu3T" .
	"p/eu9Be2DszNLBw867MFAAAAAGAwhS0AAAA6ZOvdbz968eLN7//477148eiT" .
	"pw/K/4SYSatonq2YhcvnDAAAAABAEYUtAAAAOuTirls7fyxspc2ztbG2ubS5" .
	"VFTYOnp77vnccZ8zAAAAAABFFLYAAADohGcfP//+x6pW5PzKlW2pP21+38LU" .
	"iRNFta0odfnMAQAAAADop7AFAABAJ8R8Wr2Frcj13Q+Plf9pV1+6Pn1to6iw" .
	"dfLc4vrifp85AAAAAAD9FLYAAADohJhPq7+w9cHLn/2Q+jMPzM0sHDzbX9ja" .
	"s3369N6Vx1tPpp9M++QBAAAAAOilsAUAAEDLfXFqa+q3VbV689EnTx+U/8kX" .
	"zi/fWz5UNM/Wpdm1HauXff4AAAAAAPRS2AIAAKDlLrx987WfKmxd3HVrZ/mf" .
	"HHNoFRW23lo4/OrsTp8/AAAAAAC9FLYAAABorZg3a3BVK/Kdr1ffePHi2cfP" .
	"vy//t5w8t7i+uL+otvX5++szN7+zLQAAAAAACApbAAAAtNaVbXdeGa6wFbm+" .
	"++Gx8n9LVLKKCltR57ItAAAAAAAIClsAAAC0UMyVFfNmDV/YOvXhtfdS/8ZY" .
	"ALGothWLJ9ouAAAAAAAobAEAANBCMVfW8FWt3vzqyDdvlv8bL82u7Vi9XFTY" .
	"Wrp+5vCZKdsFAAAAAACFLQAAAFoo5spKK2xd3HVrZ/m/MebQ2rN9+vTelf7C" .
	"1oG5mYWDZ20XAAAAAAAUtgAAAGiVmB8rrarVm7GoYlkxk1bRPFtXX7o+fW3D" .
	"NgIAAAAA6DKFLQAAAFol5seqXtj69PX798v/7Rtrm0ubS0WFraO3557PHbeN" .
	"AAAAAAC6TGELAACAlog5sapXtSLnV65sS/2XRDGrqLYVpS7bCwAAAACgmxS2" .
	"AAAAaImYE2tUha3IL05tTZX/l8TSh0WFrVg20fYCAAAAAOgmhS0AAABaIubE" .
	"Gm1h64OXP/sh9d9zYG5m4eDZ/sLWnu3Tp/euPN56Mv1k2lYDAAAAAOgahS0A" .
	"AAAaL+bBGm1VqzcfffL0Qfl/1YXzy/eWDxXNs3Vpdm3H6mXbDgAAAACgaxS2" .
	"AAAAaLyYB6u+wtaVbXdeKf+v2ljbXNpcKipsvbVw+NXZnbYdAAAAAEDXKGwB" .
	"AADQYDH3VX1Vrch3vl59I/VfePLc4vri/qLa1pdH7t64e8N2BAAAAADoDoUt" .
	"AAAAGizmvqq7sBW5vvvhsfL/ws/fX5+5+V1RYSvqXLYjAAAAAEB3KGwBAADQ" .
	"SM8+fv79j3NfjaewderDa++l/msPzM0sHDxbVNt6vPVk+sm0bQoAAAAA0AUK" .
	"WwAAADRSzHc1nqpWb269++1H5f+1l2bXdqxeLipsXTi/fG/5kG0KAAAAANAF" .
	"ClsAAAA0Usx3Nf7C1sVdt3aW/9fGHFp7tk+f3rvSX9iK+bdsUwAAAACALlDY" .
	"AgAAoGG+OvLNm5OoavVmLMhY1slzi+uL+4vm2br60vXpaxu2LwAAAABAuyls" .
	"AQAA0DAxx9VkC1ufvn7/fvl/+cba5tLmUlFha37fwtSJE7YvAAAAAEC7KWwB" .
	"AADQGDGv1WSrWpHzK1e2pf4WR2/PPZ87XlTbilKXbQ0AAAAA0FYKWwAAADRG" .
	"zGuVQ2Er8otTW1Plf4tLs2s7Vi8XFbaWrp85fGbKtgYAAAAAaCuFLQAAABoj" .
	"5rXKp7B14e2br6X+LgfmZhYOnu0vbO3ZPn1674ptDQAAAADQVgpbAAAANEDM" .
	"ZZVPVas3H33y9EH53+jC+eV7y4eK5tmKWbhsdwAAAACA9lHYAgAAoAE+ePmz" .
	"H3ItbMVCjWVtrG0ubS4VFbaO3p57PnfcdgcAAAAAaB+FLQAAALIW81flWdWK" .
	"fOfr1TdSf7v5fQtTJ04U1ba+PHL3xt0b9gEAAAAAgDZR2AIAACBrV7bdeSXv" .
	"wlbk+u6Hx8r/dp+/vz5z87uiwtbJc4vri/vtAwAAAAAAbaKwBQAAQKaeffz8" .
	"+x/nr8q/sBWLNqY5MDezcPBsf2Frz/bp03tXHm89mX4ybX8AAAAAAGgHhS0A" .
	"AAAyFXNW5V/V6s2td7/9qPxveuH88r3lQ0XzbMX/r/0BAAAAAKAdFLYAAADI" .
	"1PzKlW1NK2xd3HVrZ/nfNObQivm0+gtbMf+W/QEAAAAAoB0UtgAAAMjOV0e+" .
	"ebNpVa3IWMAxFnMs6+S5xfXF/UXzbH3+/vrMze/sGwAAAAAATaewBQAAQHZi" .
	"nqomFrYiP339/v3yv/WXR+7euHujqLA1v29h6sQJ+wYAAAAAQNMpbAEAAJCR" .
	"R588fdDkqlZkLOaY5ujtuedzx4tqWxtrm0ubS/YTAAAAAIDmUtgCAAAgIzE3" .
	"VdMLW5GxsGNZl2bXdqxeLipsLV0/c/jMlP0EAAAAAKC5FLYAAADISMxN1Y7C" .
	"1oW3b76W+jns2T59eu9Kf2Er/rv9BAAAAACguRS2AAAAyML67ofH2lLV6s1Y" .
	"5LGsmEmraJ6tmIXLPgMAAAAA0EQKWwAAAGThg5c/+6GNha1Y5LGsjbXNpc2l" .
	"osLW0dtzz+eO22cAAAAAAJpIYQsAAIAJizmo2lfVioxFHtPM71uYOnGiqLYV" .
	"pS77DwAAAABAsyhsAQAAMGEXd93a2d7CVmQs+FjW1ZeuT1/bKCpsnTy3uL64" .
	"3/4DAAAAANAsClsAAABMzLOPn3//4sU7X6++0fbCViz4mObA3MzCwbP9ha09" .
	"26dP7115vPVk+sm0fQkAAAAAoCkUtgAAAJiYmHeq3VWt3ozFH8u6cH753vKh" .
	"onm2Ls2u7Vi9bF8CcvPVkW/efPHi09fv33/x4sq2O6/8WF2NhWKHP3NGqTf+" .
	"7IW3b77248+Mb5C08yoAAADAZClsAQAAMDFlh+2bnrH4Y1kxh1ZRYSvm37Iv" .
	"MYyotkTZpU0ZxaDejNn7GI+td7/96DcrWeM/u8a3SZxjo8jV9H0gt6Ms7fM8" .
	"/rf/8N0//LeP3p57Pnc88k8u3Tp+6y+nfRqxd40z4+8tK4rU1b+pcztjx9m1" .
	"ut4qp6wv69C1bded6wpHpaPSsYY9s4784tTW1I+fRm4vmeS8jeLusqw/+8W/" .
	"+Z/+u4/IZ//8Vz/71c/SPqu4u8ztU4p/VZrBn1VabqxtLm0uOfulUdgCAABg" .
	"AuLxUHeqWr3zxKQ9hj55bnF9cX9Rbevz99dnbn5nv8Jx11/i6Z2TKc/H5c0S" .
	"n17Us/Iv3UaFKwZLmuXUh9fey+mTTBsS6P3mimV80z6NSS2dHHt4Wf0166Xr" .
	"Zw6fKb0Pxlkrn31gVMdRbr9XW7MOtl3vjI/xPdiOuR5tWUdlU67k45vIlbyz" .
	"StOz93tkVIX4suKp1KSusYf5fNIUzU//+BePNx//Iu3ON8+9qEphK+5Nip4u" .
	"ls2443Peq0JhCwAAgAmIQfRuPp5Le7Dy5ZG7N+7e8IiEKrpZlCzKKMRUmT2o" .
	"a3vOpGbPGu2wX1Pm38ptoCtthsirL12fvrZR5XsqhmYn+7unveU/v29h6sSJ" .
	"KqXqGKLOp6TS1n1bNcS2G+11RQzAp503fOM4Km27Ls+u2ibOKmnXWrE/j/Mb" .
	"JOctlVZlixcn4kWR6s/K8nxuWaXQNvi5YtmMz9ncWtUpbAEAADBWOb+jlvOM" .
	"HeGthcOvzu4selziQQmDKWwNzqhHVHlXtX17S9NLWoMHRepboqh935VplZ3e" .
	"uaaivFX2J+QwTBL1i7JiYcS0ecViyLnpdb0iBnFVQ7pW4cr/usKWdVS29Xpe" .
	"hctZpemzcI1n/q08Z02uPs9WlTpRzs8tq1T6YvnCURW20mYRpp/CFgAAAGPl" .
	"4V2V9wVjANjjEtIobJUtVk5qiYpJiQfT+czrM85tneeyibktjJh2RMRcU2mf" .
	"QA4LtVRZGDHtd89hXrE6FkN0Haga4roiz/KWLeuo7MKsRRZSdFZpbsbLA/UV" .
	"EHN+SlBlnq14epb2meQ5t1baayTDPEssmwfmZhYOnnWuGxWFLQAAAMYqz7f3" .
	"mjVfRe8E7x6aMDyFrSpv6rd7mMewSu+2zmc+hty2S9ogQdp77bmVltLeZU/7" .
	"3XMbInK2UQ2x7eqYeSufZRNtWUdl1+7BlbecVXx39MvzpZ0q82y16ZlJ1E/T" .
	"7lKjvhZPC0dV2Epb8J0iClsAAACMSbxT7kFbb6Y9cImZtIoenaQtO0UXKGzl" .
	"s4je//u/fPNH3/zRZPeHOP+0ddHD6tt6VPOr/fDX/83/pP3Z3BbjqLKkb1m5" .
	"lZaqvNFeVj7V9hi6Gy2DuKohsjdHdV1RZWF0W9ZR2eWjz7KJ9swm3qfUUdvK" .
	"eRHAcc5PmefdcZWrhVggclRVrVhU0VlutBS2AAAAGBO1gFE9dokhGQ9QKEth" .
	"K4cZ8kK85/qP/sP/bd/y5vj3hHjEn8Nic90ZSt/6g//nv9p6nvZnc1sYcTzz" .
	"UuS2f46nrBbHZruHxwziqobIOq4rntz47m989zf+8c2L/9Y/+e9sWUelbMfS" .
	"2M1lz8x5vqUmbrvxXIfn+bQk7gTTDH5ymJZVCuIUUdgCAACgdjm/q9fcB09R" .
	"zPIYheEpbOU2vBpLCcSxHBWuuveBqF+oao1/W4fND7b+/tbfL/unchs4GVWJ" .
	"rUhuiyFWXxixudu6OwOBqiG2XTu+a2IWDVvWUSknO5vms3/+q5/96mddvuu0" .
	"Z46zyjPaq7X4afnM9jrOebbyfMW0yqzPg58Zls2Y6d9TtToobAEAAFC73BY2" .
	"yi3T3uiNpQ89TGF4Clt5Dq/Gg9Q926dP71358sjdG3dv1LH1LUqbw/Jwf7b1" .
	"bPpZ6VpebrMu1bFMXlOuGepeGDGf2dTq28oGcVVDZH3nmbTyty3rqJR1lGDu" .
	"/9UHv//gL3XzrtOe2fRXKfJ8faK+ebbyfE5S5TlDvBg2qqrWgbmZhYNnx/OC" .
	"WTcpbAEAAFCjeNBpNpfBGW/ypYlHJ/2PVKL84ZEKvRS28hxe7X2cWkdtS1Ur" .
	"t4rer3/+63/265+X/VO5velex9xLIedrhvoGinKbi7S+OQwM4qqGyBzmEbFl" .
	"HZVymG/86jNrnrt/4d//X5e6dlduz2zH8oh5zjhVx/djbgvQxzatsgx90XPC" .
	"tLw0u7Zj9bLnafVR2AIAAKBGigLDZ9rjmFj6xIMVhqGw1ZQFC6K29fWjX/7J" .
	"L//EGbjdiyQOL0qBTZ8bcrCcF0Ose2HE3I7W+gp5BnFVQ2Q+y7Daso5KOUxt" .
	"osqRuLG2ubS59F//zXf/zt+dq28m3dzYM9sxz1Zus/z2lilHdaWa5/1yla05" .
	"+Alh2YxnFJ6k1U1hCwAAgBrlNiNI+2boibd1ix6vvLVw+NXZnfZDgsLWeJZQ" .
	"SRP1yt7j9093/+k//dN/mvbT8ny8bmikity2aR2VtaYsoFzHwoixBGEXlrw0" .
	"iKsaIsdzXWHLOiplPjMYRYWi7gXQ82HPbNP3RW4vjYz2Liy3J5ZVZvONemic" .
	"Z0ZV2OpOzXSyFLYAAACohWrIOB8Enzy3uL64v+ghSyy4Zp/EUZnz4+MoX8YD" .
	"1hjUSdvKFqJtx1xTRfIZVKhjccCm7Lft/t3rXojNIK5qiHRUOiptu24WYmKR" .
	"si7UtuyZzZq1vYl3l9VrlHnOrVVlxu7BTwXL5tL1M4fPTL1gLBS2AAAAqEVT" .
	"5slox5BMVLKKHrXEgxv7JApb+T8uP/17S7/7P56N8lbaVv7g5c9+8PlPYsCg" .
	"jgGSfrm94z6q5bqashhiF373+hZDDAZxVUNkDiVRW9ZRKcc/w2jvfLrtrm3Z" .
	"M9v3Gkme27TKPFu5za0Vd/FpBj8PLJtxdqryRIKyFLYAAAAYsRi09rBs/G/u" .
	"xgKIRY9dPHBBYSv/x8f3/+qD33/wl9K2r6GR5j5kH15uCyOOajmSJpa8R7Uw" .
	"Yj4lPIuvqYbYdt2cZ8uWdVTKcdZiYp6t3mJELGTWprtOe2Zbl2uPa8Wmz7OV" .
	"5/5Z5eWfwU8Cy2aV2b5Jo7AFAADAiHk8N6mJ0Hvf2TWlOf0UthpQffjdP/+f" .
	"kuLxrmUQu7M8Yj5vhI+q4tPEvXdUc97kszXrHuRzlagaIqvkhbdvvmbLOipt" .
	"u5yuAdLmpOy/Z4+yRZter7JntvVaLs/nCcP/1nku71hlq0W5alRVrTgXeW42" .
	"fgpbAAAAjJjSwKSWWoiHvPGebv/Dl3iX1/7ZZQpbzXpTdniWQWz6e95l5bYw" .
	"YpXft4mLIY5qYcTcZiQdz/nKIK5qiKzy/WLLOiptu3bMtdk7z1bk/L6FqRMn" .
	"2nHXmc+eGXdJaZnbbFLjnP91sCgQN3F5+tzOmVWqn4Of/qVlLK3oudn4KWwB" .
	"AAAwMrFUh0e3kx0CP3lucX1xf9EjmKsvXZ++tmFf7abcClvDPy5vbg20m285" .
	"y65t9yoLdTVxMcRRDYzlM2iUNlNa2hJO6gWqIXL8s/Daso5KmVvdOWa/buti" .
	"ZPnsmaMVNf347fKZIXX8s6XmObvz4Jce85xbq8o9VNE5JC3jKaInZpOisAUA" .
	"AMDImOUlh8dtMYBa9CCmTW/uUlZuJY808bA1fpd4LJtznSvOis66/W8Sx5vZ" .
	"cZaLjJmWev9L/N80sa43nnm28vlkqizU1fRZOassjJjP8Zt2vZE2qJzPIG78" .
	"S+LbpH1Zh3y2XZw3qsyb0txSeB2D8Y5KR6Uc/9zYXx65e+Pujf679ZgvJ60S" .
	"nY+2Frb65fbiwXgKWzmff4oKlLn9a6s8JSg6e6RlO845TaewBQAAwAjEu4Ye" .
	"1+YzDHz09tzzueNFD2U8jummdhS2+kUtJrdF4qrMW9PErTnMLERVFo+LOlez" .
	"ZmOqe8gkn08jbaGupi+GWGVhxDhrNX12kLcWDr86W3qQOp8Bs/oqFG2V2xJX" .
	"oxVHQcx1kfMyWHX87o5KR+Wo7lt7a/f9GfXudsyhO6qafnyT9t+tx728PTP/" .
	"wlbIZ4nAtLuPWFwv7R48t5nG+guUuS1BXn1R9cHP+spmzNTl+3SyFLYAAAAY" .
	"gaYvaZRzxpB2WZdm13asXvZQhl5tLWz1ynNh1jrkMzAweFA5rQgyWPzM/GcX" .
	"S6sxDS+3wlPZgfY2XTmUXRgxn21XZTHEuKIo+2dVQ5qr3YWtnH9fhS3afVT2" .
	"zp4b36d5LjZX31yVRffsV1+6Pn1tw56Zf2ErnzvQtBpQleXw8nwBo/cONLc7" .
	"jirLqQ9+ylc2D8zNLBw865s0BwpbAAAAVBIPWNvxdmyeWWWpqXgEUzTtub23" .
	"a7pQ2Aq5PZYdrTzfEu7NGLQYjzwretVLt8PL5/u37PBDm64cys6Imc85qvoA" .
	"c9k/qxrSXF0rbOX5LaOwRXeOyriCasry32kF6M/fX5+5+V376hRdK2zlcHeW" .
	"9qJI7x4Y/3vaJ5DbcRpX2rndNVeZjS9mQSt6vpeWVbY4o6WwBQAAQCV5vn3e" .
	"vkybpWbwO7vxfp59uDu6U9jK7eFslSUPmnXWHWdVq1fOta0qpdth5FP9Gb60" .
	"1KbFENOO9HzKalUWQ1TYcs3fhcLWX1xRZzarZVu3rKPSUTn4yiH/mbfSvlUH" .
	"lyqaeM/etcJWXAHmthRg2edFVRbizOETKKpRNn0evmGe7JXNpi+62j4KWwAA" .
	"AFTSxAULurDcUuhdtKg/Y8DVPtwd3SlshXwe0Y52ADK3R8/VH0CPSs5VtrR3" .
	"qYeRW/lpmKHKti6jPMw3dT4DWmlzgcT7/b3XEs09TlVDmrvtxl/Yyu1M29Yt" .
	"66h0VA4WV1N5XglXuR6O8kSb5tnqWmErh983rSw4v29h6sSJURUE40rYs7tR" .
	"XXUP80wvLeNn+g7Nh8IWAAAAiXIrf7Q706a4DyfPLa4v7i96WPPlkbs37t6w" .
	"Pztm2/cAPZ9HxqMagMxzMcTxD5wPlts8KF1bGHGYocq2LqM8zBxj+QxhphXB" .
	"YxhPYaubulzYipqIwpaj0lGZw1GZc22r+ixHRXn1pevT1zbsmbndb+awN1aZ" .
	"zXfP9unTe1d697T4L1HQT/s02nqdP6lvt8GFzrK5dP3M4TNTvj1zo7AFAABA" .
	"ojwHxdudacuNff7++szN74oe2USdy/7cBV0rbLVvADK3hf/icXx9c0elyXOo" .
	"IK0cM7x85qwaPGjU1sUQh18YMZ8B5rSlWvtnYmjumTn+JXF+bnqmzavR3G03" .
	"qaKwwpaj0lGZz1EZn3Bu13vDLw/dq78M3Z/x/duUu84uFLZiD5zsdV2Ve7Go" .
	"ABbtb1EibPrWb26Jc5jneGUz5upLq+JRN4UtAAAASstzlhdTqQ8WD2iKHt94" .
	"cNMFCltNL2zltpBc3SWkdmz96mfvYeRWhCoaOmrrYojDHBf5XDulDSf3L4bY" .
	"9MJWm3I8i9IqbDX3+HVUOirbelTmufeWresNX8toyj17OwpbcTXbW8SM3yuf" .
	"FxerzOA7zLxNVRbOy3np0nHW6aqUdwc/wSubVRa7pG4KWwAAAJTmsX4TZ8UY" .
	"vNRClXcoaYquFbZi6Ki5x2y/3B58V3kAHUNT/bP19M78V2XB1tyWr6p7n8/t" .
	"9y0aQOrCIilFdYp8Zsgb1WKICluqId2phuRzBVXH7+6odFQ2fYHs+OZt7vJn" .
	"RZXo5lYunFXynHk9xB3WMPtblLra8eShWd8CwyyTOnxW2Y6Mh8IWAAAApXVh" .
	"wLV906rHg+A926dP710pmiDdvt1uXSts5XOmGpV8tl2VYblhln0Z1dBUbvM5" .
	"jaq6VySfN/77v6e6sBji4G2dz9YZ1WKICluqId2phkTNsemLKzkqHZW5HZVR" .
	"368+a1Q+R2iVMk2bihfOKnlWtUK8GDP8vVgcp02/N2nKXJgxq1nRU7u0rPIS" .
	"FOOhsAUAAEAJ+cwPYXL1ogWnBhv8eK7Kwzjy153CVj5nqlEtXRQFi6YPQw6/" .
	"4Muozk65fWeNanHMJu75XVgMcfAsVjlUSEe7GKLClmpIFwpbMZdkPhXwOra1" .
	"o9JROamjsndRtrhLTStv5XaPk7b1h1/+LP+7TmeVOq7fqr/4kXYvVuXVvty+" .
	"Q/O/4ytbpxucS9fPHD4z9YLsKWwBAABQQj5LjMm0B8GDJ8CP+TPs523VhcJW" .
	"FBnzeSgcbxW3b9ulDRj0DsuNZ6ggBgm6M3yb28KIvftJ14ZqeqtR+cwuNtrF" .
	"EBW2VEPaXdiKb5DcFiOuY6ZGR6WjMofCVuRbC4dfnd2ZVttq+tYf/jo5/5es" .
	"nFVGuy+lvarXK46p4UuB/RmL9Nkf6rs+qfJqU3/GHF3V5y9kPBS2AAAAGEpu" .
	"87vIKjP3DH4cHNOw2+fbp92FrThH5VYKqb5oRchtObmy4qwyqQUdujZ8m8/i" .
	"I/H7dm0xxP5qRT5LNY12MUSFLdWQ9hW2YlA8ftPcrihGNWeno9JRmXNhq8py" .
	"2Pm8WjbaT6OJs+Y4q1SZST1mpY3S8KjEPlO9APRnW8+mn02nfbfGt1i7t2CV" .
	"rRZ11VEVtqoU7Bg/hS0AAACG0rXFjNo93frg2TJMnN5W7StsxSPRnIcERvWo" .
	"PbffcbTnnLofPeczP8p4hm/zWRgxPvkuXz9EVSuHAaoqVY/RLsxkEFc1ZFTn" .
	"lriqqZLxG0XmP4tw2gx5jkpHZRMrSmmzPnensBX/lznfdTqrpJ3hq8+kVSRe" .
	"eonSVZV7sf/z6uf/8PN/mPZvaPcrHFXO+aO6U+6dp9Czr2ZR2AIAAOAn5LbE" .
	"mBzVgmtFD+ziv9vz2ye3wtbwQ6rxeDceg8bj7NyWKCoaTh6VfIY90mofUbSq" .
	"/gC66QN49Q23939r+36U1fe9qy9dn762obClGmLbtakCbss6KvOvKKUVkvKZ" .
	"YbTuwlb+d+vOKlX2nHj1oo7yVvXaVvW52POvR6fdHadtr1iysHqRrllLptJP" .
	"YQsAAICf4HFbW4dwBlco0paiIGe5FbbanaNaDDG383DaENSoCltpA3j5DAzE" .
	"XFNlff3f/PJv/fJvlf7Msxm2zGEQxecQtdeyTp5bXF/cr7ClGmLbtXUJSFvW" .
	"UdmmwlY+CxDXXdhK+/71BKmJWcfyiMPU8eub1y2W53bXH6ovVdmbcd3uqVcT" .
	"KWwBAADwEwx2tnVIIN6PbO5SC5SlsDWejPkIR/tWdNOXRBxVYeuP//eLjy/+" .
	"O2X/9nwKW2nn6v/j42u/d7X03pTPwoiTrWr5HOKMlGaYN/6bfjZTDbHtujy3" .
	"li3rqGxTYas7SyLmP4+Os0p95a1R3WOWrQqNdn/Lp145qTp1zHY2qqpWXLFX" .
	"n/+MSVHYAgAAoJB6RxeGY2OJsaJHP/EgybHgiJaTHaJremErHvFXeQBdZc6/" .
	"fGrHafvG79+b/3vz/27ZP2VhxBgK8jmkzes2/OwLTT+b+d6x7dq3mKkt66hs" .
	"ekUpqiRlf1rTZ1QtW9iKb+o87zqdVep+8lP9DBOL8R2Ym1k4eHbwnhb/N6Pd" .
	"Q+L6PH6X5m6LmC0sTdnjfXCmnTPJh8IWAAAAhSyo1IXJ2AcXKUyr3iYKW+OZ" .
	"0acL2y5tto9hZuupY3nWpg/fxueQ9s70qQ+vvdfV47F3EKXL1zP1LYaosKUa" .
	"IuvLOHuPdrZOW9ZR2ZTCVtorQ00v6JctcORc0XBWybka2CvusyY1l1tz95Mq" .
	"n3zakpTjrNMxfgpbAAAA/BYxGO8RWHemZC96tzIKFvH+peOi6RS26s74hLuw" .
	"7dLqoWUXRqy+MGtUdppenYlPIz69sn+2mwNm/dXJ+OTNvjm84euV9knVEFnH" .
	"kVtl3g5b1lHZ3MJWWk0/t9k007Z+2RcbFLbkqGpbRc+CxrOPNev1kviOTqtT" .
	"Dz+rWTtm2mN4ClsAAAD8Fh6xNTfTZr4ZXKRIKwqQG4Wt5g7L5VairTIw8NbC" .
	"4Vdnd47nAXRu32VlK33xWD8+jfjcmr7nTPZ4bPrCK7kthqiwpRoi86n2hrS5" .
	"GG1ZR+WkClsxm2Nk2t4b4kWCpr/C0aZF0JxVmrWEbv+zoCgVjee1vWY9o6hy" .
	"bi/78lLdrzaRD4UtAAAAfouuDWoaoO2tBZhova0UtvJ8p3l47Vj8Mc428/sW" .
	"pk6cGG0RpF9ub2yX1b9krYURqxSX42hV+xhs+MUQFbZUQ+RoZ+yoUtUK8d1q" .
	"yzoqmzXTc3W5LXxcdoa8uLpT2JKTmgW5/1lQvCgyznnW81++PO6n0qQd44Oz" .
	"SsmV3ChsAQAA8Btyez9VjnOS9sHDtFEdcIw0l8JWPg9t08RgWNOHBHrFg+Z4" .
	"27g3v/hrd/7gzl+p8pNzWwwxreIWCwNVn++wO8Nmg4/K7iyMOJ7FEBW2VENk" .
	"Pgsg9s6NZ8s6KrtT2MpzJtGy+gv6w2RaQXM88tkzRyWersTddGT8jnGF344r" .
	"wP7XacZZ24pjOecXR9Nmziv6bKtkzmVN0ihsAQAA8Bu6Ng+Hdyt7fXnk7o27" .
	"N4oeDEWdyzHSXApbo51VK60WGf6///LRrke7yv6pWOqiHfNs1S23uZTSZmLr" .
	"XzjDwojVB8hzG1rLZ39LGzAu+7fkM4gb/5LewdfmZtpy2M3ddu3IKLhU33Yx" .
	"lN5btXRUOiq7U9jK7WWGtM8hbaG0nBdEa19ha7B49pJP2SjtWVD/iyLj39Py" .
	"vNKI2b/SpF1dF2V8149z5jPGQ2ELAACAv5DbfCRyUkWKKASYer19FLaqZ1Sm" .
	"qvuf1v7nv3Pmadk/lef8QOOZQ6Lp32VpAycxQDKq83AXCtnD1ChzKz7msxhi" .
	"vK/fncJWlZkSuklhK8/vzf55OxyVjsouFLbynBc87ehO+/61JGI+ha3c7tTS" .
	"ngUNfnlvnPtbPi9XRAmvSgH3wNzMwsGzoypsRanOd1/7KGwBAADwF3Kbj0RO" .
	"atij6N1KE7A3ncJWlUfeoxpE7D2+yr4dG2WUPD+l6ss5VRefT57zJ6U96C9a" .
	"nM7CiFXefe9CPT1t/r+0IaXm7oeqIc4h45/3brSzLhUtZe6odFS2u7CVZ1Wr" .
	"SmF68OtSCltNKWyFuCJt7t3Z4L0u5otyZhtqT0iaOa+JM+pRncIWAAAAWZcA" .
	"5PgXRQpFRYEY0HXUNJHCVtm3aeNhcZWlD3v1v7V89aXr09c2yv6c3BZ/iYzZ" .
	"m0b1WaXJZ4Ck/5Opvrf0ZsynUvZntnthxLJzmLV1YcS0RVsG728KW+S27bo8" .
	"n1YY/HKFo9JR2dbCVs5nobh3KCte3kj7/lXYyrOwldsis2UVze87ztpQ089s" .
	"/UsVV8+4Vvet11YKWwAAABiAaXmmvc0/eGkGk7E3kcLWMAMtdcyBEQvY9T+0" .
	"TRtoyXlegSgnjfbTGywqYjnPEJm2GOIw72SXnaEttLWoVLYs2NYrn7T9LW0x" .
	"JtUQ9wuyvtpukcFVLUelo7J9ha2YJSjP1xWqvygVL28obLWpsJXPHXdaib9/" .
	"md3+THvpqDtntqL5L9Myfprvu3ZT2AIAAKC1w7eyyruVUTExJXubKGwVDaOO" .
	"diatXvEubNFCY2nz1cW/M+plOVff6h52jVpYbMH2LU43+O32KsXZK9vuvGIu" .
	"yfbONzbOxRBVQ7pGYatKxrm3imGqWo5KR2XTC1vxLRbl4zxnTh3tYohVCtNp" .
	"C2R3bc90VJY1zEsjdT8LavpnOMx93DAZr3ulvaJDsyhsAQAAdFo8WDSI0u6M" .
	"Ql6awW9YRqnLcdQUXS5sRYUoBn7iEXDds0BFVWuYpRDSFjjIeU6p/ne7R/Vp" .
	"xzBeU0oDaUWi4RfHSVsYMWaqaNPRnTZEGvIv/OW8GKJqSNcobE12r/v8/fWZ" .
	"m985Kslz2w1fa4itHBnFrKgzxk/I+YWEOu61qxSm45xgz8ytsJXPtWV9ha26" .
	"F+lT2Mq/lMloKWwBAAB0WrPeW5XjXyZp8DINpmdvlrYWtmJoJx6nRsZD3tjn" .
	"o54yTsNXtaosaNLE+YF65zMbpsIV9ayo40T5qVnDeGk1teGHSSyMGPtDFW0q" .
	"oIx/MUTVkK7JZ9vFt0lv7aM/8zzLxb+qyoyewxyzjkpHpcz/+7d6YTrnV6e6" .
	"WdjK83Wa+u5E6luUU2HrrYXDr87u9B3XHQpbAAAAHdXWxYBkHQtVFL37a5L2" .
	"ZsmtsDV4qLU/61iycLTKVm2qLIwYmjLP1vCVu3YUidLm1grxgH74/afLCyNW" .
	"+Zzbdy00/sUQm14NidJPb9m3TZlWIGjKthvmmjbnknqV5RHjqnvwN4Wj0lEp" .
	"859bK158Guf37zh1p7AVrwbleUeWtn8Ofz9b5R62Tdcb/aoXtnKeP486KGwB" .
	"AAB0VDsGa2UOM76k1QUYv9wGL9shhk4HLx46TMZsdmX/dtXbNp1p0+Za6PLC" .
	"iKOai6Xps41OajHEtIE69YLxZHzOo9XEAdSc73eqnMEGH7+OSkelzH9R5uHn" .
	"4m3i7Du5zdtXPWNbx+8Vmf+y2nUviVjfTG9dLmyl3dnRdApbAAAAHdWshaXk" .
	"ZN/pj0qKCdubTmFrtOJxdpXhlt6MB7tp/xIF3HbMm1JlroWuLYxYZU6LfjHr" .
	"SdcWY0qbEbD6UjjqBaoh4xxAjZnn8jzXxb1YldlDi45iR6WjUuY8d3W87FTl" .
	"+zeuGHO+S7JnNrfQX3ax7LSXjtp3vdErrbAVTxVyXuqU+ihsAQAAdE7TBybl" .
	"pAaHBpcJTNueP4Wt6mI/r77MwWiPo5wHpLu2LE7a2TUezVfZc7q2MGKVYlzR" .
	"EdS1xRDLLr7ZnzHHT9m/1yCuasj4B1BzXh4xbUB98LHsqHRUyrrvptPmUg3V" .
	"FyPOf35re2Zzzzll73PT6vttvd5I+wzr+yRpCoUtAACAzsl/8naZ52wcUSVp" .
	"7pu+KGylifeG6ytpjWqerViqw/mtiYtbVZlbK/K//+unDpz8puzf29yFEeNf" .
	"PloXd93a2bTPIa7oyqpeEExbDDEYxFUNmdQAas4V1SoLq/Uvj+iodFTK3O6j" .
	"Q/W5tepbhM6eacnOULZQWMdToK4VtqpcV9MOClsAAAAd0tyhWZnDEG8YPCdH" .
	"2rJcjIfC1jBiCCSWGar+Bnxa/t+7Hp58eDLt3295xGbN9lS9OlOlsBWaNTfb" .
	"aBdD7NXEymPa8P+kFkMMBnFVQyY1gNqd5REdlY5KWUdGsbuK6ncWTSl22DNz" .
	"+E4pK57kjPN1o7Zeb5QtbNXxGdIsClsAAAAd0sTZI2Ru88EMfjPYRO45U9jq" .
	"F/NSxDBn9QXCqueo3lE2k2LOD/F7ze9bmDpxovqeU2VxnGZdG9Qx4N0rhria" .
	"8mmkLck0qcUQg0Fc1ZDJnntzXh6x+ndK2qCvo9JRKet72SlUr0o3a05re2YT" .
	"XyaJWaUVthS2GD+FLQAAgE6IN7Y9upLV3xKONy/3bJ8+vXfFdO7N0s3CVuyx" .
	"saBnDJZERWZSs2eNZwAmahzNqp40ceiuymwoaYMi/Rln4yqzGzZrZqm0itLw" .
	"mlJfm9RiiLG/VfmEDeKqhuRQbMp5Nsoq2y5toTRHpaNS1ne9F0dl0b1z2Yw7" .
	"mvzvOu2ZTVw6PF69U9hS2GL8FLYAAAA6wSMzWZRpD6AHP86LIoLjLjdNL2zF" .
	"gEcMVMQ+FgWs3owyVjz0HNXQSH0Z/8IqcyMNFg/r1bbqWGSkSm0oylWjqgyO" .
	"quqX/35SfX6LJp4nc1sMsfr+5opUNSSHwlb+yyPWXU51VDoqZd1VrVC2utH0" .
	"xRDtmc1dqD3t3kRhq/pRr7CFwhYAAEAn5DkgIZs7eDB4lg6PnPLU9MLWqBYT" .
	"ySHjgXiVZcWGp7Y12kH0tHfWe6W9v173XAv5zyxV92KIzbpqmtRiiNUL2QZx" .
	"VUNyKGzleV1Ux+/oqHRUyrJ54e2br42iqjXaO5e4emzKXac9c/wZ165p+23c" .
	"TaTtmQpb/RS2KEthCwAAoOWatdSRnNSjvTSDH0WlLctCfRS2cshJDbeobVU/" .
	"T1avao1qGcQ65lrI/2ohbQgq7Zso5+XSmrsYYjCIqxqSW5mprcsjOiodlXJS" .
	"2yheyRjtXL/NurO2Z44/40o+TcyfqrClsMWkKGwBAAC0XLwh6gGWrOMB3+Dy" .
	"QbPeA+4Cha1JZTyEzWGgJWblicKH8944F8SJrT/aobs6FtPMs9IXVzJpn3na" .
	"En5RzrMYYh2LbxrEVQ3JrbAVZ/icC83V68KOSkelHFzKj7uk6mLZ69Fe743q" .
	"+3ec7JnjzPXdD49Vu0Opsn/O71uYOnHC9UYvhS3KUtgCAABorRiY9wBL1jcc" .
	"HmKWl6LZOOKxteMxBwpb4y9pjWq5utGKwWl13sEZSwRWr2rFObD6UnT1za3V" .
	"K8+FEdMGouKMUWVeqDwXRkxbDLHs0FEdiyEGg7iqIXkuF5jzLINp8+o5Kh2V" .
	"cnDG7HrVr/Tqu95r7qzV9sz8q1qhytxa9b2kp7BF1yhsAQAAtFbOC3zIPDNt" .
	"GHhwjaaOOWBIo7BVX0YpJB5YxzIoTdkr4oG4pRIj43OosqRIv+oDIeM8r+ZW" .
	"WYgtkjaY2jtomvaJ5TbcmLaEcQwh57AYYp6fqmpIE7ddHYWtv7jSyLjKXN/y" .
	"iI5KR2V3Ms4eo521Lr5nqxej2zG3lj1zPNfG1ata8VpR9b10VIX+Nl1vKGxR" .
	"lsIWAABAaxmAl2nvGZc1eCL9GDJ3POZAYWu09axYAKIdlcQYuIpH0l0ewEsr" .
	"rRapo6pV39xaeV4/xIxfZUVpsvpyLbnNVJr2HR3nqHwGjA3iqobkXNjq5vKI" .
	"jkpHZRdmkh7Vooe96ptVq7lza9kz85/9N4xqv61jL1XYomsUtgAAAFoo3rfz" .
	"SEumva+ZZnA1oVlzDrWVwlZaMSseocbsWXkucVjHN0iei8HVMV9RHQN4dVS1" .
	"IsezB+azMGLabGdxtPZ/emlL9MZiZM0takRZLZ+5EwziqobkXNgK+S+POKoB" .
	"e0elo7KtV3dRcR5tEb9X3VWtOpaZGyd7Zs53K0XXyfm8RqKwRdcobAEAALRQ" .
	"PoOLsomZNsH+4En1m7ugQ5sobPXXsHrLWJFRTWjuG+2jEoPB8bi8TeWt+F2q" .
	"LyNSpL6q1jjPojnUvqsUiGMAaVRLSeYwaNSOxRDz+TxVQ5q+7eoubP3FVUfG" .
	"yyOmzbfnqHRUtrXOEkXz0S5pXSReQ6qvqtWO2antmdVn0qpjf64+6+p4aoUK" .
	"W3SNwhYAAECrxOwLHnLJ6u/upykaJq8yuwmj0rXCVjcfff7657/+Z7/+eR0/" .
	"OUo8TSwEx6P2+obx4sxWX1UrSjPjPH9GXa8diyFWPyfk8GlMajHEtKUkBzOI" .
	"qxrSlMJW/ssjjmrOFUelo7IpxayoUcZvXd/sWYO/VeOqrL75fdsxL7WzyvAv" .
	"J8Q3WtxnjXb2xF6DX67LbV9V2KJrFLYAAABaJZ9ljGTTM23ppcHDw1G4cZxO" .
	"isJWd/zqZ7/6R7/6WX0/PwbJokSS2/xbUSkbz2Be3Qvi1LEgXYkjbqKzy6R9" .
	"Bw1Tm0ubP6+Jn0b1xRDT5iQbzCCuakhTClsh5+UR4/u3+gC/o9JROdlrtjii" .
	"e8tYkXUsWp12pTeqJeQGZ5vulJ1V4vwc+3ZkfCbxnTLOumHUqkZbNKx7HjiF" .
	"LbpGYQsAAKAlcpgBQrZvKv6y4qF20QPBmH/L0TrZs0QMfuSQZSlslfXFX7vz" .
	"B3f+ynj+rhh4iLfD4+xRd5ErBvni74q/d5yDH/Ge+uA5BXNeamSo/efPh5R6" .
	"h5rGkzFkm2aY4ai0AdFmfRrVF0Osb17MOE7z+SZqa9ZxPsxn26VVGKvIeVtX" .
	"L2w5Kh2V+Vzt5yPq8nVf6Y1/2etx3hc4ridrtAsg1l3ob9P1hsIWZSlsAQAA" .
	"tIS3GGUdmTYINHiOk0nNFkPTKWyVFWWLf/zzf/KXP/4fJvsviYfd8eC7d+6E" .
	"3uyfWaF/loXxD9JX3w/T0t5bVnyz5DArQA7yXAwRcjhL+ByAIjEHZ/X5KfOZ" .
	"qQh3zaNNL+ANQ2GLshS2AAAAWiK3RalklxfviIfdhoEZLYWtNLEQRgwIpS0G" .
	"R3xudS992Dt0V8fMRu02zGKI1RdGbMw1YZaLIcJkxVVBzI/o0wB6r/HKXkW4" .
	"3iPPPblsVahs+g4d/nrDUwuGp7AFAADQeLFMj2qRrCOjCJhm8IMqxRHKUtiq" .
	"onfGnfgkDRENFp/PeObTiozl/GyXtC01zGKI+Sw3Wbc8F0OEyYqrgpgdxB4O" .
	"XTapkpaqFvXdrZS9Ejb7b93XGz5bhqewBQAA0HgfvPzZD6pFss6MUmBZg5dk" .
	"avdgOXVQ2Kqu96iMx/rm0Sn6lOoe9uivasVcaD7/0X7XdG1Jl+GXhjQLJl3T" .
	"O4DqKhS6I0otcbUwzuUOVbWo++o3rma9UpLz9YanFgxDYQsAAKDBHn3y9IE6" .
	"kaw/L7x987XUvbSo9BD/3VHM8BS2RqW/4BKP+7tZ3uodxhvPsIeq1mhVGXxt" .
	"3ydffb4QJU7aqn8A1dJO0FZRX47vxHFW8AeXoVVeSBNzwsW98PjvVnxXVr/e" .
	"8NSCwRS2AAAAGuzKtjuvqBPJcWUUBMsaXLIxMMyo9iWPPssqmkcq/kt82m1d" .
	"urR32GNSw3gxy4KqVhUx8FllK8RQbps+k+r7s+Fk2qp/ANXyiNB0cR0VV3ST" .
	"nT2rKOPfZkuRdp8y2b3as5pRXW94asFgClsAAACN9Ozj59+/ePHO16tvKBLJ" .
	"ceWnr9+/X35fjQeOHk5RncJWHWKga3DJI4YK4pF9Ewe2e+fQKvsA3YI4OUtb" .
	"DLE3f+/47O++/R+149OwGCIMVnT+tzwi5Cxm94lv/Dha41jOYd6swfOnmpeI" .
	"Ye7CevftuEfIYR9W1arjesNTC4oobAEAADTS+u6Hx1SI5HgzCoJpBr8bao6Z" .
	"8fjl3974z375JzF40MQsu9SXR5/Di2Nw+EGCOKKjQpfbcFT8e+LflkM9q93z" .
	"OU1W9WGtNhU1qi+GOJ5ZQO7uu/fH9/64ud9EbcrHv3i8+a//X0+2+/cdfMaI" .
	"wem2btkq+eDf+1eH/tUhx4ujsnrGURbfNb3ZW8PK7cot7UpPKb/L3/X9e3js" .
	"FbFv51PJyrmq1fT9p+xW9tQChS0AAIBGOvXhtfdUiOQkMsqCZcWjKyWGyYrh" .
	"kOYOgZRNjz7LiuGlKvtJfOa9QxRx7I9qacUolvUOh/QO8uU8y0LMftSs/eGP" .
	"/vN/8Mt/8Du9A6i5ZfWtM/yeefzLP3z3vz2Z86dRfa6R8SyB2vTBeNmdzH9Y" .
	"XUqZz7nCfFq+65ubud2tdG3/8dQChS0AAICG+erIN2+qDcnJ5Qcvf/ZD6t57" .
	"YG5m4eDZokeE3kWum8IWw4tH9nUvN1N3HSeHPXA8JZg6VJ+xKf/hVZ9G2qdR" .
	"hUFc2ZSMq6bBc8RKKbuZcVdr2Tjf9e5W7D+eWlCdwhYAAEDDXNx1a6fakJx0" .
	"br377Ufl996YEWeyizF1mcIWZUWNst1VlTreUG/HAF6U9tq6pcp+4/g0RsUg" .
	"rmxWYSu+B+uuL0splbTaxHd9/ncrOS8LrrBF1yhsAQAANMazj59/ryok88go" .
	"DpY1eNArHoI70uujsEUVsRyhAZhhhvbbtN3bWlNIm0ugaJ7Ibn4aaZxDZLPO" .
	"6rHftruyKaUsyphjT0nLd307Ml7Cyf9uRWGLrlHYAgAAaIxPX79/X1VI5pHv" .
	"fL36xo8lwrIGz9bz+fvrMze/c7zXQWGLUYnj1GBMnM2au+hh9TN2184M7TuL" .
	"jnMxxOC8IZtY2AqWR5SyC3NoxayT7b66813ftZm0mrU/K2zRNQpbAAAAjTG/" .
	"cmWbqpDMKaNEWFbM0zP4PWbHex0UtqhDlLe6MIzdO+zRvpm0irRvXpkq82QM" .
	"/v6yGOIwDOLK5ha2LI8oZZuu6OLaNb4H4/vdVb3venPC2X88tWD8FLYAAAAa" .
	"4ItTW1PqQTK/jBJhHY/hvNNcB4Ut6hZHbgx9xcw9bRr26E5Jq1+bCgrVt2Ob" .
	"FkYc/7etQVzZ3MJWsDyilM2aRTLmCo0j2lzOvuvbt2/Ht5L9x1MLmkthCwAA" .
	"oAE+ePmzH9SDZK751ZFv3iy/V0cBouwgGVUobDF+MWNB7Hs5P3zvnWtBPatf" .
	"OxZGHNUMju04l45/McRgEFc2vbAVLI8o5aSWLIzvkcg4TiOjtqKSlQPf9dX3" .
	"8PiWiX07Xobpzr6tsEXXKGwBAABk7dEnTx+oBMm88+KuW8lDvkWztsTDSmcA" .
	"aKsYcugtco1n1qL4u6J+ZBGc4fUW75qboxroilmpfBoAAABUobAFAACQtSvb" .
	"7ryiEiSbkFEuLGvwPCUxz43zAHRN1IOiUBLngbQySqS5sgAAAIDcKGwBAABk" .
	"6tnHz79/8eKdr1ffUAaSTchPX79/v/x+HvOUmBweAAAAAOgOhS0AAIBMre9+" .
	"eEwNSDYn51eubEvd2+f3LUydOFFU24pSl3MCAAAAANAOClsAAACZOvXhtffU" .
	"gGTTMoqGZV196fr0tY2iwtbJc4vri/udEwAAAACAdlDYAgAAyM5XR755U/VH" .
	"NjM/ePmzH1L3/ANzMwsHz/YXtvZsnz69d+Xx1pPpJ9PODwAAAABA0ylsAQAA" .
	"ZOfirls7VX9kk/PRJ08flN/zL5xfvrd8qGierUuzaztWLzs/AAAAAABNp7AF" .
	"AACQkWcfP/9e3Uc2P6N0WFbMoVVU2Ir5t5wlAAAAAICmU9gCAADIyKev37+v" .
	"7iObn+98vfrGjwXEsk6eW1xf3F9U2/r8/fWZm985VwAAAAAAzaWwBQAAkJH5" .
	"lSvb1H1kW3J998Nj5Y+CL4/cvXH3RlFhK+pczhUAAAAAQHMpbAEAAGQhqi0q" .
	"PrJNeerDa++lHhFvLRx+dXZnUW1rY21zaXPJeQMAAAAAaCKFLQAAgCx88PJn" .
	"P6j4yDbmV0e+ebP8EXFpdm3H6uWiwtbS9TOHz0w5bwAAAAAATaSwBQAAMGGP" .
	"Pnn6QK1Htjcv7rq1s/xx8XjryfST6T3bp0/vXekvbB2Ym1k4eNbZAwAAAABo" .
	"IoUtAACACYs6i1qPbHc++/j59+WPjphJq2ierasvXZ++tuEcAgAAAAA0i8IW" .
	"AADAxESF5Z2vV99Q6JFtz09fv3+//DGysba5tLlUVNg6envu+dxxZxIAAAAA" .
	"oFkUtgAAACZmfffDY6o8shs5v3JlW+qREsWsotpWlLqcTwAAAACAplDYAgAA" .
	"mJiosKjyyO7kF6e2psofKbH0YVFhK5ZNdD4BAAAAAJpCYQsAAGACvjryzZvq" .
	"O7J7+cHLn/2QetQcmJtZOHi2v7C1Z/v06b0rj7eeTD+Zdm4BAAAAAPKnsAUA" .
	"ADABF3fd2qm+I7uajz55+qD8UXPh/PK95UNF82xdml3bsXrZuQUAAAAAyJ/C" .
	"FgAAwFhFVUVlR3Y5r2y780r5Yyfm0CoqbL21cPjV2Z3OMAAAAABA/hS2AAAA" .
	"xurT1+/fV9mR3c53vl5948WLZx8//778EXTy3OL64v6i2tbn76/P3PzOeQYA" .
	"AAAAyJnCFgAAwFjNr1zZprIj5YsX67sfHit/BEUlq6iwFXUu5xkAAAAAIGcK" .
	"WwAAAGMS9RQ1HSkjT3147b3Uo+nA3MzCwbNFta1YPNE5BwAAAADIk8IWAADA" .
	"mHzw8mc/qOlI+Zu59e63H5U/mi7Nru1YvVxU2Lpwfvne8iHnHAAAAAAgTwpb" .
	"AAAAtXv0ydMHqjlS/ra8uOvWzvLHVMyhtWf79Om9K/2FrZh/y5kHAAAAAMiT" .
	"whYAAEDtopKimiNlUT77+Pn35Y+sk+cW1xf3F82zdfWl69PXNpx/AAAAAIDc" .
	"KGwBAADUKGoo73y9+oZSjpTF+enr9++XP7421jaXNpeKClvz+xamTpxwFgIA" .
	"AAAAcqOwBQAAUKP13Q+PqeNI+VM5v3JlW+pRdvT23PO540W1rSh1ORcBAAAA" .
	"APlQ2AIAAKhR1FDUcaQcJr84tTVV/ii7NLu2Y/VyUWFr6fqZw2emnIsAAAAA" .
	"gHwobAEAANTiqyPfvKmCI2WZvPD2zddSj7gDczMLB8/2F7b2bJ8+vXfFGQkA" .
	"AAAAyIfCFgAAQC0u7rq1UwVHyvL56JOnD8ofcRfOL99bPlQ0z1bMwuW8BAAA" .
	"AADkQGELAABgxKJuonYjZVpe2XbnlfLH3cba5tLmUlFh662Fw6/O7nR2AgAA" .
	"AAByoLAFAAAkurvv3h/f+08+f3995uZ3VbJ9n8ynr9+/r3YjZWq+8/XqG6lH" .
	"38lzi+uL+4tqW18euXvj7g1nbwAAAADa5/EvHm8+/kXZ5/OPt55MP5n26Y2f" .
	"whaM2Acvf/aDIRYppZRSNiejWlTW4Jlshs+oVrTvmnB+5co2e5eU1XJ998Nj" .
	"5Y++eMzUtXOOc5GUUkoppZRSSill+/KrI9+8ObonY0VZ9rXq8fzuZf8NaSMd" .
	"wyh6PVthCxS2pJRSSikr5bOPn39f/ppn6fqZw2emqhe22jfDVlRM7FdSVs9T" .
	"H157L/VIjAUQi8483Xlr0Gx/UkoppZRSSimllApbXShsRW69++1Ho3u2Fj8t" .
	"7V+osAUKW1JKKaWUhXlx162d5a92ouiwZ/v06b0rVapaB+ZmFg6edTUopRyc" .
	"aQ9ZLs2u7Vi9XHT+uXB++d7yoS7cn0YlN5aYtC9JKaWUUkoppZRSKmxVL2zF" .
	"K4LDZNE8WMNkWmGryiugZZ/2K2yBIToppZRSyjHd7A1Tgxg+4+e06Trw0SdP" .
	"H9ivpGxCtbSthdEi8Rnal6SUUkoppZRSSikVtsa5DkYdJadhZvmqvjziMPPW" .
	"K2yBwpaUUkopZUYLjQ2TUaFo35JkKhFSNmvx1qsvXZ++ttGFu9TBk7dLKaWU" .
	"UkoppZRSSoWtphe2+ueYj1es017M7v1pRbPXK2yBwpaUUkopZelc3/3wWPnr" .
	"nLSbvf48eW5xfXF/m64ALTomZd2Z9lbcxtrm0uZS0bloft/C1IkT7lWllFJK" .
	"KaWUUkoppcJW0wtbX5zamvrN/xJPw6o/Q+v/yQpb4CG4lFJKKWXpjFJR2lw1" .
	"UbSqXtiKCkWbrgCHmR5ZSlkl51eubEs9Qo/enns+d7w7Z6QiUdW1L0kppZRS" .
	"SimllFIqbLWvsBUuvH3ztdQXQfufnsVPq+93UdgChS0ppZRSdiivbLvzSvkr" .
	"nFi+sHpVK2oT7bsCjCqJvUvKujPeZisrlj4sOi/FsonduWM1F6CUUkoppZRS" .
	"SimlwlZbC1v9q2HE/z54ecSiP9X76rvCFihsSSmllFJWyrRV2y+cX763fKh6" .
	"YevS7NqO1cttuvaLG2b7lZTjybRpzMOBuZmFg2f7z0t7tk+f3rvSnTtWMwJK" .
	"KaWUUkoppZRSKmy1tbBVNFfW4OeKw8zLpbAFCltSSimllBkVHYbP+Antu/br" .
	"v5GTUjaxeNq+OmmR+PTsRVJKKaWUUkoppZQKW20tbBU1N/prWDGj/zAjKQpb" .
	"oLAlpZRSSpnRUmLDZ/sWHVN6kLJNS7u+tXD41dmd3blvvbjr1k77kpRSSiml" .
	"lFJKKaXCVnsLW/EMv2ihw8j5lSvbhls8UWELFLaklFJKKUtn3HKkOXp77vnc" .
	"8eqFrY21zaXNpTZd9VlWTMpJZe+DlbJOnltcX9xfdKb68sjdG3dvdOG+1XKu" .
	"UkoppZRSSimllApb7S5sFT3Jj3Uz+l9o7J9/S2ELFLaklFJKKSvl4NuMIlGx" .
	"ql7VinpE+676et/LkVKOP9d3PzxW/siNSlbXzldFet8glFJKKaWUUkoppZQK" .
	"W+0rbA3T4ihaBlFhCxS2pJRSSikrZdo8NLGIYfXCVn03e5MSNRH7lZSTzVMf" .
	"Xnsv9SiOBRCLzlqxeGIX7l6dzaSUUkoppZRSSikVtrpQ2OpfHnGYZRAVtkBh" .
	"S0oppZQyMWNS37KirLBn+/TpvStVqloH5mYWDp51vSelzOehVbg0u7Zj9XLR" .
	"uSvqql24e406r/kCpZRSSimllFJKKRW22l3YCv3LIw6/PonCFhjAk1JKKaUs" .
	"kVvvfvvRqKsMw2f8nDZd6cXnab+Ssum11FBUS21r2bRIfIb2JSmllFJKKaWU" .
	"UkqFrXYXtkLM3D/MMogKW6CwJaWUUkqZ0WJhw2TUINq3rJhag5TdWfj16kvX" .
	"p69tdOEeNqZ8txdJKaWUUkoppZRSKmx1obAVL2YPswyiwhYobEkppZRSls71" .
	"3Q+Plb+SSbud68+T5xbXF/e36RrPwmFS5pzDT13ea2Ntc2lzqeg8dvT23PO5" .
	"4+5kpZRSSimllFJKKaXCVpsKW/n8jQpb4DG3lFJKKVuVUSpKE0Wr6oWtqEG0" .
	"6Rqvf217KWU+Ob9yZVvq0T2/b2HqxInunM2KRM3XviSllFJKKaWUUkqpsKWw" .
	"pbAFCltSSimllKXzyrY7r5S/honlC6tXtdo6J03UQexdUrZvZsFY+rDonBbL" .
	"Jnbnfta5TkoppZRSSimllFJhS2FLYQsUtqSUUkopS2fZldfDhfPL95YPVS9s" .
	"XZpd27F6uU1Xd3FLbL+SMv+Me7E0B+ZmFg6e7T+n7dk+fXrvSlRau3A/azZB" .
	"KaWUUkoppZRSSoUthS2FLVDYklJKKaUskRfevvnaqMsKw2f8hPZd3cWnau+S" .
	"ssul1fZVUYs8+/j59/YiKaWUUkoppZRSSoWt0b0c2Jv5/+Rx/o0KW6CwJaWU" .
	"UsqW5BenthIW7hq8HNjw2b6Fw6L2Yb+Ssll5cdetneWP95hDK+bT6j+/vbVw" .
	"+NXZnd25q43P0L4kpZRSSimllFJKqbBFfRS2QGFLSimllI3P+ZUr21KvXo7e" .
	"nns+d7x6YWtjbXNpc6lN13WWBpOyifnO16tv/DhTVFknzy2uL+736MpSsFJK" .
	"KaWUUkoppZQKWyhsgcKWlFJKKeVPZNoEvFGxql7ViopD+67rovZh75Kyibm+" .
	"++Gx8kf9l0fu3rh7o2vnuiKnPrz2nn1JSimllFJKKaWUUmELhS1Q2JJSSiml" .
	"/K2ZNpdMLGJYvbDVvtu5qHrYr6Q072B/xuKJXbi3dSaUUkoppZRSSimlVNhC" .
	"YQsUtqSUUkopf0te3HVrZ/krligc7Nk+fXrvSpWq1oG5mYWDZ13RSSnb8Ugr" .
	"XJpd27F6uei8F1XXLtzbRhXYXINSSimllFJKKaWUClsobIHhPSmllFLK38it" .
	"d7/9aNR1hOEzfk6bruXi87RfSdnlSmsoqrS2taha5Mq2O6/Yl6SUUkoppZRS" .
	"SikVtlDYAoUtKaWUUsp/nac+vPZe6hVLFA6qVLWiytC+pcGi3mHvkrJN+eiT" .
	"pw/Knw0unF++t3yoO3XVIvHp2YuklFJKKaWUUkopFbZQ2AKFLSmllFLKF+u7" .
	"Hx4rf62SdsPWhUXBLP4lZVvz09fv3y9/TthY21zaXCo6Bx69Pfd87rj7XCml" .
	"lFJKKaWUUkqpsIXCFniQLaWUUspOZJSK0pw8t7i+uL96YSuqDG26iotKh71L" .
	"SufMXvP7FqZOnOjOmbDIF6e2puxLUkoppZRSSimllApbKGyBwpaUUkopO5tX" .
	"tt15ZdSzxQyfbZ1XZn7lyjZ7l5RmJSzzkCsqsN2523WelFJKKaWUUkoppVTY" .
	"QmELFLaklFJK2dF89MnTB+WvUi6cX763fKh6YevqS9enr2206fotbnrtV1K2" .
	"O+NOLc2BuZmFg2f7z4d7tk+f3rvyeOvJ9JPpLtztmolQSimllFJKKaWUUmEL" .
	"hS1Q2JJSSill5/LC2zdfG3XhYPiMn9C+67f4VO1dUnYht9799qNRF14vza7t" .
	"WL3chbvdZx8//95eJKWUUkoppZRSSqmwhcIWKGxJKaWUslP5xamtqfLXJ1Em" .
	"qD631tL1M4fPTLXpyi3mKrNfSdmdvLjr1s7y54qYQyvm0+pOmbVIfIb2JSml" .
	"lFJKKaWUUkqFLRS2QGFLSimllC3P+ZUr21KvT47enns+d7x6Yat9y35d2Xbn" .
	"FXuXlF3Kd75efePHmaLKOnlucX1xvwdbMUuZfUlKKaWUUkoppZRSYQuFLVDY" .
	"klJKKWXL89PX798vf2Wysba5tLlUvaoVNYX2XblFdcPeJaUzavUzalvPk0VO" .
	"fXjtPfuSlFJKKaWUUkoppcIWClugsCWllFLKFmcd88F0+YZtfffDY/YrKc1Z" .
	"OOo5C6PU1YU7X2dRKaWUUkoppZRSSoUtFLZAYUtKKaWUrc2Lu27tLH9NEssX" .
	"7tk+fXrvSpWq1lsLh1+d3dm+azZzw0gpyz7wCpdm13asXi46Zy5dP3P4zFR3" .
	"7n/NUyillFJKKaWUUkqpsIXCFihsSSmllLKFufXutx+NulIwfMbPadPVWnye" .
	"9isp5YW3b76WeiY5MDezcPBs/zkzarLduf+9su3OK/YlKaWUUkoppZRSSoUt" .
	"FLZAYUtKKaWUrcmYBWq0ZYLhM2oHMVNXm67WYsYye5eUMvLRJ08flD+TXDi/" .
	"fG/5UHeqrkXi07MXSSmllFJKKaWUUipsobAFCltSSimlbEmu7354rPzVSNot" .
	"WRcW9nr28fPv7VdSyt/MmCOqrI21zaXNpaLz59Hbc8/njnfnLjjmKrMvSSml" .
	"lFJKKaWUUipsobAFCltSSimlbHC+8/XqG6lXIyfPLa4v7q9e2Io6Qpuu0z59" .
	"/f59e5eUclzn2y+P3L1x90YX7oK/OLU1ZV+SUkoppZRSSimlVNhCYQsUtqSU" .
	"UkrZ6Ixq0WhnfBk+2zo3zPzKlW32LinluGY0jDpXd+6FnWOllFJKKaWUUkop" .
	"FbZQ2AKFLSmllFI2OB998vRB+euQC+eX7y0fql7YuvrS9elrG2Z/kVJ2J099" .
	"eO291DPMgbmZhYNn+8+le7ZPn9678njryfST6S7cC5vFUEoppZRSSimllFJh" .
	"C4UtUNiSUkopZSPzwts3Xxt1aWD4jJ/Qviu0+FTtXVLKwbn17rcflT/DXJpd" .
	"27F6uei8GlXaLtwLP/v4+fc/LjFpX5JSSimllFJKKaVU2EJhCxS2pJRSStna" .
	"G7Bh6gLD59L1M4fPTLXp2izmKrNfSSmHyYu7bu0sf56JObRiPq3uFGGLxGdo" .
	"X5JSSimllFJKKaVU2EJhCxS2pJRSStmAnF+5si31CuTo7bnnc8erF7bat3TX" .
	"lW13XrF3SSnLZMwUVVYUXj32ilnK7EVSSimllFJKKaWUClsobIHClpRSSikb" .
	"kJ++fv9++WuPjbXNpc2l6lWtk+cW1xf3t+/azOJcUsoczsbz+xamTpxwXyyl" .
	"lFJKKaWUUkopFbZQ2AIPpqWUUkqZRUapKG1OlyhaVS9ste+WbH33w2P2Lill" .
	"TvMdRqmrC/fFzsBSSimllFJKKaWUClsobIHClpRSSimzzou7bu0sf9URyxfu" .
	"2T59eu9KlarWWwuHX53d2b6rslMfXnvP3iWlTM0vTm1NlT/zXH3p+vS1jaLz" .
	"bSyb2J27Y3McSimllFJKKaWUUipsobAFCltSSimlzDS33v32o/JXHZdm13as" .
	"Xq4+t1b8nDZdj8Xnab+SUlbJC2/ffC31LHRgbmbh4Nn+821UbLtzdxyLS9qX" .
	"pJRSSimllFJKKRW2UNgChS0ppZRSZpRx5TDaQsDwGdWBmKmrTddjMWOZvUtK" .
	"WT0fffL0Qfmz0IXzy/eWD3WnJlskPj17kZRSSimllFJKKaXCFgpboLAlpZRS" .
	"yoxyfffDY+WvN9JuurqwONezj59/b7+SUo4ur2y780r5c1EUYbu2EG1hfe3P" .
	"5yqzL0kppZRSSimllFIqbKGwBQpbUkoppZxwvvP16hup1xsnzy2uL+6vXtja" .
	"WNtc2lxq05WY5beklHWcq6MMOtpz9ZdH7t64e6ML98jxYNG+JKWUUkoppZRS" .
	"SqmwhcIWKGxJKaWUcsIZ1aKyomJVvap19Pbc87nj7bsSm1+5ss3eJaVswmyI" .
	"Uefqzp2y87OUUkoppZRSSimlwhYKW6CwJaWUUsoJ56NPnj4of6Vx4fzyveVD" .
	"1QtbV1+6Pn1to03XYF+c2pqyX0kp68lTH157L/XsFAsgFp2NY/HELtwpR+nN" .
	"viSllFJKKaWUUkqpsIXCFihsSSmllHICeeHtm6+lXmns2T59eu9KlarWgbmZ" .
	"hYNn23cNFp+qvUtKmc8DsnBpdm3H6uWic3LUcLtwpxzLSsYSk/YlKaWUUkop" .
	"pZRSSoUtFLZAYUtKKaWUjR/yHz7bVw6IucrsV1LKuvPirls7R123bWuJtkh8" .
	"hvYlKaWUUkoppZRSSoUtFLZAYUtKKaWUY8r5lSvbUq8xjt6eez53vHphq33L" .
	"b13ZducVe5eUclwZM0WVtXT9zOEzU91ZplbFVkoppZRSSimllFJhC4UtUNiS" .
	"UkopZRb56ev375e/uvjyyN0bd29Ur2qdPLe4vri/TdddFtiSUjblTL6xtrm0" .
	"uVR0fp7ftzB14oS7ZimllFJKKaWUUkqpsKWwBXj0LKWUUsqRZZSK0uZliaJV" .
	"9cJWFL/adN21vvvhMXuXlLI5cyVGMavoLB2lri7cNTt7SymllFJKKaWUUips" .
	"obAFCltSSimlrD0v7rq1s/x1RSxfuGf79Om9K1WqWm8tHH51dmf7rrtOfXjt" .
	"PXuXlHIS+cWpranyZ61Y+rDoXB3LJnbn3jmqb/YlKaWUUkoppZRSSoUthS1A" .
	"YUtKKaWUteTWu99+VP664sL55XvLh6rPrXVpdm3H6uU2XXHFbar9Sko5qYz7" .
	"vjQH5mYWDp7tP1dHPTequl24d47FJe1LUkoppZRSSimllApbCluAwpaUUkop" .
	"GzCoP3zG8H/7rrhixjJ7l5Rysvnok6cPRl3GbV/Ftkh8evYiKaWUUkoppZRS" .
	"SoUthS1AYUtKKaWUI8713Q+Plb+iSLut6sICW88+fv69/UpK2fzlbru2iK0C" .
	"rpRSSimllFJKKaXCFgpboLAlpZRSytrzna9X30i9opjftzB14kT1wtbG2ubS" .
	"5lKbrrUsoSWlzO08H0XSsk6eW1xf3O8xmSVupZRSSimllFJKKRW2FLYAhS0p" .
	"pZRSjiyjWlRWVKyqV7Wi8tW+a635lSvb7F1SyubPpPjlkbs37t4oOodHnas7" .
	"99HO7VJKKaWUUkoppZQKWwpbgMKWlFJKKUeQjz55+qD8tcSF88v3lg9VL2xd" .
	"fen69LWNNl1lfXFqa8p+JaXML6NslCYWQCw6k8fiiV24j47Sm31JSimllFJK" .
	"KaWUUmFLYQtQ2JJSSillYl54++ZrqdcSe7ZPn967UqWqdWBuZuHgWVdZUkqZ" .
	"8+OzcGl2bcfq5aLz+dL1M4fPTHXhPjqWlYwlJu1LUkoppZRSSimllApbCluA" .
	"oUQppZRSZjFsP3zGHF1tur6KucrsV1LKnPPirls7R13VbWsBt8iVbXdesS9J" .
	"KaWUUkoppZRSKmwpbAEKW1JKKaUc28JYR2/PPZ87Xr2w1b4ltAzhSym7vBhu" .
	"+5a4Vc+VUkoppZRSSimlVNhCYQsUtqSUUko5slzf/fBY+euHL4/cvXH3RvWq" .
	"1slzi+uL+9t0ZWWRLClls/LT1+/fL3+u21jbXNpcKjq3R53XPbWUUkoppZRS" .
	"SimlwpbClsIW4OGylFJKKX8jo1QUBaOyomhVvbAVxa82XVlFAc7eJaXswjyL" .
	"8/sWpk6cKDrDR6mrC/fUX5zamrIvSSmllFJKKaWUUipsKWwBCltSSiml/Mm8" .
	"uOvWzvJXDrF84Z7t06f3rlSpar21cPjV2Z3tu7I69eG19+xdUspuzLY4+IFa" .
	"++ZQ/In62p9X3+xL8v9n7/5Zq+r2fYFDqrwFq1jYB6xOET23uMV9AZImCy1y" .
	"iwSEa2EiCIKuRkiR1RkIkiKFBEVyTXHEJCsW4WKEAykMF8E/ybbQk6xOzRFP" .
	"BO/l/PYDz0POyl5zzvV3zM+3+BQHzrPZeeYac4w5vnsMkiRJkiQVthS2FLZE" .
	"RGGLJEk29evLH5+yzxxWn64drF0vfrbWxszWaH0zpTlVLEQ9VyQH0VgV5stk" .
	"dbo2tXJ6nI9qb9R8y7CyjsslPUskSZIkSVJhS2FLYUtEFLZIkmSXNuZbN7bw" .
	"05tTxYllni6Sg2vj7rdn7S7yplfPbZa4YthTRJIkSZIkFbYUthS2RERhiyRJ" .
	"dunqq9Zdfv1o9tGQTXqSLMNVuVHzLc/6WnmXJEmSJEkqbClsKWyJiMIWSZL8" .
	"i3Pr28N55wxzV2tD8/PFC1uHW0fLR8spzaZcg0UyDW9/qV/6o4SaNQ+eLO4t" .
	"XvMRzfW4JEmSJElSYUthS2FLRBS2SJLkX4xqUdZExap4VSsqX+nNpqIG5+ki" .
	"WeY3xcdb+7v7u83G/6hzlWeVvfB456FniSRJkiRJKmwpbClsiYjCFkmS/P9+" .
	"ffnjU/bZwurTtYO168ULW6/Ova7sHKY0j3q/0BjyXJF0FuMfufOuelK9X54T" .
	"Fpslrh72LJEkSZIkSYUthS2FLRFR2CJJstS+uPx2LO9sYXyksjSxXqSqNVmd" .
	"rk2tmEeRZKof1yIbM1uj9c1m74Ll149mHw2VZ60dV0x6lkiSJEmSpG9KClsK" .
	"WyJio5EkScuktm29t26c0ZXSDCrOKvNckUzV1ZtvLuYdIaOkW57ybrNsD384" .
	"71kiSZIkSZJ2IhS2FLZERGGLJEmXW2XK2ZdbtWKczvW9cVw5rtiGJ0kX6UYV" .
	"uAxrbdVekiRJkiSpsKWwpbAlIgpbJEmW1L0rn+9lnyF8vLW/u79b/GytB08W" .
	"9xavpTR3+vn85JeLrkiWw3/9p7/9Lfs4ebh1tHy03Oy9EFXg8qy446wyzxJJ" .
	"kiRJklTYUthS2BIRhS2SJEthlIqiYJQ1UbQqXtiK4ldKc6cowHm6SJbnPZIv" .
	"Z79H0ns7NMv7hcaQZ4kkSZIkSSpsKWwpbImIwhZJkiXxxeW3Y9nnBnF9YfGq" .
	"VqpnqCw83nno6SLppMZin9vSO3/x7MT1xJ4lkiRJkiSpsKWwpbAlIgpbJEkm" .
	"7teXPz5lnxusPl07WLtevLC1MbM1Wt9MadYUS03PFcmyGWvGfJmsTtemVk6/" .
	"I8ZHKksT61ERLsO6Oy6X9CyRJEmSJEmFLYUthS0RUdgiSdLmeobN9daNbfj0" .
	"Zk1xYpmni2Q5bdz99iz7yBnl3Wbvi6gIl2HdHdcTe4pIkiRJkqTClsKWwpaI" .
	"KGyRJOn6qr/k1bnXlZ3D4mdrLb9+NPtoKKX5UpxV5rki6ZrdrIkztKLIe/p9" .
	"ERXh8qy+FX9JkiRJkqTClsKWwpaIKGyRJJmgc+vbw3lnBXNXa0Pz88ULW4db" .
	"R8tHyynNl1xlRZJhnBSVNQ+eLO4tXvOJLU4p8xSRJEmSJEmFLYUthS0RUdgi" .
	"STIpo1qUNVGxKl7VispXevOlqMF5ukiyE2+ZVN8d1uAkSZIkSVJhS2FLYUtE" .
	"fCwmSbIUxuV9WROXGBYvbKW3TIrLJT1XJFn8HMc776on1fvlOZ3Rm4UkSZIk" .
	"SSpsKWwpbImIwhZJkon74vLbsbzzgfGRytLEepGq1mR1uja1YqZEkmXw/UJj" .
	"KPuI+urc68rOYbP3SFSHy7MSv/2lfsmzRJIkSZIkFbYUthS2RBS2vEJIkizV" .
	"QiiyMbM1Wt8sfrbW6tO1g7XrKc2R4qwyzxVJnnb15puLeUfXKPiefo9Edbg8" .
	"K/G4XNKzRJIkSZIkFbYUthS2RBS2SJJk6S6oulGbvTAzVqSqFVvs3xvHleNK" .
	"SnOkOLHM00WS7b2ENwq+zd4pUSMuw0pcLZgkSZIkSSpsKWwpbIkobHl5kCQ5" .
	"wO5d+Xwv+xzg46393f3d4mdrPXiyuLd4LaXZ0c/nJ79cVkWS/8jt4Q/ns4+x" .
	"UfBt9k658656Ur1fnvV4nFXmWSJJkiRJkgpbClsKWyIKWyRJcmCMUlEUjLIm" .
	"ilbFC1tR/EppdhQFOE8XSbbyDsqXs99B6b1ZmiU+YnqWSJIkSZKkwpbClsKW" .
	"iMIWSZIcGOPavvaebtK6qZ6DEldMerpIsnOnPJ79MS69sxu9d0iSJEmSpMKW" .
	"wpbClogobJEkmYhfX/74lP3tv/p07WDtevHC1sbM1mh900knJFlmFx7vPMw7" .
	"6t6ozV6YGWv2lol6cRlW5f/6T3/7m2eJJEmSJEkqbClsKWyJKGyRJMk+N97g" .
	"+TJZna5NrRSpao2PVJYm1tObF8WJZZ4ukuz0x7hIFH+bvWuiXlyGVXlcbRxX" .
	"THqWSJIkSZKkwpbClsKWiMIWSZLsU98vNIayv/dfnXtd2TksfrbW8utHs4+G" .
	"UpoRxVllniuS7P4VvVECPv2uiXpxedbmSsMkSZIkSVJhS2FLYUtEYYskSfap" .
	"c+vbw3nf+3NXa0Pz88ULW4dbR8tHyynNiFxHRZLFjZOisiZKwM3eOFE1LsPa" .
	"XHWYJEmSJEkqbClsKWyJKGyRJMk+NapFWRMVq+JVrah8pTcjihqcp4sk++0N" .
	"lep7xwqdJEmSJEkqbClsKWyJKGx5eZAkWerzS8q8ENq78vme54oke30G5J13" .
	"1ZPq/fKc7OitRJIkSZIkFbYUthS2RBS2vDxIkhwAX1x+O5b3jT8+UlmaWC9S" .
	"1ZqsTtemVsyFSJJn+36hMZR9NI6rD5u9g6J2XJ51unMfSZIkSZKkwpbClsKW" .
	"iMIWSZIcyKVOZGNma7S+WfxsrdWnawdr11OaBX19+eOT54ok222sMfMlysGn" .
	"30FROy7POj0ul/QskSRJkiRJhS2FLYUtEYUtkiTZMxce7zzM+66/UZu9MDNW" .
	"pKoV2+TfG8eV40pKs6A4sczTRZKdMEqxWRPl4Gbvo6ggl2GdrlJMkiRJkiQV" .
	"thS2FLZEFLZIkmSP3bvy+V72t/zHW/u7+7vFz9Z68GRxb/FaSvOfn89Pfv3+" .
	"fftL/ZKniyQ74/bwh/PZx+coBzd7H0UFuTyrdcVikiRJkiSpsKWwpbAlorBF" .
	"kiR7YJSKomCUNVG0Kl7YiuJXSvOfKMB5ukhyEN9f5fkkF584PUskSZIkSVJh" .
	"S2FLYUtEYYskSQ78CSWte+dd9aR6P735z9z69rCniyQH84TI9M599M4iSZIk" .
	"SZIKWwpbClsiClskSbKP/Pryx6fs7/fVp2sHa9eLF7Y2ZrZG65tOKyFJ5jPK" .
	"RvkSFyA2e0NFNbkMa3anQpIkSZIkSYUthS2FLRGFLZIk2SXjHZ0vk9Xp2tRK" .
	"kapW/BPSm/m8uPx2zNNFkv39qS4SpeFm76nl149mHw2VYc0e10rGFZOeJZIk" .
	"SZIkqbClsKWwJaKwRZIkO+j7hUaOjehX515Xdg6Ln62V3kZ4nFXmuSLJ7htl" .
	"2XwZH6ksTayXp1iscEySJEmSJBW2FLYUtkQUtkiS5IBdIHXnXfWker94Yetw" .
	"62j5aDmlOc+//tPf/ubpIslBu+Q3CsTN3lZRUy7Dyl3tmCRJkiRJKmwpbCls" .
	"iShskSTJDhrVoqyJilXxqtaDJ4t7i9fSm/O4TIok03u7RU3Z+p0kSZIkSVJh" .
	"SxS2RHzwJUmShfz5/ORXu88gKfNSZ+/K53ueK5Ic5PMj567Whubny3MqZLPE" .
	"R89YxZNna8wh87nweOehMYT0P3kis2jEIMk/27j77ZnClsKWiChskSQ5cL64" .
	"/HYs+9v8e+O4clwZH6ksTawXqWpNVqdrUytmOyTJzhkl2qw5++NdVJat7kX+" .
	"HKMN2Z3zAETsGpAUEZEiUdgarChsiVh6kQN8pYuISCeyMbM1Wt8sfrZW/HNS" .
	"+svE/5rHO4sk++1/fZ4vUSw+/f6KynLUl80KRCJGG1JhS8SuAamwJSLS/1HY" .
	"GqwobIlYepEKWyIif8mN2uyFmbEiVa1Ut7rjxDLvLJLsN7++/PEp+6i++nTt" .
	"YO16eWrHIkVinCEVtkTsGpAKWyIi/R+FrcGKwpaIpRepsCUiUmgxc9oHTxb3" .
	"Fq+l9Jf5+fzk1+/ft7/UL3lnkWQ5rgBO9WJfkXwxzpAKWyJ2DUiFLRGRVPc4" .
	"FLZ6FYUtEUsvUmFLROTviaJV8cLW4dbR8tFySn+ZGKW9rUiyP41CbZRr2/vu" .
	"88FOJGKcIRW2ROwakApbIiL9H4WtwYrCloilF6mwJSLy91NGile17ryrnlTv" .
	"p/f3mVvfHva2Isn+du/K53vZR/iPt/Z393fLc2akSL4YYUiFLRG7BqTClohI" .
	"/0dha7CisCVi6UUqbImI/F59unawdr14YWtjZmu0vpnSXya2WLynSLL/jXJt" .
	"vkThuDwnR4pkjRGGVNgSsWtAKmyJiPR/FLYGKwpbIpZepMKWiMjvyep0bWql" .
	"SFUr/gnp/WVWb7656D1FkqlvjUfhuNk7bvn1o9lHQ2YLUuYYW0iFLRG7BqTC" .
	"lohI/0dha7CisCVi6UUqbIlIqfPq3OvKzmHxs7XS28z++vLHJ28okhw0o2ib" .
	"L+MjlaWJ9TKUko93//2//ft/i/M1WTbzfYY2tpDdKWzFmY5GKvaz+c7VtmtA" .
	"dq6w5d1Bkn/2wZPFvcVrClsKWyIKWyQ7XtiKTySmXySLeKM2e2FmrHhhK73r" .
	"omJk9oYiyUE0SrdZc/YFweld+5vvIyYH3XjOFbbI/ixs5TsPgOymcZG0XQOy" .
	"fwpb3h0kWVyFLYUtEYUtUmErc+ITiYkUyd4a273pzWpuf6lf8oYiyTLNrqN8" .
	"3N7tyX6OrR2FLYUtUmGLVNgiFbZIkgpbClsiClukLSWFLZIWM32RvSuf73k3" .
	"keQgG6XbTpw7ld6JknHho/mMwpbCFqmwRSpskQpbJGmPQxS2RBS2SIUthS2S" .
	"A2Bs8ZrPkCT70yjgtnfDI71zJeOqR7MahS2FLVJhi1TYIhW2SFJhSxS2RGxw" .
	"kgpbClskB8DY4k1pJtO4++2ZtxJJpmKsT9t77tT4SGVpYv1747hyXEnj3Rf/" .
	"XeK/l7mNwpbCFqmwRSpskQpbJKmwJQpbIgpbpMKWwhbJPjW97erIi8tvx7yV" .
	"SDIto4zb3nOn0qssn30RJBW2jCSkwhapsEUqbJGkwpYobIkobJEKWwpbJHts" .
	"ehdC/Xx+8uv379tf6pe8lUgyLaOM295zp9K7FPhw62j5aNkMR2FLYYtU2CIV" .
	"tkiFLZJU2BKFLRGFLVJhS2GLZJ8a27opzWFiHPY+Isn0jDJuFHPbe+5Uep/z" .
	"4r9vrDKYqv/n+c7/epX512AkIfu5sHWjNnthZsz4xu64/PrR7KMhuwbkoBe2" .
	"jGYk+Wc/3trf3d/V9FDYElHYIhW2FLZIJvi/pu3/zK1vD3sfkaT5dpZzp+au" .
	"1obm530HkDLEGEL2c2HLeQBi14BU2Mr67jAuiYhIP0RhS8TSiyxRYSvf/wZO" .
	"RCTtxCaKNxFJpm0Uc/Pl7Fl3eqdOipyOMYRU2BKxa0AqbImIiLQ3Clsill6k" .
	"wpaISKmzevPNRW8ikiyH7xcaOWbDGzNbo/VNc2wpc4wepMKWiF0DUmFLRESk" .
	"vVHYErH0IhW2RERKmq8vf3zyDiLJMhkl3XyZrE7XplZOz7HHRypLE+veqpJ2" .
	"jB6kwpaIXQNSYUtERKS9UdgSsfQiFbZEREqaGHu9g0iybEZhN2tWn64drF1v" .
	"NtOOU7i8WyXVGDdIhS0RuwakwpaIiEh7o7AlYulFKmyJiJQ0t7/UL3kHkWT5" .
	"3B7+cD77W+Nw62j5aLnZTDtm5t6tkmqMG6TClohdA1JhS0REpL1R2BKx9CIV" .
	"tkRESpe9K5/vefuQZFmNwm6+PHiyuLd4rdl8++Ot/d39Xe9ZSS/GDVJhS8Su" .
	"AamwJSIi0t4obIlYepEKWyIiZiwkydIZ5d32bodEnct7VtKLEYNU2BKxBicV" .
	"tkRERNobhS0RSy9SYUtEpERp3P32zHuHJPn798LjnYd53yY3arMXZsZOz7fH" .
	"RypLE+vfG8eV44p3rqQUIwapsCVi14BU2BIREWlvFLZELL1IhS0RkRLlxeW3" .
	"Y947JMk/jCJv1mzMbI3WN5vNulefrh2sXffOlZRirCAVtkTsGpAKWyIiIu2N" .
	"wpaIpRepsCUiUor8fH7yyxuHJPlXo8ibNXGGVpyndXrWPVmdrk2tePNKSjFW" .
	"kApbInYNSIUtERGR9kZhS8TSi1TYEhEpRWKk9cYhSZ42Sr1ZE7PrZnPvV+de" .
	"V3YOvX8ljRglSIUtEbsGpMKWiIhIe6OwJWLpRSpsiYiUInPr28PeOCTJ9s3G" .
	"D7eOlo+Wm829567WhubnvX8ljRglSIUtEbsGpMKWiIhIe6OwJWLpRSpsiYgk" .
	"nvcLjSHvGpJkc6PUmy9nz8mj1OVdLIMeowSpsCVi14Dsz8KWiIjI4EZhS8TS" .
	"i1TYEhFJPKs331z0riFJ/iOj4Js1cfWhGbikHeMDqbAlYteAVNgSERFpbxS2" .
	"RCy9SIUtEZFk8/Xlj0/eMiTJ1oz1bL5MVqdrUyunZ+DjI5WliXVvZBn0GB9I" .
	"hS0RuwakwpaIiEh7o7AlYulFKmyJiCSb7eEP571lSJJZjLJv1qw+XTtYu95s" .
	"Hr4xszVa3/RelsGNkYFU2BKxa0AqbImIiLQ3Clsill6kwpaISLK5/aV+yVuG" .
	"JJnFKPtmzffGceW40mwefqM2e2FmzHtZBjdGBlJhS8SuAamwJSIi0t4obIlY" .
	"epEKWyIiCWbvyud73i8kyexG2ffn85Nf2d8+D54s7i1eazYb/3hrf3d/1zta" .
	"BjFGBlJhS8SuAamwJSIi0t4obIlYepEKWyIiCWbh8c5D7xeSZF6j+Js1Uclq" .
	"NhuPOpd3tAxijAmkwpaIXQNSYUtERKS9UdgSsfQiFbZERJJK4+63Z94sJMli" .
	"RvE3X+ICxGZz8rg80ftaBivGBFJhS8SuAamwJSIi0t4obIlYepEKWyIiSeXF" .
	"5bdj3iwkyV5stEc2ZrZG65vm5JJSjAakwpaIXQOymzsFJDlY5vt+ErucrRvf" .
	"W8ysUorCloilF6mwJSKSSH4+P/nlnUKSbJ9RAs6X8ZHK0sT66Tn5ZHW6NrXi" .
	"rS2DFaMBqbAlYteAJEm2d5cz67zXLmd6UdgSsfQiFbZERBJJjKXeKSTJ9hqF" .
	"4KyJuXezmfmrc68rO4fe3aKwRSpsKWyJXQOSJKmwZZdTYUtELL1IhS0RkQHO" .
	"3Pr2sHcKSbI/5uqHW0fLR8vNZuYxk/fuFoUtUmFLYUvsGpAkSYUtu5wKWyJi" .
	"6UUqbImIDGTeLzSGvE3I1lRtJPP9avJl7mptaH6+2fw8Sl3e46KwRSpsKWyJ" .
	"XQOSJKmwZZdTYUtELL1IhS0RkQHL6s03F71NyH/kwuOdhwqOZF73rny+l/0N" .
	"FVcfmp+LwhapsKWwJXYNjAkkSdrlVNgShS0RSy9SYUtEJJF8ffnjk/cImX2+" .
	"cftL/ZK/CZnFWO3my2R1uja1cnp+Pj5SWZpY/944rhxXvNNFYYtU2FLYErsG" .
	"JElSYcsup8KWiFh6kQMwlfl4a393fzc+zLXiv/3Pw//xb//XSCUiKWV7+MN5" .
	"7xGyNX8+P/nlt0MWM4rCWbP6dO1g7XqzD44bM1uj9U3vdFHYIhW2FLbErgFJ" .
	"klTYUthS2BIRSy9yAKYyIiLilCCyFV9cfjv219+O0+nIdv2aWkmcoRXnaZ3+" .
	"4HijNnthZsw7Xfo5fvukwpaIXQOSJKmwJe2NwpaIpRepsCUiMpDZu/L5njcI" .
	"2ZrvFxr/5ceMhcc7D/19yCxGUfjP59W1ngdPFvcWr9lul0GM3z6psCVi14Ak" .
	"SSpsSXujsCVi6UUqbImIDGQUTchWnFvfHm7+O1J8JPMZv52siQvNm312jDqX" .
	"97v0Z/zqSYUtEbsGJElSYUvaG4UtEUsvUmFLRGTA0rj77Zl3B9ma28Mfzjf/" .
	"NcUpQf5KZHurkGfnzrvqSfV+s4+PcXmid730W/zqSYUtEbsGJElSYUvaG4Ut" .
	"EUsvUmFLRGTA8uLy2zHvDrI1v7788clviuyPbfjIxszWaH3Tx0cZrPi9kwpb" .
	"InYNSJKkwpa0NwpbIpZepMKWiMjAxGlAZOvGtaGt5P1CY8hfjMxulB3zZXyk" .
	"sjSxfvrj42R1uja14o0v/Ra/d1JhS8SuAUmSVNiS9kZhS8TSi1TYEhEZmMRo" .
	"6a1BtuLelc/3svy+4oo3fzeyE+fYnc7q07WDtevNPkHGKVze+9I/8UsnFbZE" .
	"7BqQJEmFLWlvFLZELL1IhS0R6aP8xz//x//+j3+OD+5FPNw6Wj5aTu/vo1BC" .
	"tm6cSNd6toc/nPd3I7s1k483dbNPkHfeVU+q982LRGGLVNhS2BK7BiRJUmFL" .
	"YUth6+/xv+knSZJkr87baN1X515Xdg5Tmri7so3s9DVtMWr565FZvf2lfil3" .
	"EflqbWh+vtnbPNX6tShskQpbCluisEWSJBW2FLZEYYskSZJtdvXmm4t5p6fj" .
	"I5WlifUiVa3J6nRtasXnXbLMRsExXxYe7zz0NyQ7fwlpKxvzD54s7i1e8/FO" .
	"FLZIhS2FLbGiJ0mSClsKWwpbClskSZJs80f5yMbM1mh9s/jZWnFGV0pTdqf+" .
	"kK0b14YWSZRO/CXJrMY2ZL5E2fr0Oz1q3N8bx5Xjik94orBFKmwpbInCFkmS" .
	"VNgys1LY8pMjSZJkm6sSd95VT6r3ixe20tvW3R7+cN7TRbZm/F6K5Ofzk1/+" .
	"kmReG3e/Pcv+uzv7QuSodPuEJwpbpMKWwpYobJEkSYUtMyuFLT85kiRJtm1Z" .
	"8vHW/u7+bvGqVnoXJ0Vx5PaX+iVPF9macSJd8by4/HbM35PMbvx2sibK1s2u" .
	"RU71smNR2CIVthS2RGGLJEkqbClsKWwpbJEkSbKQUSqKglHWRNGqeGEril8p" .
	"TdZdzUa27sLjnYft+/W9X2gM+auS/TQfsBkvClukwpZ3hChskSRJhS0zK4Ut" .
	"PzmSJEl2/ESN1r1Rm70wM5beZD0KKJ4ushWj4NjexDWv/rZkdz5THm4dLR8t" .
	"l+ccTVHYIhW2FLZEYYskSSpsKWwpbClskSRJspCNu9+eZZ+Mrj5dO1i7Xvxs" .
	"rY2ZrdH6ZkrT9NjY8FyRrZvvRJ+zsz384by/LZndKDvmy5131ZPq/WZv/Ch1" .
	"+ZwnClukwpbClihskSRJhS1R2CJJkmSpjc+O+TJZna5NrRSpasXpXOlN0+PE" .
	"Mk8X2bkT/lrJ15c/PvkLk93apI9ECdunSVHYIhW2FLZEYYskSSps+SqisKWw" .
	"RZIkyTZfQ5bvQ3wZlihxSpDnimzd9wuNjo4CLicl87l6883Fdle6Uy1qi8IW" .
	"qbClsCUKWyRJUmFLYUthS2GLJEmSLXn7S/1S3mno3NXa0Px88cJWelcjmW+T" .
	"3bl2rfVELdVfm8xnnFSXNWdfmpzeVciisEUqbClsicIWSZJU2FLYUtiygUSS" .
	"JMkOLkKiYlW8qhWVr/Qm6FFA8XSRrbg9/OF853+Vzr0ju/87PXu2cOdd9aR6" .
	"30c9UdgiFbYUtkRhiyRJKmyJwhZJkiSdmdFSzj4zo3VfnXtd2TlMaWoe17p5" .
	"rshOj0L58uLy2zF/c7K753E+eLK4t3it2Uzg46393f1dn/ZEYYtU2FLYEoUt" .
	"kiSpsCUKWyRJkiyFUVzIl/GRytLEepGq1mR1uja14gMuWWYXHu88zP4r+944" .
	"rhxXojaa9f83thv95cl8xtWiWXP2tn3UuXzaE4UtUmFLYUus90mSpMKWKGyR" .
	"JEnSZ/em2ZjZGq1vFj9bK1/Zop8TpwR5rshOlz9iFCpS+nRpKdnNkmUkfrOn" .
	"5wNRAY8ipg98orBFKmwZqURhiyRJKmyJwhZJkiSTNcoK+XLnXfWker94YSu9" .
	"rdnt4Q/nPV1kluvVfj4/+VVsFMq3sWdFTBaxcffbs3YXvtOrcYvCFqmwpbAl" .
	"ClskSVJhS2FLYcvnaZIkSbbhVJuPt/Z393eLV7XSu/woSidRQPF0kZ27kvVw" .
	"62j5aLn4eOI8PLL7v98oaje7UjnVi5JFYYtU2FLYEoUtkiSpsKWwpbClsEWS" .
	"JMlCp9pEMaJ4YSuKXylNx6MA5+kiO30la5zB067L1Gy3kEXMN5eIj5K26kVh" .
	"i1TY8hYQhS2SJKmwJQpbJEmSdCrGP8jZp2K07o3a7IWZsfSm4wuPdx56usjO" .
	"X8kaZ/CcHlviqjVVS7L/P2KePifvz85drQ3Nz/vMJwpbpMKWiMIWSZJU2BKF" .
	"LZIkSSZlXASWNadPtclnvlJFPye2LjxXZKc/fJy97XfnXfWkej/rP9NlpmSv" .
	"ypfxm232i45Sl499orBFKmyJKGyRJEmFLVHYIkmS5MAbHxbzpdmpNq0bp3Ol" .
	"NxGPE8s8XWSna6OtXMmar+ThV0wW8f1CI8cnxlfnXld2Dn24FIUtUmFLYUsU" .
	"tkiSpMKWKGyRJEkycePyr6zJ96m9DIuQOJvHc0V2ujba+pWscRZg1n++c/LI" .
	"Iq7efHOx3XXwVEveorBFKmwpbInCFkmSVNhS2FLYUtgiSZJ0aVFLmbtaG5qf" .
	"L17YSu96IzNqsju10bhKtZVxJsofOce6/xwn/Tsi++fC5fSuURaFLVJhS2FL" .
	"FLZIkqTClsKWwpbtJZIkScuMf5CoWBWvakXlK70puHoH2bq3v9Qv/XEuXdbc" .
	"eVc9qd7v9FafNTJZxO3hD+ez/7rj/Lxmv+UbtdkLM2M++YnCFqmwJaKwRZIk" .
	"FbZEYYskSZIlOvcilg3FC1vpfXZ/v9AY8lyRWXxx+W2O2kW+2uiDJ4t7i9ey" .
	"/mfFOOnfFNn9Umb8Zpv9oj/e2t/d3/XhTxS2SIUtEYUtkiSpsCUKWyRJkky8" .
	"JBEZH6ksTawXqWoVuZ7MJ1qyzJt8kbOvS2tmjF1xco9fN9n/156evamfr4Ip" .
	"orBFKmyJ+BpAkiQVtkRhiyRJkgNWktiY2RqtbxY/WyvKFilNu53BQ2Y1Lg/N" .
	"lyh95ht/YhzL+p8YdRP/1sh8LjzeeZj39x4XIDb7ReerYIoobJEKWyIKWyRJ" .
	"UmFLFLZIkiQ5MCWJszdNO33CTT8nTizzdJGd/syRb5Pvz955Vz2p3s/6nxsX" .
	"usXlbv7dkf1TFk+vAi4KW6TClsKWKGyRJEmFLVHYIkmSpGuJ/p6Pt/Z393eL" .
	"n62V3gVGahxkPuNcuqyJMaRXV7KqZpL9dh1zqpcsi8IWqbClsCUKWyRJUmFL" .
	"FLZIkiSZiFEqioJR90sSYRS/UppwuyiNzGpsZmRNnMzXrLTRnStZY0vSv0Gy" .
	"iPnmIfHJstnv+tW515WdQx8BRWGLVNgSUdgiSZIKW6KwRZIkyUROtoiSRPGq" .
	"Vr5ryPo/ccWkp4vs9Dl/Z1+L1rqHW0fLR8t+9eRgfeKMX26z3/Xc1drQ/LyP" .
	"gKKwRSpsiShskSRJhS1R2CJJkmQiF5DFaTTFSxJRtkhpqu2sHbKb5/xF6bPI" .
	"KNSuSodVM1nEqDzmrEv+56+4c3VMUdgiqbAlorBFkiQVtkRhiyRJkj2+gCwy" .
	"WZ2uTa0UKUnEFWbpTbXjxDJPF9npc/7OPlmn+7XRKL/6t0kW8f1CI8cHyLj6" .
	"0GdNUdgiFbZEFLZIkqTClihskSRJMtkLyM7eFi3zMkNdg+zOll6k+Dl/naiN" .
	"2pIh+61KHr/0uMrZB0FR2CIVtkQUtkiSpMKWKGyRJEkywYuHWje9K4rMmclu" .
	"jkXFz/nrxMeOKML6N0v222XN6V3BLApbpMKWwpYobJEkSYUtUdgiSZJkKRYS" .
	"7bqALCpf6U2yo3ri6SI7PRa165y/j7f2d/d3OzEa3P5Sv+TfL9nda1LjDK1m" .
	"v/cbtdkLM2M+CIrCFqmwJaKwRZIkFbZEYYskSZIDdnZFLAyKlyTS+7DuTB2y" .
	"m2PRgyeLe4vXioxCna5uRN3Ev18yn1F5/Pn85Fe7xwdb+6KwRSpsiShskSRJ" .
	"hS1R2CJJkuTAnFoRGR+pLE2sFylJxBVmPsKSXL355mL239rZJ+i0blyd1rkx" .
	"oXH32zP/lsliRhk6a+LkvGa//ahz+SwoClukwpaIbwUkSVJhSxS2SJIk2def" .
	"ziMbM1uj9c3+L0l0P3E+kOeK7E4Vo11jURS/Oj0+uCaVLGL8gvIlTtHr7Qgg" .
	"ClukVafClojCFkmSClsKW6KwRZIkyd8Lj3cedmbjsxXjdK70tkhdfEbmu+ys" .
	"V2PR3NXa0Px8d8YH62iyP4vmPnSKwhapsCWisEWSJBW2RGGLJEmSA3y1UOum" .
	"dwnRz+cnv/6onni6yE5fzHq4dbR8tFx8LHp17nVl57A7o4QT+Mj+vMo51Qua" .
	"RWGLVNgSUdgiSZIKW6KwRZIkyb47zyYKRlkTRaviJYkofqU0pY4CnKeLzGrj" .
	"7rdn2X9x8WGi+Dl/3R8rVm++uejfO1nMqD9m/vX950XM/VDfFIUtUmFLYUsU" .
	"tkiSpMKWwpYobJEkSZbO7eEP57NPGeP6wuJVrTvvqifV++lNqefWt4c9XWQW" .
	"41eTL3EiTpGxqFefNpQ7yV59AD37ZL5U5yeisEUqbIkobJEkSYUtUdgiSZJk" .
	"sudStO7GzNZofTOlyXRsP3iuyO58wohTcAb9nD/Xp5K9qnvOXa0Nzc83Gxmi" .
	"1OVDoShskQpbIgpbJElSYUsUtkiSJNk24+NgvhQ/z6ZXF5B1Oi8uvx3zdJHd" .
	"Ko8Wv5j1Rm32wsyYcYMcdOO8uqw5uwQQI4wPhaKwRSpsiShskSRJhS1R2CJJ" .
	"kmTbfL/QyDGRb9d5NuktJKJu4rkis7p6883F7L+4dl3MGucF9nb0aNz99syT" .
	"QPZfDT3K5THa+FwoClukwpaIwhZJklTYEoUtkiRJ9unlQa2b3jVDZsVkN8/F" .
	"iQtVi49F/VPFiJHZ80AWMeqPWXP2Rc/pXd8sClukwpaIwhZJklTYEoUtkiRJ" .
	"DsxSISpWxesRqV4wpGxBZvX2l/qlvL+4uMqwyFgU9VOlTzIl44LRrIniZpyn" .
	"dXqsiPO3fC4UhS1SYUtEYYskSSpsicIWSZIkC/nz+cmv7NPEmPoXL2yl9+k8" .
	"zgfyXJHdqVa0qzwaF7z2z0jiWlWyXTXQfPOcKJTb+BeFLVJhS0RhiyRJKmyJ" .
	"whZJkiT7oh4RaXbyROumekaFz6xkNy8vK14ejdGsP8eT1ZtvLno2yF58Ev14" .
	"a393f7ds54OKwhapsCXiSwJJklTYEoUtkiRJ9t3H8cjGzNZofbP4eTbxz0lp" .
	"6uxEHDKfcYVovkT1s8hY1M8fMpzYR/Z2hLnzrnpSvd9s9IgT/nw6FIUtUmFL" .
	"RGGLJEkqbInCFkmSJFty4fHOw7wTxBu12QszY8XPs/neOK4cV1KaOseJZZ4u" .
	"sjsfLOISw+Ll0ThHp5/HlrjWzXNC9ltJ3WdQUdgiFbZEFLZIkqTClihskSRJ" .
	"MoNxakvW5Ps4XoaLhH4+P/mlVEHmNU6ny5oYSYqMRVE/VQYly2BcMJovzU7y" .
	"S/VyZ1HYIhW2RBS2SJKkwpYobJEkSbLNRqkoCkbdr0ekeoWQOTDZzQpFnM9X" .
	"fCxafbp2sHa9/0eYxt1vzzwtZO/qoTFWlOeKZ1HYIhW2RBS2SJKkwpYobJEk" .
	"SbLNbg9/ON+7esSdd9WT6v30Js1z69vDni6yW6f9nX1JWesO1sWsxhmyV59H" .
	"o2hetrmNKGyRClsiClskSVJhSxS2SJIk2ddnS7RueqdQxAaD54rMd9pfvsRV" .
	"hkXGormrtaH5+cEabay1yd6OPGefM5re6aGisEUqbIkobJEkSYUtUdgiSZJk" .
	"G4zPf/kyWZ2uTa0UqUfEPyG96XJc6ObpIrP64vLbsey/uLPPuWndV+deV3YO" .
	"B2u0iatsPTlkr872O7siEHUunxEVtkgqbIkobJEkSYUtUdgiSZLkX3y/0Mgx" .
	"VY9aQ/F6RHpLhTirzHNF5rNx99uz7L+7GEnKXB5VEiX7s8I+PlJZmlgfrItW" .
	"RWGLVNgSUdgiSZIKW6KwRZIkyQ46t749nHc6eOdd9aR6v3hhK72rgsx7yXwu" .
	"PN55mPd3F5WIMpdHo3rrKSJ7VRuNy52bjTBxhbSPiQpbJBW2RBS2SJKkwpYo" .
	"bJEkSTLnYqBdV4+leknQ7S/1S54uslsjUrtO+0ujPGr8IXt1MWucodWsPJrq" .
	"BdCisEUqbIkobJEkSYUtUdgiSZJkZn8+P/mVfSJY/OqxVD+O7135fM9zRXZ3" .
	"RJq7Whuany8yFt2ozV6YGUtjFNoe/nDes0T2bkSKMrpagChskQpbIgpbJElS" .
	"YUsUtkiSJNml0yNaN9VzJnxIJfO5evPNxbwjUvHyaFxklsYoFFe5eaLI/jyF" .
	"NAqmPikqbJFU2BLxnYEkSSpsicIWSZKkz98ZErUG9QglCbK9vl9o5PhgsPp0" .
	"7WDtevERKYpfKY1IC493HnquyGLOrW8P5/0N3nlXPaneT/sCVlHYIhW2RBS2" .
	"SJKkwpYobJEkSTKDsZGfL3FxWJFiRJzOlV49Ik4s83SRWb39pX6pdyNSXF6W" .
	"3nLd6pvsbZ301bnXlZ1DH0lFYYtU2BJR2CJJkgpborBFkiTJv7t35fO97JO/" .
	"fJ+/y1CP+Pn85NcfpRNPF5nV7eEP57P/7j7e2t/d3y0+IkWpIr3leoxLni6y" .
	"Vxe2RuIC6GbldR8WFbZIKmyJKGyRJEmFLVHYIkmSLNFJNrGRnzVRtCpej0jv" .
	"GiCzXMxiHEIAADMhSURBVLKIcZ1o1sQnhiJjURQp0l60R9HEM0YW9+vLH59y" .
	"/AbPvLY1veuhRWGLVNgSUdgiSZIKW6KwRZIkybadZBPXFxavat15Vz2p3k9v" .
	"Wjy3vj3s6SK7ez1rnE9TZEQqw0eKuMrNk0b25wwq1XmRKGyRClsiClskSVJh" .
	"SxS2SJIk2fHzIVo3vZMkYgvBc0V282NEXGLotL/W48JWsl1nlObL2WeUxgWv" .
	"PjIqbJFU2BJR2CJJ0jdShS1R2CJJkkzQuBgrX+LiMFeP/RdVNteNkQXMdz3r" .
	"3NXa0Px8kRHpRm32wsxYeZbucTKQ540s7t6Vz/ey/wbPLhBEnctHRoUtkgpb" .
	"IgpbJEkqbClsicIWSZJkgsbFWFnTrpNs0lsMxFllniuymxXSdl3Pmt5pf2en" .
	"cffbM08d2euLXKMq2mxcivHNp0aFLZIKWyIKWyRJKmwpbInCFkmSZCLOrW8P" .
	"553w3XlXPaned/WYmS3ZDxXSdl3PWs5iRBRNPHtk9wsEkaiKNhuXYnzzqVFh" .
	"i6TClojCFkmSClsKW6KwRZIkWerpflSsihcjUr3o5/aX+iVPF5nd+O3ky9nn" .
	"05R5RLIeJ7vpi8tvc1yqGlXR8ZHK0sR6eS6PFoUtUmFLRGGLJEkqbInCFkmS" .
	"ZEn9+fzkV/apXkzfixe20vv8vXfl8z3PFZnX7eEP57P/7j7e2t/d3y0+IsU1" .
	"r+VcwMe7wBNI9vP8qsxjlMIWSYUtEYUtkiQVthS2RGGLJEnSCRBNT4Bo3VTP" .
	"ivCplCxi4+63Z72okDq9JrJ6881FzyHZryeYzl2tDc3PG6kUtkgqbIn4CkGS" .
	"pC8MCluisEWSJFm6YsTGzNZofbP4STbxz0lp+ht/T88Vmc+FxzsP8/76ildI" .
	"fZKIvF9oDHkayXY4t749nPeXeOdd9aR6v9l4FaUu45XCFkmFLRGFLZIkFbYU" .
	"tkRhiyRJskTFiBu12QszY0WKEVGtiJO6Upr+xollni6ym58e4oKw4hVSBYg/" .
	"5/aX+iXPJNkOowTZ3pHNJ1SFLZIKWyIKWyRJ+mqqsCUKWyRJkgPp3pXP97JP" .
	"7/J94C7D1P/n85NfKg5kMeN3lDVxQViRESlKqBbwf8728IfznkmyHcYGar7E" .
	"Va3Niu9GKoUtkgpbIgpbJEkqbClsicIWSZLkwBilonx58GRxb/Gak2zMY8n2" .
	"unrzzcXsv7s4pc/1rJ3I15c/PnkyyfYZv6msWX26drB23dilsEVSYUtEYYsk" .
	"SYUthS1R2CJJkhx449yUrImKVfFixJ131ZPq/fQmvnPr28OeLrK7V4adXWUo" .
	"8/Ws7Upcnuv5JHs1+zq7lup0QIUtkgpbIgpbJEkqbClsicIWSZJkqc94aN1X" .
	"515Xdg5TmvLGJoHnisxnlB3zpdllYa0bpwZaujdLXJ7rKSXbdb5pvotfzz7f" .
	"VI1AYYtU2FLYElHYIklSYUthSxS2SJIkE7x0rF3FiPgnpDfljb+qp4vs5qkz" .
	"H2/t7+7vFq+Q2n47O1Eu8ZSS7TJKkO0d8RRPFbZIhS2FLRGFLZIkFbYUtkRh" .
	"iyRJMsFLxzZmtkbrm8WLEelN9+OsMs8V2f0z/6SbeXH57Zhnlez1mYJxAWKz" .
	"WZarXRW2SIUthS1R2DImkCSpsKWwJQpbJEmSSW0Q3nlXPaneL17YSm8rMU4G" .
	"8nSR+Vx4vPPQonkQEmVfTyzZq3pBKwV6H1UVtkgjqsKWKGwZE0iSVNhS2BKF" .
	"LZIkyUQm9IdbR8tHy8WrWqle1nP7S/2Sp4vs7tVg0qtE8ddzSxY3Tq3Ll/GR" .
	"ytLEenkunlbYIqmwJaKwRZIkFbZEYYskSXKA/fn85Ff2yVwUrYoXttL7wB1F" .
	"E88V2f1xSXoVZwqS/XAhbHw8bTbjenXudWXn0HilsEUqbClsicIWybPfHSQ5" .
	"WOb7hqCwJQpbJEmSA3mKQ1xf2OwUh9a9UZu9MDOW3jQ3rnLzdJHdP11GepX4" .
	"MOTpJfv59NO4xtp4pbBFKmwpbInCFslmioiUJwpborBFkiTZYxt3vz3LPo3b" .
	"mNkarW8WP1sr/jkpTXDj7+m5Iov4fqFh6a+uSpbcuGY0X+au1obm55vNvqLU" .
	"ZbxS2CIVthS2RGGLpMKWiChsKWwpbClskSRJ9sDYVs+Xyep0bWqlSFUrTueK" .
	"k7pSmuDGyUCeLrL7BQXph7gQlmyv8ZvKmrMLBz6wKmyRClsKW6KwRVJhS0RE" .
	"YUsUtkiSJJPaAizz5P7n85NfniuymNvDH85bKBsJSf5hbK/mS7N6faqleYUt" .
	"kgpbIgpbpMKWiIjClihskSRJ9rW3v9Qv5Z3APXiyuLd4rXhhK73reMxUyeLG" .
	"GXXxa+LgGieleZ7Jdvn15Y9P2Wcmq0/XDtaul+daaoUtkgpbIgpbpMKWiIjC" .
	"lihskSRJJniGTVSsile17ryrnlTvpze1VVAgSZKdK7NmTZyhFedpnZ6P3ajN" .
	"XpgZ82lSYYtU2FLYEoUtkgpbIqKwpbClsKWwRZIkOcDnNLTuq3OvKzuHKU1q" .
	"3y80hjxXJEmyk2ejxpWjWXP22ahKBgpbpMKWwpYobJFU2BIRhS2FLYUthS2S" .
	"JMmOu3rzzcW8U7fJ6nRtaqVIVSv+CelNauOv6ukiSZKdc+/K53vZZykfb+3v" .
	"7u82m5tFncsHSoUtUmFLYUsUtkgqbImIwpbClsKWwhZJkmQHjbOgsmZjZmu0" .
	"vln8bK30JvRxVpnniiRJdtq4fDlf4kLqZjO0uPbaZ0qFLVJhS2FLFLZIZk2+" .
	"dwdJDqIKWwpbClskSZJ9t8nXut8bx5XjSkrT2e3hD+c9XSRJsl/LB62U731y" .
	"VdgijZkKW6KwRVJhiyQVthS2FLZIkiQ7YsyjsiZOXCg+lU/1wp3bX+qXPF0k" .
	"SXIQrrceH6ksTayX59JqhS2SClsiClukwhZJKmyJwhZJkmTPjFLRz+cnv7JP" .
	"16JoVXwqn94n7L0rn+95ukiSZC+MS5mzZvXp2sHa9WaztTiFy8dKhS1SYctq" .
	"VxS2SIUthS2SVNhS2FLYIkmSbIMvLr8dyz5Ri+sLm53E0Lo3arMXZsbSm8gu" .
	"PN556OkiSZKpnJwaV2D7WKmwRSpsKWyJwhapsKWwRZIKWwpbClskSZJtsHH3" .
	"27PsE7U4ZaH4JD690xri7+m5IkmSvT0/NV/OPj81Sl0+WSpskQpbCluisEUq" .
	"bClskaTClsKWwhZJkmRO45NcvkxWp2tTK0Wm73E6V5zUldIUNk4s83SRJMne" .
	"Ghc0t3dLKepcPlkqbJEKWwpborBFKmwpbJGkwpbClsIWSZJkH23jlXn6/vP5" .
	"yS/PFUmSTLean2rhXmGLVNhS2BKFLWMCqbBFknZ8RGGLJElygC/Kad30rtQx" .
	"FyVJkmW4/Dq9K60VtkiFLYUtUdgyJpD9U9iK/y+SHCzT2/ERhS2SJMmOGLOm" .
	"rIkJd/Gq1tzV2tD8fHqT17n17WFPF0mS7CfjsuasiTO04jyt03O5OH/Lh0uF" .
	"LVJhS2FLFLZIha1OvDuMSyIiorBFkiSZrF9f/viUfXK2+nTtYO168cLWq3Ov" .
	"KzuHKU1b3y80hjxXJEmyX89VjYubs+bsc1VVEBS2SIUthS1R2CIVthS2RERE" .
	"YUthiyRJsiVXb765mHdyFqcpFKlqpXoeQ/xVPV0kSbI8p6umemaqwhapsKWw" .
	"JQpbJBW2REREFLZIkiR7/Ek6sjGzNVrfLH62VpzRldKENc4q81yRJMl+Ni5u" .
	"zpc776on1fvNZndR6vIRU2GLVNhS2BKFLVJhS2FLREQUtkwXSJIku7pR17rf" .
	"G8eV40pKE9bt4Q/nPV0kSXIQjEuc21vcX379aPbRkI+YClukwpbClihskQpb" .
	"ClsiIlLqwlYssaK2RfK0sVVvSk1mNT5bGEM46OY7W+vjrf3d/d3iVa0HTxb3" .
	"Fq+lN2G9/aV+yThJkiTLejX2+EhlaWLdR0yFLVJhS2FLFLZIhS2FLRERKXVh" .
	"S0QsvchOGGUXkXImilbFC1tR/ErpL7N35fM9IyRJkhw040LnrImLrZvN9OIU" .
	"LjPnXsVTTSpsidg1IBW2RERE2huFLRFLL1JhS6RniesL49SEIlWtG7XZCzNj" .
	"6f19Fh7vPDRCkiTJQTMudM6aw62j5aPlZvO9uD7b/LlX8VSTClsidg1IhS0R" .
	"EZH2RmFLxNKLVNgS6VnOPkehddM7cSE+6xsbSZLkIBoXOufL2Wevpnei6qDE" .
	"U00qbInYNSAVtkRERNobhS0RSy9SYUukZ5msTtemVopUteJ0rvT+Mi8uvx0z" .
	"NpIkyUE2Lndu74ZT1LnMorsfzzOpsCVi14BU2BIREWlvFLZELL1IhS2RHiTf" .
	"p4TTLr9+NPtoKKW/zM/nJ7+MiiRJcvCNy53zJS68blbWj2u1zai7Gc8zqbAl" .
	"YteAVNgSERFpbxS2RCy9SIUtkR5k7mptaH6+eGHrcOto+Wg5pb9MjAZGRZIk" .
	"mYaNu9+eZZ8RxYXXzWaAca22GXU340kmFbZE7BqQClsiIiLtjcKWiKUXqbAl" .
	"0tVExap4VSsqX+n9febWt4eNiiRJMhXjouesiTO04jyt0/PAuFbbvLqb8SST" .
	"Clsidg1IhS0REZH2RmFLxNKLVNgS6WriRITiha1X515Xdg5T+su8X2gMGQ9J" .
	"kmSKxqXPWROXX5dnNtjP8QyTClsidg1IhS0REZH2JnNh68676kn1fvEtRrL/" .
	"jafd0ovs58KWtxLLaapnKniHkiRJ650/5+yTWVM9b1Vhi1TYUtgSXzxIhS2F" .
	"LRERUdiyNU6FLYUtUmGL7DvjjK6UpqRfX/74ZCQkSZLpGpc+58vZq54odfnE" .
	"qbBFKmwZqURhi1TYUtgSERGFLVJhy5SaVNgiO+j3xnHluJLSlHR7+MN5IyFJ" .
	"kkzduAA6a+Lqw2Yzw7g20SdOhS1SYctIJQpbpMKWwpaIiChskQpbptSkwhbZ" .
	"ER88WdxbvJbSZPTn85Nfv3/f/lK/ZCQkSZKpG18M8iUuxT49PxwfqSxNrPvE" .
	"qbBFKmwZqURhi1TYUtgSERGFLVJhy5SaVNgiO+LHW/u7+7spTUb3rny+Zwwk" .
	"SZJlMi6Dzpq4FLvZLHFjZmu0vulDp8IWqbAlorBFKmwpbImIiMIWqbBFUmGL" .
	"bJs3arMXZsbSm4wuPN55aAwkSZJlMi6Dzpq4FLtsc0WFLVJhS2FLFLZIhS0R" .
	"ERGFLVvjVNiy9CIVtsiemd6pCfHh3uhHkiTLZlwGHRdDZ01ckF2e01gVtkiF" .
	"LYUtUdgiFbZEREQUtmyNU2HL0otU2CJ74PhIZWliPb1p6IvLb8eMfiRJsqzG" .
	"xdBZE5WsZvPGqHP53KmwRSpsiShskQpbIiIiCRa24sNQLIHItN2/evAvB//d" .
	"0otMqbAVGxjGNw6W6Z2UEOdJGPdIkmSZjYuh8yUuQGy26onLE330VNgiFbZE" .
	"FLZIhS0REZGkClsiYulFDm5ha/n1o9lHQ0Yqkd4mfu/GPZIkyaxVhkhclt1s" .
	"1bP6dO1g7bo5Z3vjWSUVtkTsGpAKWyIiIu2NwpaIpRepsCUiXc3c+vawcY8k" .
	"SfKPS6LzJS7OPr3qmaxO16ZWzDnbG88qqbAlYteAVNgSERFpbxS2RCy9SIUt" .
	"EelS3i80hox4JEmSfzUujM6aWN00W/u8Ove6snNo/tmueEpJhS0RuwakwpaI" .
	"iEh7o7AlYulFKmyJiLckSZLkgK2GDreOlo+Wm619Yq1k/tmueEpJhS0R30NI" .
	"hS0REZH2RmFLxNKLVNgSkY7n68sfn4x1JEmS/5VxYXS+zF2tDc3PN1sBRanL" .
	"XLR4PKWkwpaIXQOymzsFJMlmxm5L69m/evAvB/8SO6qt+/HW/u7+rlmcwpaI" .
	"pRepsKWwJTLw2R7+cN5YR5Ik2dy9K5/vZZ9lxdWHVkAKW6TClpFK7BqQJElz" .
	"bHNshS0RSy+vClJhS0T+np/PT379/n37S/2SsY4kSbK58T0hXyar07WpldMr" .
	"oPGRytLE+vfGceW4Yl6qsEXaTBKxa0CSJM2xzbEVtkQsvUgqbImUInFWhFGO" .
	"JEmyFbNebRBZfbp2sHa92TpoY2ZrtL5pXqqwRdpMErFrQJIkzbHNsRW2RCy9" .
	"SCpsiZQiC493HhrlSJIkW/PF5bdj2WdccYZWnKd1eh10ozZ7YWbMvFRhi7SZ" .
	"JGLXgCRJmmObYytsiVh6kVTYEkk8sWwwvpEkSbZuXCQdl0pnzYMni3uL13xs" .
	"VdgibSaJ2DUgSZLm2ObYClsill4kFbZESpo4H8L4RpIkmdW4VDprPt7a393f" .
	"bbYaijqXOarCFmkzScSuAUmSNMc2x1bYErH0IqmwJZJgvr788cnIRpIkmde5" .
	"9e3hvDOxs1dJcXmi+arCFmkzScSuAUmSNMc2x1bYErH0IqmwJZJU4hdtZCNJ" .
	"kuzmR9jIxszWaH3Tmkhhi7SZJGLXgCRJmmObYytsiVh6kVTYEilR4kwIIxtJ" .
	"kmQR44LpfBkfqSxNrJ9eE01Wp2tTK+arClukzSQRuwYkSdIc2xxbYUvE0ouk" .
	"wpZIItm78vmeMY0kSbJ9xmXTWbP6dO1g7XqzlVGcwmXuqrBF2kwSsWtAkiTN" .
	"sc2xFbZELL1IhS2FLRHvQZIkSbZhrXS4dbR8tNxsZRQrKXNXhS3SZpKIryUk" .
	"SdIc2xxbYUvE0ou0CaGwJTLAibMfjGYkSZLt9faX+qW8M7S5q7Wh+flm66Mo" .
	"dZnHKmyRNpNE7BqQJElzbHNshS0RSy9SYUthS2Qg8+Ly2zGjGUmSZGeMi6ez" .
	"5uzPsg+eLO4tXjOPVdgibSaJ2DUgSZLm2ObYClsill6kwpbClsiA5efzk19/" .
	"nP1gNCNJkuyE8bUhXyar07WpldPro/GRytLE+vfGceW4Yk6rsEXaTBKxa0CS" .
	"JM2xzbEVtkQsvUiFLYUtkYFJnPdgHCNJkuy0jbvfnmWfra0+XTtYu95slbQx" .
	"szVa3zSnVdgibSaJ2DUgSZLm2ObYClsill6kwpbClsjAZG59e9g4RpIk2Xnj" .
	"EuqsiTO04jyt06ukOH/LnFZhi7SZJGLXgCRJmmObYytsiVh6kQpbClsiA5BY" .
	"GBjBSJIku2NcQh0XUmfNgyeLe4vXfIpV2CJtJonYNSBJkubY5tgKWyKWXiQV" .
	"tkQGOHHGgxGMJEmy/1dPh1tHy0fLzdZKUecyv1XYIm0midg1IEmS5tjm2Apb" .
	"IpZepC0HhS2RPs3Xlz8+GbtIkiR7YVxInS9nr6Gi1GWuq7BF2kwSsWtAkiTN" .
	"sc3iFLZELL1IhS2FLZG+S/xmjV0kSZKD8ok2sjGzNVrftGJS2CJtJonYNSBJ" .
	"kubY5tgKWyKWXiQVtkQGLHGug7GLJEmyV67efHMx71xusjpdm1o5vWIaH6ks" .
	"Tayb6ypskTaTROwakCRJc2yzOIUtEUsvUmFLYUukj7J35fM9oxZJkmR/GBdV" .
	"Z83q07WDtevN1k1xCpd5r8IWaTNJxK4BSZI0xxaFLRFLL1JhS2FLxJuOJEmS" .
	"f3F7+MP57DO6w62j5aPlZuumWGeZ9ypskTaTRHxLIUmS5tiisCVi6UUqbCls" .
	"ifQ4cX6D8YokSbJ/vP2lfinv7O7Bk8W9xWvNVk8fb+3v7u+aAytskTaTROwa" .
	"kCRJc2xR2BKx9CIVthS2RHqWF5ffjhmvSJIk+8+4tDprzv5oG3Uuc2CFLdJm" .
	"kohdA5IkaY4tClsill6kwpbClkgP8vP5ya8/zm8wXpEkSfabC493Huad6U1W" .
	"p2tTK6dXT+MjlaWJ9e+N48pxxXzYM0baTBKxa0CSJM2xRWFLxNKLVNhS2BLp" .
	"auLMBiMVSZJkP9u4++1Z9pnexszWaH2z2Rpq9enawdp182FPF2kzScSuAUmS" .
	"NMcWhS0RSy9SYUthS6SrmVvfHjZSkSRJ9rdxgXXWxBlacZ7W6TVUnL9lPuzp" .
	"Im0midg1IEmS5tiisCVi6UUqbClsiXQpMfU3RpEkSQ6KcZl11sRayYdahS3S" .
	"ZpKIXQOSJGmObY6tsCVi6UUqbClsifQ4cU6DMYokSTLttdXh1tHy0XKzldTc" .
	"1drQ/LzCFkmbSSJ2DUiSpDm2KGyJWHqRNhUUtkQ6mK8vf3wyOpEkSQ6acZl1" .
	"vpy9wopSl8IWSZtJInYNSJKkObYobIlYepEKWwpbIh1J/CqNTiRJkoPo+4VG" .
	"jpXPq3OvKzuH1lMKW6TNJBG7BiRJ0hzbLE5hS8TSi1TYUtgS6UHibAajE0mS" .
	"5CC6evPNxbzzwMnqdG1q5fR6anyksjSxrrBF0maSiF0DkiRpji0KWyKWXqTC" .
	"lsKWSJuzd+XzPeMSSZLk4BuXXGfN6tO1g7XrzVZVGzNbo/VNhS2SNpNE7BqQ" .
	"JElzbFHYErH0IhW2FLZEvMtIkiT5F7eHP5zPPhv83jiuHFearapu1GYvzIwp" .
	"bJG0mSTiSwtJkjTHFoUtEUsvUmFLYUukDWnc/fbMiESSJJmKt7/UL/3+/fP5" .
	"ya/sM8MHTxb3Fq81W1t9vLW/u7+rsEXSZpKIXQOSJGmOLQpbIpZepMKWwpZI" .
	"oby4/HbMiESSJJmWceF11pz9STfqXApbJG0midg1IEmS5tiisCVi6UUqbCls" .
	"ieRMnLsQZzAYkUiSJFNy4fHOw7yzxLgAsdkKKy5PVNgiaTNJxK4BSZI0xxaF" .
	"LRFLL1JhS2FLJHPiN2gsIkmS9En3z9mY2RqtbzZbYa0+XTtYu66wRdJmkohd" .
	"A5IkaY4tClsill6kwpbClkjmzK1vDxuLSJIk0zUuv86X8ZHK0sT66RXWZHW6" .
	"NrWisEXSZpKIXQOSJGmOLQpbIpZepMKWwpZIhsTk3ihEkiRZBuMi7KyJlVSz" .
	"ddarc68rO4cKWyRtJonYNSBJkubYorAlYulFKmwpbIm0lNWbby4ahUiSJK28" .
	"mudw62j5aLnZOmvuam1ofl5hi6TNJBG7BiRJ0hxbFLZELL1IhS2FLZF/kK8v" .
	"f3wy/pAkSZbJuAg7X6KY1Wy1FaUuhS2SNpNE7BqQJElzbFHYErH0IhW2FLZE" .
	"miZ+d8YfkiTJsvl+oZFjXRRXH5ZzteWZIW0midg1IEmS5tiisCVi6UUqbCls" .
	"ibQht7/ULxl/SJIky2d8u8iXyep0bWrl9GprfKSyNLH+vXFcOa4obJG0mSRi" .
	"14AkSZpji8KWiKUXqbClsCXyl+xd+XzPyEOSJFlu44LsrFl9unawdr3Zmmtj" .
	"Zmu0vqmwRdJmkohdA5IkaY4tClsill6kwpbCloi3FUmSJP/ii8tvx7LPJOMM" .
	"rWZrrhu12QszY+nNnz0tpM0kEd9hSJKkObYobIlYepEKWwpbIjnTuPvtmTGH" .
	"JEmSf1yQ/fP5ya/ss8oHTxb3Fq+V58Oup4W0mSRi14AkSZpji8KWiKUXqbCl" .
	"sCWSM3GOgjGHJEmSYVyWnTUfb+3v7u82W3lFnUthi6TNJBG7BiRJ0hxbFLZE" .
	"LL1IhS2FLSl14uyEOEfBmEOSJMlwbn17OO8MMy5AbLb+issTFbZIm0k2k0Ts" .
	"GpAkSXNsUdgSsfQiFbYUtqSkiV+Z0YYkSZLFP/hGNma2RuubZVh/eUJIm0ki" .
	"dg1IkqQ5tihsiVh6kQpbClsimRNnJxhtSJIkedq4ODtfxkcqSxPrp9dfk9Xp" .
	"2tSKwhZpM8lmkohdA5IkaY4tClsill6kwpbClpQuMX03zpAkSfJsv7788Sn7" .
	"bHP16drB2vVmq7BX515Xdg4VtkibSTaTxK6BMYEkSZpji8KWiKUXqbAlUqKs" .
	"3nxz0ThDkiTJzqzLDreOlo+Wm63CYtWmsEXaTLKZJHYNjAkkSdIcWxS2RCy9" .
	"SIUtkVIkzkgwwpAkSbIV4xLtfJm7Whuan2+2FotSl8IWaTPJZpLYNSBJkjTH" .
	"FoUtEUsvUmFLJPHEL8sIQ5Ikydbdu/L5XvaZ59mfgB88WdxbvKawRdpMspkk" .
	"dg1IkiTNsUVhS8TSi1TYEkk8t7/ULxlhSJIkmcX4spEvk9Xp2tTK6bXY+Ehl" .
	"aWL9e+O4clxR2CJtJtlMErsGJEmS5tiisCVi6UUqbIkkmDgXwdhCdtoXl9+O" .
	"/fEWI9kJ41dmtCG7b+Put2fZZ6GrT9cO1q43W5FtzGyN1jcVtkibSTaTxK4B" .
	"SZKkObYobIlYepEKWyLeRyQzGyfYiUin8/P5yS9jDtm7UnLWxBlacZ7W6RVZ" .
	"nL+lsEXaTLKZJL7SkCRJmmOLwpaIpRepsCWSVOIsBKMK2Wm3hz+cN+KIdCvO" .
	"2SJ7VU2O0mTWPHiyuLd4LaXPvp4H0maSiF0DkiRpji0KWyKWXqTClsKWiC1t" .
	"ciAvihKRfIlPUUYeclBWah9v7e/u7zZbl0WdS2GLtJlkM0nsGpAkSZpjK2yJ" .
	"iKUXqbAlMvBxaRTZHRce7zw04oj0InPr28NGIbK7xu8uX85erx1uHS0fLSts" .
	"kTaTbCaJXQOSJElzbIUtEbH0IhW2RAY48TsynpCddu/K53tGHBFvOtLn4H+U" .
	"jZmt0fpmGqszzwBpM0nErgFJkjTHFoUtEUsvUmFLYUvkv4hzR8juGKfZiUj3" .
	"4yxJsleu3nxzMe8vd7I6XZtaOb06i/+7whZpM8lmktg1IEmSNMdW2BIRSy9S" .
	"YUtkIPN+oTFkJCE774vLb8eMOCK9TvwSjUhk9/368sen7L/Z1adrB2vXm63R" .
	"4hQuhS3SZpLNJLFrQJIkaY6tsCUill6kwpbIgCXOPDCSkP15IZSItDfxSzQi" .
	"kYOyajvcOlo+Wm62Ros1ncIWaf5sM0nsGpAkSZpjK2yJiKUXqbAlMjCJcw6M" .
	"IWSnjStH86XZVVAkb9RmL8zkPLfORcBk9739pX4p79vwwZPFvcVrzUaDKHUp" .
	"bJE2k2wmiV0DkiRJc2yFLRGx9CIVtkQGINvDH84bQ8h+fU/lWwyTZfPjrf3d" .
	"/d2sv6/4VRqdyO67d+XzvXa/E6POpbBF2kyymSR2DUiSJM2xFbZExNKL/H/t" .
	"3bGPFGeax3GJiH/BEQ5Gl3JyPMvuH2GR0IKABCSfRODB0kY22U0wLZ10jDRC" .
	"BBNxcAiZxGKAQas5yTi4I/AEBLaxHHjFzEU2c4i1T7rgJ0tl9VZdV3dVdVX3" .
	"5xt8A+8aM11Vbz1vP795XoEtYABkzoE1hLltZ5pdXaqniTDzPEENMyaZF+V8" .
	"7zEbZVMnz58Z3b6w9+b4ZHQyEthi1kzSTIKuATMzM7MaW2ALgK0Xs8AW0FMy" .
	"28DqwdzPtnRazmk/i+MwV3ueoMaDj7/+wErFvAgff/rz5/Xfj4839s8+fVK2" .
	"Gjy4//D7hx8JbDFrJmkmQdeAmZmZWY0tsAXA1otZYAvoKdt3n9+yejD39eCn" .
	"6oY0M086T03dZ+2b7eNTVirmRfjRuZfrTQeaM39LYItZM0kzCboGzMzMzGps" .
	"gS0Atl7MAltA78g8A+sGc9vOkaOzUfcNxczXxtfXNtZne+I29w5OW7WYF+F3" .
	"X/zya/1ntvrI4H5+KexaM2smAboGzMzMrMaGwBZg68UssCWwhZUm8wysG8z9" .
	"nB3yev9o92hX+IZ5Nn/3yasXr17Ufe5SSVq1mIeyj6t+V25eHJ/a2hLYYtZM" .
	"0kyCrgEzMzOzGltgC4CtF7PAFtALMsPAisHcjTPNri5514jdMM/mzN2p+9z9" .
	"9OztD1Yt5kU48+1mo3o3l1CXwBazZpJmEnQNmJmZmdXYAlsAbL2YBbaABWOC" .
	"CHM33r77/Nasz+nlG1fHV+7ME1jJ2y3vLOZV87+9u/+P//5ktqfvwcdff2AF" .
	"Y16Ev9k+nmGX9eV7X42evx7K3s1VZtZMAnQNmJmZWY0NgS3A1otZYEtgCytK" .
	"ZhhYMZj7+Vaqbjy3fSQcgMMPf/zMCsa8CCcu2WzQ+fyZ0e0LewJbzJpJmknQ" .
	"NWBmZmZWYwtsAbD1YhbYAhZG5hZYK5i7cY4frR2pvDg+tbU1T1QrTWsrHjAP" .
	"f/7r0z9Yx5gX4RxOWpcH9x9+//Cjsjfj4439s0+f9GFtcX2ZNZMAXQNmZmZW" .
	"Y0NgC7D1YhbYEtjCyuGYJ+Zu/Ojcy/X6T+ib45PRyWj+2VppWlvxgHk4OP3t" .
	"+1Yz5kU4T1+z79Ds+AS2mDWTNJOga8DMzMysxhbYAmDrxSywBXRKZhVYJZi7" .
	"cabZ1Y5UVk4Hmd6v9492j3ate4D3JvMQnfl2s3Hz3s7hzqU+Hxbs+jJrJgG6" .
	"BszMzKzGhsAWYOvFLLAlsIUVwqQQ5m68uXdwetbnNEcZzhPVynGKVjzAvox5" .
	"6D788MfP6j+z1V8fJ84lsMWsmaSZBNUpMzMzsxpbYAuArRezwBbQEZlVYJVg" .
	"7udBTpn5Mf9srS/f+2r0/LUVD2iKREasbMzde/vu81uzPrnXxtfXNtbL3pU5" .
	"PFFgi1kzSTMJugbMzMysxlZjC2wBsPViFtgCNJuZl8Q5Rq0u1Uc4TePzZ0a3" .
	"L+xZ8YA2EHpmHsrXx+Hxxv7Zp0/K3pg5gFhgi1kzSTMJugbMzMysxlZjC2wB" .
	"sPViFtgCWiTzCawPzG079dtsJG41T2DLuwloj0fnXq5b5ZgX4Tx9dckMrbJ3" .
	"aw4gFthi1kzSTIKuATMzM6ux1dgCWwBsvZgFtoBWOP7058+tDMxdOdPs6lI9" .
	"BWR6v94/2j3ate4B3qfMy+d3X/zya/0nN7u2vh0i7GoyayYBugbMzMysxobA" .
	"FmDrxSywJbCFJcdEEOZunOPSZmsn130HTfra+PraxroVD2gbEyuZh7XLS5S5" .
	"7O25eXF8amtLYItZM0kzCboGzMzMrMZWYwtsAbD1YhbYAhojwRFrAnOfD2yq" .
	"biRP78zosu4BbZMpelY85u69uXdwetYnt3qv1/18SleTWTMJ0DVgZmZmNTYE" .
	"tgBbL2aBLYEtLC15FqwJzN04x6XV5cH9h98//GieqNb5M6PbF/beHJ+MTkbW" .
	"PaBtEobORD3rHnP3/mb7eIY9WI4+7M/OznVk1kwCdA2YmZlZjQ2BLcDWi1lg" .
	"S2ALS0vmEFgTmPs88+PyjavjK3fmCWzdvLdzuHPJigd0ieOGmRflfE/S7Ds3" .
	"0WeBLWbNJM0k6BowMzOzGluNLbAFwNaLWWALmIvMHrAaMPf5vVM97cN2F+gz" .
	"mahn9WNelH969vaH+k9u9VTLLg8XdgWZNZMAXQNmZmZWY0NgC7D1YtY4F9iC" .
	"NwgzzzXnI0ek1SWTseaJamVSiBUPWBQHp799/7d1IHZUInM3ztNXlxwfXPZW" .
	"vTa+vraxLrDFrJmkmQTf+TAzM7MaW40tsAXA1otZYAuoTeYNWAeYpz/KsBi2" .
	"yBuk6GxZ49nmedRtGHsTActBopzFNeTwwx8/+/0K8+Djrz/4/SpkZWaexglH" .
	"thGY7uZLZFeQWTMJ0DVgZmZmNTYEtgBbL2aBLYEtLBWZN2Ad4NVp1haDDo/O" .
	"vVwvj1vlCLM+UH0k0/R+vX+0e7Rr3QNEvkS+eDWdJ6Iu333y6sWrF2Xv1sS5" .
	"BLaYNZPUIdA1YGZmZjW2GltgC7D18qpgFtgCarR1HcbEw/L23ee3fqt5Ejso" .
	"BhHSiC2GFZaDHLo0T1Rr8+L41NaWdQ/A/JGvYtgra7J3Ew9rUmYb7+LMwhTY" .
	"YtZMAnQNmJmZWY2txhbYAmy9mFlgC/h/SAvWCsCLPViwesZVU0cKDpfqqR7T" .
	"+/HG/tmnT6x7ANom63ZxJS8Le3kb8lC+XA55ky5qr+eqMWsmAboGzMzMrMZG" .
	"swhsAbZezAJbwMIwFYPbOGpwcuqV6NU85N0xT1Tr/JnR7Qt7PkkAfaY64KVi" .
	"4WadsPhs5K06+ba9fOPq+Mqd9p4RV41ZMwnQNWBmZmY1NppFYAuw9WIW2AIW" .
	"1hb17HO1i3NQJgNYx5/+/LlnqX3KGsPePgBWk+Khjd9sH5/67d10cPrb9397" .
	"Z2WOo/c4V3u2KHl1kPrL974aPX/dxp3vejFrJgG6BszMzKzGRrMIbAG2XswC" .
	"W8ACyFwBz/4qH0SYxnZWvzS8s9FKIxx9oPropemdQxV9ngBWk+IRjTkMOu++" .
	"1EICXvaAdXm9f7R7tFv2zs0OsY072fVi1kwCdA2YmZlZjY1mEdgCbL2YBbaA" .
	"Tkkcx1O/3AcRpiHtIMKhs3lxfGpra56o1rXx9bWNdZ8kAExP5kdOHs6Y92ze" .
	"uXn/qkOWI8jexjs6oa5m70zXi1kzCdA1YGZmZjU2mkVgC7D1YhbYAjold7un" .
	"fihzsBxEOFwy1yoby/jB/YffP/wovnlv53DnUt4jccJV88/TKjozulwLoA88" .
	"+e7ZzrN/yPOeqEdxTcjTWlwxfGJDYTLgVTycUbSr/07MvS7VXze3se9zpZg1" .
	"kwBdA2ZmZlZjo1kEtgBbL2aBLaBTHPqzWG/ffX7r99OwchyhGFbfyGyMsrhV" .
	"XIxbXb5xdXzlTrNxq/n95vhkdDJyNYH+rCqzPcvnz4xuX9grrjkJfRZXpC/f" .
	"+2r0/LXIVz/JfFOhrn46V2E2yt7+eWabfQu7UsyaSYCuATMzM6ux0SwCW4Ct" .
	"F7PAFtARCQZ53oWxVodicCFRhmK4IRNuigGIvsWt5nHCHO4BoG8sarUR+RoK" .
	"+dIzM59SS2SPL3Dftmc7QjrPTjdzLl0jZs0kQNeAmZmZ1dhoFoEtwNaLWWAL" .
	"8I4QxsLfofpIwayoxfBB4gjLFLqaxwleuIuAvpEAxxBXlUwSKq66WYeLK3Nx" .
	"xc4a7oq3gVBXG3507uV6/WuRGVplFUgOO27qurtGzJpJgG+EmJmZWY2NZhHY" .
	"Amy9mAW2gNbJzABPujDWYikGsMoOFhS0aipU4X4D+syqBUyLYa/iZK/E1/Je" .
	"yJGR7o2mEOqq6xxMmcMr65K7uu2vmF0jZs0kQNeAmZmZ1dhoFoEtwNaLWWAL" .
	"aJ2D09++L4wljNU0aa5PBrCKRw2aetW9vWuA/pPn1Ho1TcCrOMereGhjJhu5" .
	"l+ZBqGvS+TTqkkh624cU27MzayYBugbMzMysxkazCGwBtl7MAltAi2ROQGYG" .
	"CGOhmjS/sxFKUzwN8jQas+KliS5M0GebUgP0nzyn1qumnIPnJgNexSMa3XWz" .
	"kS9hU1+l1krdlRps+WrLhNVmo3pvOP/b2Z6dWTMJ0DVgZmZmNTaaRWALsPVi" .
	"FtgCWiRzAob7bOa9lglh+VmEsepSbFengZ21KCtYmtya/cvhXFP3PDAUrMCL" .
	"XS3j4oTIvCszLcn9OT35ojb7qUfnXq4PP85V96vnkCM+29sJ2rMzayYBugbM" .
	"zMysxkazCGwBtl7MAltAi/S/YSaSVZc0kqsPItSMX9bJMcX5MUVnIprZWsCw" .
	"KM41LHryGS+uAGJeXToH++aTz3tWwKsuQ4xzZYrYbJQdBp0ZpfN8kvbszJpJ" .
	"gK4BMzMzq7HRLAJbgK0Xs8AW0GJ7rD8HF6ZFl2dNJKt665IJDWkGO4hw6M61" .
	"K4tbTR7aJXQFoC5ZN8rmKYp8dR+xLUa7EqgV6qquV/sZ5/rp2dsf6v9Eue5l" .
	"d0hqvNk+K3t2Zs0kQNeAmZmZ1dhoFoEtwNaLWWALaIU0vRYVyZrtKJllpThD" .
	"ZfJQwrJJDNy32SrxzXs7hzuXJqdbmbMCYLjUjXyJETcb6kqIxxeRkxTjXJl6" .
	"tbl3cLrfO8Q8TW0cXmzPzqyZBOgaMDMzsxobzSKwBdh6MQtsAQ3z7otffhXJ" .
	"6gphrGEdKZgGeTGCUGyTx7mm7m0AmJ7icb3THOwo8jX9dEahrrJat+0415//" .
	"+vQPs/4NE+8uu76zTdO0Z2fWTAJ0DZiZmVmNjWYR2AJsvZgFtoCGyf0skjU/" .
	"wlh9c9nBgo4UBIChU4x8Fd+5Cdp68wp1TU+zca7DD3/8rP7fofrL6MS56v6Z" .
	"9uzMmkmArgEzMzOrsdEsAluArRezwBbQMGXtqPzztKxEsoSx+jb7quyQQU80" .
	"AKDsy77JaV7FaK/3rFDXJHXjXPmOZTbKJsmlzqw70dOenVkzCdA1YGZmZjU2" .
	"mkVgC7D1YhbYAhrj+NOfP/97kay0plYHYaw+NIYzPWLy2EETsAAA3VMd8EqA" .
	"eDXf3amLiuHp1E6reUxwWZzrp2dvf6j/p6X+Kfvk879O/6fZszNrJgG6BszM" .
	"zKzGRrMIbAEABozAFrDYtqswVpcHEeZQqslWbg6xck8CAJaDYuw7Ex8nA15l" .
	"k5NWJ9SVsJEaYJp7qaw6zV3kUwL6udPUTAIAAADU2KuAwBYAYMAIbAFtF/EJ" .
	"BmVW0ypPv+jmIMLi9BF3IAAA05DJkXl7JsaUt2qql9UJeIlzlZE7wRfQwLD2" .
	"oZpJAAAAgBp7FRDYAgAMGIEtYB7SxktLTySrqRhWcQ5W5oKs5pFGAAD0uf4p" .
	"Hs5YjHaJcy0fifSVfTKp3DwXQH/QTAIAAADU2KuDwBYAYMAIbAHVFCNZxYOE" .
	"hKuaCmOZXQEAwPJRPJZxFQ6AXoU4V3UNnFCXOx/oA5pJAAAAgBp7dRDYAgAM" .
	"GIEtQCRLGAsAAHTPKoS68lPkJ8rPmBppiPGmVMv2iUD/0UwCAAAA1Nirg8AW" .
	"AGDACGxhFRDJmn9ihDAWAADonuUOdQ0xznX5xtXxlTtlP4s7FugDmkkAAACA" .
	"Gnt1ENgCAAwYgS0sByJZ84Sxbt7bOdy5lM8tWwiH2gAAgKFQDHVNVoNlASNx" .
	"rtnI36Ts75zP3z0JLBbNJAAAAECNvToIbAEABozAFoZCWlNpU6VRtEzH5Qhj" .
	"AQAAtM0yhboWFefKn19df7rTgD6sdZpJAAAAgBp7FRDYAhomXzUyczeu25wQ" .
	"2EJ7vPuvv/3xb38UyRLGAgAAWBTLEeqajHPl58o0svk/pVShZf/1yYOzj//5" .
	"v//p+Be7b+ZuXP2EaiZB14CZmZlZjb1MCGwBDaMNz9xnC2yhDf7n4du1t2ue" .
	"r8lIVp64tAwnW18AAADokuLxi6nTNi+OT21tDSvOdW18fW1jPV83z1ZnVv9u" .
	"cf7k6f//zNwHayZB14CZmZlZjT1EBLYAWy9mgS1gLnJfrdrTlFaZSBYAAMBy" .
	"kDhX5sUWp3MNZV5s3blcqWbL/pzivyWwxayZBOgaMDMzsxobbSCwBdh6MQts" .
	"AXMxrJkEs00vKDa9XHEAAIBVI0dXDzfONTmXq/pIiFS/+dkFtpg1kwBdA2Zm" .
	"ZlZjow0EtgBbL2aBLWBG0rISyQIAAMAqM/Q416TzKxn56QS2mDWTAF0DZmZm" .
	"VmOjDQS2AFsvZoEtYEbSiBLJAgAAAMoYbpwrlbPAFrNmEqBrwMzMzGpstIHA" .
	"FmDrxSywBczYdhLJAgAAAOavq/sZ59q8OD61tSWwxayZBOgaMDMzsxobbSCw" .
	"Bdh6MQtsAbXJvdTlgSxpF+W/m0L5zfHJ6GTkWgAAAGBZKYtzdbmL/Mv//sef" .
	"Dv7TbppZMwnQNWBmZmY1NppFYAtomHx1yMz99OON/bNPn1ipMA+JSTX7G/8i" .
	"WQAAAEBdvvvk1YtXL7LLay/O9S9/unn1X0/sppn77KwGVkX0Gc8pMzMzq7Ex" .
	"icAWAABADdIQmq3ZkzI3zaTMCcjMAJ8qAAAA0Cz5FYgcGp5fjcivSdSt4fOr" .
	"Gj5PAAAAAADQLAJbAAAANbg2vr62sT7NrKw0h4yNBQAAAPpDptjWPWbRtGYA" .
	"AAAAANAsAlsAAABTkeiVWVkAAADAspJDH/KrFzfv7RzuXMqvZOTXNnw+AAAA" .
	"AACgKf4PKNhmJjctQAYAAAAASUVORK5CYII=";
}

exit(0);

$root = $dom->getRootElement();

$rawXML = <<<END_XML
		<path fill="#000000" d="
			m315.32547 191.05774
			l-292.6666 0 -3.522379 3.5223846 0 17.61148 0 0 292.6666 0 3.5224 -3.5223846 0 -17.61148
			z" fill-rule="nonzero"/>
		<path stroke="#000000" stroke-width="2.0" stroke-linejoin="round" stroke-linecap="butt" d="
			m315.32547 191.05774
			l-292.6666 0 -3.522379 3.5223846 0 17.61148 0 0 292.6666 0 3.5224 -3.5223846 0 -17.61148
			z" fill-rule="nonzero"/>
		<path fill="#ffffff" d="
			m43.538296 208.54468
			l-2.984375 0 -1.1875 -3.09375 -5.4375 0 -1.125 3.09375 -2.906248 0 5.296873 -13.59375 2.90625 0 5.4375 13.59375
			z
			m-5.046875 -5.375
			l-1.875 -5.046875 -1.84375 5.046875 3.71875 0
			z
			m5.566696 0.953125
			l2.671875 -0.265625
			q0.234375 1.34375 0.96875 1.984375 0.75 0.625 2.0 0.625 1.328125 0 2.0 -0.5625 0.671875 -0.5625 0.671875 -1.3125 0 -0.484375 -0.28125 -0.8125 -0.28125 -0.34375 -0.984375 -0.59375 -0.484375 -0.171875 -2.203125 -0.59375 -2.203125 -0.546875 -3.09375 -1.34375 -1.265625 -1.125 -1.265625 -2.734375 0 -1.046875 0.59375 -1.953125 0.59375 -0.90625 1.703125 -1.375 1.109375 -0.46875 2.671875 -0.46875 2.5625 0 3.859375 1.125 1.296875 1.109375 1.359375 2.984375
			l-2.75 0.125
			q-0.171875 -1.046875 -0.75 -1.5 -0.578125 -0.46875 -1.75 -0.46875 -1.1875 0 -1.875 0.5 -0.4375 0.3125 -0.4375 0.84375 0 0.484375 0.421875 0.828125 0.515625 0.421875 2.515625 0.90625 2.0 0.46875 2.953125 0.984375 0.96875 0.5 1.515625 1.375 0.546875 0.875 0.546875 2.15625 0 1.171875 -0.65625 2.203125 -0.640625 1.015625 -1.828125 1.515625 -1.1875 0.484375 -2.96875 0.484375 -2.578125 0 -3.96875 -1.1875 -1.375 -1.1875 -1.640625 -3.46875
			z
			m13.131073 4.421875
			l0 -13.59375 4.421875 0
			q2.5000038 0 3.2656288 0.203125 1.15625 0.296875 1.9375 1.328125 0.796875 1.015625 0.796875 2.640625 0 1.25 -0.453125 2.109375 -0.453125 0.859375 -1.15625 1.34375 -0.703125 0.484375 -1.421875 0.640625 -0.9843788 0.203125 -2.8437538 0.203125
			l-1.796875 0 0 5.125 -2.75 0
			z
			m2.75 -11.296875
			l0 3.859375 1.5 0
			q1.625 0 2.171875 -0.21875 0.5468788 -0.21875 0.8593788 -0.671875 0.3125 -0.453125 0.3125 -1.046875 0 -0.75 -0.4375 -1.234375 -0.4375038 -0.484375 -1.0937538 -0.59375 -0.5 -0.09375 -1.984375 -0.09375
			l-1.328125 0
			z
			m9.693577 11.296875
			l0 -13.59375 10.09375 0 0 2.296875 -7.34375 0 0 3.015625 6.828125 0 0 2.28125 -6.828125 0 0 3.703125 7.609375 0 0 2.296875 -10.359375 0
			z
			m21.146698 -5.0
			l2.671875 0.84375
			q-0.609375 2.21875 -2.046875 3.3125 -1.421875 1.078125 -3.609375 1.078125 -2.703125 0 -4.453125 -1.84375 -1.734375 -1.859375 -1.734375 -5.078125 0 -3.390625 1.75 -5.265625 1.75 -1.875 4.609375 -1.875 2.5 0 4.046875 1.46875 0.9375 0.875 1.390625 2.5
			l-2.71875 0.65625
			q-0.234375 -1.0625 -1.0 -1.671875 -0.765625 -0.609375 -1.859375 -0.609375 -1.515625 0 -2.453125 1.09375 -0.9375 1.078125 -0.9375 3.5 0 2.578125 0.921875 3.6875 0.921875 1.09375 2.40625 1.09375 1.109375 0 1.890625 -0.6875 0.78125 -0.703125 1.125 -2.203125
			z
			m7.832321 5.0
			l0 -11.296875 -4.03125 0 0 -2.296875 10.8125 0 0 2.296875 -4.03125 0 0 11.296875 -2.75 0
			z
			m7.645981 -4.421875
			l2.671875 -0.265625
			q0.234375 1.34375 0.96875 1.984375 0.75 0.625 2.0 0.625 1.328125 0 2.0 -0.5625 0.671875 -0.5625 0.671875 -1.3125 0 -0.484375 -0.28125 -0.8125 -0.28125 -0.34375 -0.984375 -0.59375 -0.484375 -0.171875 -2.203125 -0.59375 -2.203125 -0.546875 -3.09375 -1.34375 -1.265625 -1.125 -1.265625 -2.734375 0 -1.046875 0.59375 -1.953125 0.59375 -0.90625 1.703125 -1.375 1.109375 -0.46875 2.671875 -0.46875 2.5625 0 3.859375 1.125 1.296875 1.109375 1.359375 2.984375
			l-2.75 0.125
			q-0.171875 -1.046875 -0.75 -1.5 -0.578125 -0.46875 -1.75 -0.46875 -1.1875 0 -1.875 0.5 -0.4375 0.3125 -0.4375 0.84375 0 0.484375 0.421875 0.828125 0.515625 0.421875 2.515625 0.90625 2.0 0.46875 2.953125 0.984375 0.96875 0.5 1.515625 1.375 0.546875 0.875 0.546875 2.15625 0 1.171875 -0.65625 2.203125 -0.640625 1.015625 -1.828125 1.515625 -1.1875 0.484375 -2.96875 0.484375 -2.578125 0 -3.96875 -1.1875 -1.375 -1.1875 -1.640625 -3.46875
			z" fill-rule="nonzero"/>
		</svg>
END_XML;
$dom2 = new SVGDocument();
$dom2->loadXML($rawXML);

foreach ($dom->importNode($dom2->documentElement, true)->childNodes as $child) {
	$clone = $child->cloneNode(true);
	print "--- child: ---\n";
	print var_export($clone->C14N()) . PHP_EOL;
	print var_export($clone, true) . PHP_EOL;
	var_dump($clone);
	$root->appendChild($clone);
}

print "--- main ---\n";
print $dom->saveXML();

