## Make character sheets

OUTPUTS = *.pdf *.svg
TEMP = *.meta *.pdf-from-svg

default: pear ;

modes: fate_core_character_sheet.modes.pdf ;

clean: 
	- rm $(TEMP) $(OUTPUTS) 

.PHONY: default modes clean

pear: fate_core_character_sheet.pdf

%.pdf: %.pdf-from-svg %.meta Makefile
	pdftk $< update_info $(word 2,$^) output $@

%.pdf-from-svg: %.svg
	inkscape --without-gui --file=$< --export-pdf=$@

.PRECIOUS: %.modes.svg %.svg
%.modes.svg: %.php
	php $< --variant=modes > $@

%.svg: %.php
	php $< > $@

# generate PDF metadata and apply it to the PDF
# todo: parse this from SVG?
.INTERMEDIATE: %.meta

%.modes.meta: %.php
	php $< --meta > $@
%.meta: %.php
	php $< --meta > $@

PHP_INCLUDES = presets/standard.inc includes/sections.inc layouts/standard.inc
%.php : $(PHP_INCLUDES) Makefile 
	touch $@


