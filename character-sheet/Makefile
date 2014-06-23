## Make character sheets

default: pear ;

dlmlib: Fate\ Core\ Character\ Sheet.svg.php

pear: fate_core_character_sheet.svg.php

%.pdf: %.pdf-from-svg %.meta
	pdftk $< update_info $(word 2,$^) output $@

%.pdf-from-svg: %.svg
	inkscape --without-gui --file=$< --export-pdf=$@

.PRECIOUS: %.svg
%.svg: %.php
	php $< > $@

#fate_core_character_sheet_skill_modes.svg: fate_core_character_sheet.svg.php
%.modes.svg: %.php
	php $< --variant=modes > $@

dlmlib pear :
	php "$<" > output.svg

# generate PDF metadata and apply it to the PDF
# todo: parse this from SVG?
%.meta: %.php
	php $< --meta > $@

PHP_INCLUDES = presets/standard.inc
fate_core_character_sheet.php : $(PHP_INCLUDES)


