## Make character sheets

default: pear ;

dlmlib: Fate\ Core\ Character\ Sheet.svg.php

pear: fate_core_character_sheet.svg.php

%.pdf: %.svg
	inkscape --without-gui --file=$< --export-pdf=$@

fate_core_character_sheet_skill_modes.svg: fate_core_character_sheet.svg.php
	php $< > $@

dlmlib pear :
	php "$<" > output.svg

