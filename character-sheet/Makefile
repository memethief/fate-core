## Make character sheets

default: pear ;

dlmlib: Fate\ Core\ Character\ Sheet.svg.php

pear: fate_core_character_sheet.svg.php

%.pdf: %.pdf-from-svg %.meta
	pdftk $< update_info $(word 2,$^) output $@

%.pdf-from-svg: %.svg
	inkscape --without-gui --file=$< --export-pdf=$@

fate_core_character_sheet_skill_modes.svg: fate_core_character_sheet.svg.php
	php $< > $@

dlmlib pear :
	php "$<" > output.svg

# generate PDF metadata and apply it to the PDF
# todo: parse this from SVG?
%.meta: %.svg.php
	php $< --meta > $@


