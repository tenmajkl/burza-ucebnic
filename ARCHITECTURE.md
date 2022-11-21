# Architektura

- cela aplikace bude postavena na extremni duvere
- akorat ze vubec, je tam stale osobni kontakt, pokud si to nekdo rozmysli na miste, muze, tahle aplikace jen dava moznost prodat vse a ve vetsim casovem useku

## Vyhody

- kdyz nekdo chybi muze kupovat a prevzit jindy
- umozni najit vice uspesnych prodeju
- clovek neni vazan na jeden den

## Uzivatel

### Muze

- prodavat ucebnice
    - max 3 od kazdeho kusu na jednou?
- rezervovat si ucebnice sveho rocniku
- urcit cas a misto kdy bude ucebnice vydavat

## Ucebnice

- existuje seznam vsech ucebnic tj kdyz nekdo chce prodavat ucebnici musi si vybrat ze seznamu
- ma svoji cenu
- fotky?
    - jednak by byla potreba brutalni komprese
    - druhak lidi umi byt kokoti
    - ale muze to umoznit spravnou distribuci
    - za me urcite jo (JH)
- stav pisemne
- ucebnice pochopitelne jdou radit, filtrovat a picovinky vsak to zname

## Rezervace

- kdyz se nekomu libi ucebnic, muze si ji rezervovat
- v tu chvili se ostatnim zobrazuje jako rezervovana
- rezervovane ucebnice nelze rezervovat, ale bud
    - nastavit upozorneni
    - pridat se do fronty
    - nebo vlastne oboji
    - tj nejaka fronta rezervaci?
- kazda rezervace k sobe vaze hash
- ve chvili kdyz je qr kod naskenovat prodavajicim, bude ucebnice stahnuta z prodeje
- predani si borci posefi sami
- pokud nekdo zrusi zajem, jeho rezervace se zneplatni a nastavi se jako validni prvni ve fronte

## qr kody

- qr kod v sobe ponese url
- url bude neco jako: /qr/{hash}
- hash se vytvori s kazdou rezervaci a bude se k ni vazat
- generovani bude neco jako
    - datum + sul + id objednavky a to cele poshuflovane a zahashovane aby to nebylo prakticky mozne replikovat (proste takovata coolfido klasika)
- kdyz si prodavajici nascanuje qr kod, zjistime zda je to hash libovolne rezervace jeho ucebnice, pokud ano, odebere se, jinak to hodi nejaky error
- tj najdu platnou rezervaci s danym qr kodem a zjistim, zda je to rezervace vazajici se k produktu overujiciho, pokud jo, rezervace se zneplatni a produkt taky, pokud ne, proste to vrati error nebo neco
- v pripade ze nekdo zrusi rezervaci, nastavi se jako neplatna a kdyz nekdo pouzije qr kod neplatne rezervace
- diky tomu, ze hash je de facto nemozne ho zreplikovat, ma k nemu pristup skutecne jen ten kdo vytvoril rezervaci a nikdo jiny tak nemuze ucebnici vyzvednout, unless by to bruteforcoval, coz se mu stejne nepovede, protoze jediny kdo muze overovat je prodavajici

## auth

- zapomnel jsem na auth
- uzivatel se registruje, je tam kontrola zda jeho email je z urcite domeny (ta je v .env)
- pokud ne tak smula bozo
- pokud jo posle se mu overovaci email
- jakmile se overi bude ulozen do kouli teda do db
- prihlasovani klasika parku

## ostatni a navrhy
- focusoval bych dobre filtrovani treba podle predmetu, vydavatele, stavu atd (+ aby tam byl nejaky husty UX pri pridani inzeratu, co te provede tim procesem, ale tak to je detail) (JH)
- po odebrani inzeratu bych dal dotaznik pripadne i obema stranam, aby se daly sbirat udaje o tom, jak je to uzitecne a co pridat (JH)
