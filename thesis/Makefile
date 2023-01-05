all: pdf
clean:
	rubber --pdf --clean text.tex
	rubber --ps --clean text.tex
	rubber --clean text.tex
	rm -f .plot-stamp

pdf: .plot-stamp
	rubber --pdf text.tex
ps: .plot-stamp
	rubber --ps text.tex
dvi: .plot-stamp
	rubber text.tex

%.pdf: %.dot
	dot -Tpdf < $(<) > $(@)

.plot-stamp: # plot $(glob data-*)
	#gnuplot plot
	touch $(@)

.PHONY: pdf ps dvi
