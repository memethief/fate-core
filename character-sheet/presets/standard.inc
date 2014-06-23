<?php 
/**
 * @author David Leaman <fated@memethief.com>
 * 
 * Presets for page dimensions, including things like box padding.
 */

// Some general properties
define('PAGE_WIDTH',      960   );
define('PAGE_HEIGHT',     720   );
define('PAGE_MARGINX',     10   );
define('PAGE_MARGINY',     30   );

define('INNER_WIDTH',              PAGE_WIDTH  - 2*PAGE_MARGINX              );
define('INNER_HEIGHT',             PAGE_HEIGHT - 2*PAGE_MARGINY              );

define('BOX_HEIGHT',       21   );
define('BOX_CORNER',        3.5 );
define('BOX_PADDING_X',    10   );
define('BOX_PADDING_Y',     0   );
define('BOX_MARGIN',        9   );
define('BOX_MARGINX',       4.5 ); // one-sided
define('BOX_MARGINY',       4.5 ); // one-sided
define('BOX_SKIPY',                BOX_HEIGHT+2*BOX_MARGINY                  );
define('SECTION_MARGIN',   18   );
define('SECTION_MARGINX',   9   ); // one-sided
define('SECTION_MARGINY',   9   ); // one-sided

define('BOX_HEADER_HEIGHT',        BOX_HEIGHT                                );
define('BOX_HEADER_SKIPY',         BOX_HEADER_HEIGHT + 2*BOX_MARGINY         );

define('BOX_WIDTH_HALF',           INNER_WIDTH/2 - SECTION_MARGIN            );
define('BOX_WIDTH_THIRD',          INNER_WIDTH/3 - SECTION_MARGIN            );
define('BOX_WIDTH_QUARTER',        INNER_WIDTH/4 - SECTION_MARGIN            );
define('BOX_WIDTH_TWOTHIRDS',      2*BOX_WIDTH_THIRD    +   SECTION_MARGIN   );
define('BOX_WIDTH_THREEQUARTERS',  3*BOX_WIDTH_QUARTER  + 2*SECTION_MARGIN   );

// Numbers of stuff

define('NUM_SKILL_RANKS', 5 );
define('NUM_ASPECTS',     5 );

