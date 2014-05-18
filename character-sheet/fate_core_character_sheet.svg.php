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

$doc = XML_SVG_Document::getInstance();

// root element
$svg = XML_SVG_Root::getNew();
$svg->{'viewBox'} = "0.0 0.0 {$dim['width']} {$dim['height']}";

// image clipping path
$clipPath = $svg->appendChild(XML_SVG_ClipPath::getNew());
$clipPath->id = $clipId = "p.0";
$clipRect = XML_SVG_Rect::getNew(0, 0, $dim['width'], $dim['height']);
$clipPath->appendChild($clipRect);

$layer = XML_SVG_Group::getNew();
$svg->appendChild($layer);
$layer->{'clip-path'} = "url(#$clipId)";
$layer->appendChild(mkGuideBox(0,0,$dim['width'], $dim['height']));

//$groupID = XML_SVG_Group::getNew(LEFT_MARGIN, TOP_MARGIN);
$groupID = mkSectionGroup("ID", LEFT_MARGIN, TOP_MARGIN, 605, 137);
//$groupID->transform = "translate(19,37.5)";
$groupIDFields = XML_SVG_Group::getNew(0, BOX_HEIGHT+BOX_MARGIN);
$groupIDFields->id = "groupIDFields";
setTransform($groupIDFields);
$boxName = mkOpenBox("Name", 0, 0, 488, 21);
$boxDescription = mkOpenBox("Description", 0, $boxName, 488, 77);
//$boxDescription->{'below-of'} = $boxName;
$boxRefresh = mkOpenBox("Refresh", $boxName, 0, 108, 107);
//$boxRefresh->{'right-of'} = $boxName;
$groupIDFields->appendChildren($boxName, $boxDescription, $boxRefresh);
$groupID->appendChild($groupIDFields);
$layer->appendChild($groupID);

error_log("groupID y/height/bottom = {$groupID->y}/{$groupID->height}/{$groupID->bottom}");

$groupAspects = mkSectionGroup("ASPECTS", LEFT_MARGIN, $groupID, 296, 171);
setTransform($groupAspects);
//$groupAspects = XML_SVG_Group::getNew(LEFT_MARGIN, $groupID, SECTION_MARGIN);
//$groupAspects->appendChild(mkGuideBox(0,0,296, 171));
//$groupAspects->appendChild(mkClippedRect("ASPECTS", 0, 0, 296));
$layer->appendChild($groupAspects);

// all done.
print $doc->saveXML();


/* UTILITY FUNCTIONS */

function mkSectionGroup($title, $right_of, $below_of, $width, $height) {
	//error_log(__METHOD__ . " BEGIN");
	$group = XML_SVG_Group::getNew(false, false, SECTION_MARGIN);
	$group->id = "sectionGroup-" . str_replace(" ", "-", $title);
	//error_log("margin: (" . $group->marginx . ", " . $group->marginy . ")");
	$group->marginx = SECTION_MARGIN;
	$group->marginy = SECTION_MARGIN;
	//error_log("margin: (" . $group->marginx . ", " . $group->marginy . ")");
	$group->{'right-of'} = $right_of;
	$group->{'below-of'} = $below_of;
	$group->width = $width;
	$group->height = $height;
	$group->appendChild(mkGuideBox(0, 0, $width, $height));
	// Title bar
	$group->appendChild(mkClippedRect($title, 0, 0, $width));
	//error_log("group properties: (" . var_export($group->properties,true) . ")");
	//error_log("margin: (" . $group->marginx . ", " . $group->marginy . ")");
	//error_log("about to setTransform");
	setTransform($group);
	error_log("groupID y/height/bottom/transform = {$group->y}/{$group->height}/{$group->bottom}/{$group->transform}");
	//error_log(__METHOD__ . " END");
	return $group;
}

function setGroupCoordinates(&$group, $right_of, $below_of, $withPadding=false) {
	$group->{'right-of'} = $right_of;
	$group->{'below-of'} = $below_of;
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
function mkClippedRect($label, $x, $y, $width, $height = BOX_HEIGHT, $corner = BOX_CORNER) {
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
	$path->{'stroke-width'} = 2;
	$path->{'stroke'} = "#000000";
	$path->{"stroke-linejoin"} = "round";
	$path->{"stroke-linecap"} = "butt";
	$group->appendChild($path);

	if (!empty($label)) {
		$text = XML_SVG_Text::getNew($label);
		$text->x = BOX_PADDING_X;
		$text->y = BOX_PADDING_Y;
		setTextProperties($text, 'section-header');
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
	//$group->{'right-of'} = $right_of;
	//$group->{'below-of'} = $below_of;
	setTransform($group);
	//$group->transform = "translate($x,$y)";

	$rect = XML_SVG_Rect::getNew(0,0, $width, $height);
	$rect->fill = "none";
	$rect->{'stroke-width'} = 2;
	$rect->{'stroke'} = "#000000";
	$rect->{"stroke-linejoin"} = "round";
	$rect->{"stroke-linecap"} = "butt";
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

function mkGuideBox($x, $y, $width, $height) {
	$group =  XML_SVG_Group::getNew();
	$group->class = "guide-box";
	$group->transform = "translate($x,$y)";

	$rect = XML_SVG_Rect::getNew(0,0, $width, $height);
	$rect->fill = "none";
	$rect->{'stroke-width'} = 1;
	$rect->{'stroke'} = "#0000ff";
	$group->appendChild($rect);

	$rect = XML_SVG_Rect::getNew(-2,-2, $width+4, $height+4);
	$rect->fill = "none";
	$rect->{'stroke-width'} = 1;
	$rect->{'stroke'} = "#ff0000";
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
		$element->{'font-family'} = "Montserrat, sans-serif";
		$element->{'font-size'} = "15pt";
		$element->{'alignment-baseline'} = "before-edge";
		break;
	case 'box-label':
		$element->fill = "#ccc";
		$element->{'font-family'} = "Montserrat, sans-serif";
		$element->{'font-size'} = "13pt";
		$element->{'alignment-baseline'} = "before-edge";
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
	//error_log("right-of: " . var_export($element->{'right-of'}, true));
	//error_log("below-of: " . var_export($element->{'below-of'}, true));
	$x = 0;
	if ($element->{'right-of'}) {
		$x = $element->{'right-of'};
		//error_log("x: " . var_export($x, true));
	}
	if ($x instanceof XML_SVG_Element) {
		$x = $x->right + $element->marginx;
		//error_log($element->id . "] x: " . var_export($x, true));
	}
	$y = 0;
	if ($element->{'below-of'}) {
		$y = $element->{'below-of'};
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

