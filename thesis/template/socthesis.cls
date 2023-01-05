\NeedsTeXFormat{LaTeX2e}
\ProvidesClass{template/socthesis}[2017/01/15 Document class for czech SOC theses]
\LoadClass[12pt, a4paper, oneside]{book}

\RequirePackage[utf8]{inputenc}
\RequirePackage[czech]{babel}
\RequirePackage{graphicx}
\RequirePackage{tabularx}
\RequirePackage{array}
\RequirePackage[bookmarksopen,colorlinks,plainpages=false,linkcolor=black,urlcolor=blue,citecolor=black,filecolor=black,menucolor=black,unicode=true,breaklinks]{hyperref}
\RequirePackage{csquotes}
\RequirePackage[backend=bibtex,bibstyle=numeric,sorting=none,date=long,dateabbrev=false,texencoding=utf8,bibencoding=utf8,style=iso-numeric]{biblatex}
\RequirePackage{caption}


%%%%%% PŘÍKAZY
\newcommand{\B}{\textbf} % tučné písmo
\newcommand{\It}{\textit} % kurzíva

%%%%%% ROZMĚRY

% posun textu kvůli kroužkové vazbě
%\addtolength{\textwidth}{mm}
\addtolength{\hoffset}{4mm}

\setlength{\intextsep}{5mm} % nastavení mezery okolo obrázků
\marginparsep=15pt
\linespread{1.3}
\unitlength=1mm % nastavení volby jednotek

%%%%%% ROVNICE
\DeclareCaptionType{listedequation}[Rovnice][Seznam rovnic]
%\captionsetup[listedequation]{labelformat = parens}

%% hyperref \autoref nazvy
\def\sectionautorefname{Kapitola}
\def\figureautorefname{Obrázek}
\def\tableautorefname{Tabulka}


%%%%%% PROMĚNNÉ
\def\titlecz#1{\def\@titlecz{#1}}
\def\titleen#1{\def\@titleen{#1}}
\def\author#1{\def\@author{#1}}
\def\school#1{\def\@school{#1}}
\def\region#1{\def\@region{#1}}
\def\mentor#1{\def\@mentor{#1}}
\def\mentorstatement#1{\def\@mentorstatement{#1}}
\def\placefooter#1{\def\@placefooter{#1}}

\RequirePackage{xstring}
\newcommand{\field}[1]{%
\IfStrEqCase{#1}{
    {1}{\def\@field{1. Matematika a~statistika}}
    {2}{\def\@field{2. Fyzika}}
    {3}{\def\@field{3. Chemie}}
    {4}{\def\@field{4. Biologie}}
    {5}{\def\@field{5. Geologie, geografie}}
    {6}{\def\@field{6. Zdravotnictví}}
    {7}{\def\@field{7. Zemědělství, potravinářství, lesní a~vodní hospodářství}}
    {8}{\def\@field{8. Ochrana a~tvorba životního prostředí}}
    {9}{\def\@field{9. Strojírenství, hutnictví, doprava a~průmyslový design}}
    {10}{\def\@field{10. Elektrotechnika, elektronika a~telekomunikace}}
    {11}{\def\@field{11. Stavebnictví, architektura a~design interiérů}}
    {12}{\def\@field{12. Tvorba učebních pomůcek, didaktická technologie}}
    {13}{\def\@field{13. Ekonomika a~řízení}}
    {14}{\def\@field{14. Pedagogika, psychologie, sociologie a~problematika volného času}}
    {15}{\def\@field{15. Teorie kultury, umění a~umělecké tvorby}}
    {16}{\def\@field{16. Historie}}
    {17}{\def\@field{17. Filozofie, politologie a~ostatní humanitní a~společenskovědní obory}}
    {18}{\def\@field{18. Informatika}}
  }
  [Neznámé číslo oboru, upravte \\field{} příkaz!]
}

\titleen{}
\region{Jihomoravský}
\placefooter{Brno \the\year}
\mentorstatement{\@mentor}

%%% Titulní strany
\renewcommand{\maketitle}{%
	\pagestyle{empty}
	\voffset = -20mm % posun začátku textu výš
    \enlargethispage{40mm} % zvětší oblast tisku pro tuto stránku
	\begin{center}
	    {\large \B{STŘEDOŠKOLSKÁ ODBORNÁ ČINNOST}} \\
	    \vspace{5mm}
		\normalsize \B{Obor: \@field} \\

		\vspace{40mm}

		{\LARGE \B{\@titlecz}} \\

		\vfill

        	{\Large \B{\@author}} \\

		\vspace{20mm}

		{\large \B{\@placefooter}}
	\end{center}
	\newpage

	\pagestyle{empty}
    \enlargethispage{40mm} % zvětší oblast tisku pro tuto stránku
	\begin{center}
	    {\large \B{STŘEDOŠKOLSKÁ ODBORNÁ ČINNOST}} \\

		\vspace{40mm}

		{\Large \B{\expandafter\MakeUppercase\expandafter{\@titlecz}}} \\
		\vspace{20mm}
		{\large \B{\expandafter\MakeUppercase\expandafter{\@titleen}}} \\

		\vfill

        \begin{table}[h]
        \setlength{\tabcolsep}{12pt}
        \renewcommand{\arraystretch}{1.5}
        \linespread{1.0}
		\begin{tabularx}{\textwidth}{r >{\raggedright}p{90mm} c}
		\large \B{AUTOR} & \large \B{\@author} & \\
		\large \B{ŠKOLA} & \large \B{\@school} & \\
		\large \B{KRAJ} & \large \B{\@region} & \\
		\large \B{ŠKOLITEL} & \large \B{\@mentor} & \\
		\large \B{OBOR} & \large \B{\@field} & \\
		\end{tabularx}
		\setlength{\tabcolsep}{6pt}
        \renewcommand{\arraystretch}{1.0}
		\end{table}

		\vspace{10mm}

		{\large \B{\@placefooter}}
	\end{center}
	\newpage
	\voffset = 0mm
	\pagestyle{plain}
}

%%% Prohlášení
\newcommand{\makecopyrightstatement}[1]{
	\pagestyle{empty}

	~
	\vfill

	\section*{Prohlášení}
	Prohlašuji, že svou práci na téma \It{\@titlecz} jsem vypracoval/a samostatně pod vedením {\@mentorstatement} a~s~použitím odborné literatury a~dalších informačních zdrojů, které jsou všechny citovány v~práci a~uvedeny v~seznamu literatury na~konci práce.

	Dále prohlašuji, že tištěná i~elektronická verze práce SOČ jsou shodné a~nemám závažný důvod proti zpřístupňování této práce v~souladu se~zákonem č.~121/2000~Sb., o~právu autorském, o~právech souvisejících s~právem autorským a~změně některých zákonů (autorský zákon) v~platném změní.

	\vspace{10mm}

	\noindent \parbox{\textwidth}{
		\noindent {#1} dne: \rule{4cm}{1pt}
		\hfill\parbox{5cm}{
    		\centering
    		\vspace{9mm}
    		\rule{5cm}{1pt}\\
        	\@author
		}
	}

	\newpage
	\pagestyle{plain}
}

\newcommand{\makethanks}[1]{
	\pagestyle{empty}
	~
	\vfill
	\section*{Poděkování}
	{#1}

	\vspace{\baselineskip}
	\noindent Tato práce byla provedena za finanční podpory Jihomoravského kraje.
	\begin{figure}[h]
	\begin{subfigure}{.5\textwidth}
  		\includegraphics[width=.8\linewidth]{template/jmk.png}
	\end{subfigure}%
	\begin{subfigure}{.5\textwidth}
  		\includegraphics[width=.8\linewidth]{template/jcmm.png}
	\end{subfigure}
	\end{figure}

	\newpage
	\pagestyle{plain}
}




