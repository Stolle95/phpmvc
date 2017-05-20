Redovisning
====================================
Kmom05:
------------------------------------
Var hittade du inspiration till ditt val av modul och var hittade du kodbasen som du använde?
Jag tyckte att det fanns ingen poäng med user modulen vi gjorde i kmom04. Jag tyckte det saknade massa funktioner som skulle göra en användbar. Jag valde alltså att fortsätta bygga ut user modulen för att innehålla login behörighet för att komma åt de övriga funktionerna. Jag tyckte det även var svårt när alla användare visades och skapade en sökfunktion som är bokstavskännsbar, så skriver du 'f' kommer alla namn som har 'f' i sitt namn. 

Hur gick det att utveckla modulen och integrera i ditt ramverk?
Det gick smidigt när man hade en grund att jobba utifrån. Jag tyckte vet var mycket roligt och när det skulle upp på github och packagist ville man lägga ner det lilla extra för att göra det snyggt.

Hur gick det att publicera paketet på Packagist?
Inte så mycket problem, först saknades en composer.json fil och därefter fick jag byta namn på modulen för det va upptaget. Mitt första var fbs/fUser och nu blev det filipbs/f-user. Jag än nöjd. Jag tycker det gick mycket smidigare än jag trodee att publisera på packagist. Det var bara att skicka in url till repot på github så var det klart. Mycket smidigt.

Hur gick det att skriva dokumentationen och testa att modulen fungerade tillsammans med Anax MVC?
Jag skrev ner de krisiska delarna som behövdes för att modulen ska fungera. Sedan la jag in dependencies och för de modulerna ser jag inte är mitt ansvar att skriva manual utan det finns på den packagist sidan hur man ska installera modulen och använda den. 

Gjorde du extrauppgiften? Beskriv i så fall hur du tänkte och vilket resultat du fick.
Nej jag gjorde inte det, känner att jag inte har tid för det.
Kmom04:
------------------------------------
Vad tycker du om formulärhantering som visas i kursmomentet?
Jag tycker det är en smidig lösning nu när man kommit in i det och ser hur effekfullt det är med en autogenererings formulär. 
Jag vet dock inte hur stylingen och om man vill göra ett speciellt formulär hur anpassat systemet är. Så djupt har jag inte gått, 
det var testat och sett är en helt okej lösning.

Vad tycker du om databashanteringen som visas, föredrar du kanske traditionell SQL?
Jag är en mysql människa och därför valde jag att använda mysql i detta kursmoment. Jag tycker det är en smidig lösning men nu på senaste dar har det kommit 
ut lite effekfullare databaser så som mondodb, cassandra osv.. Hade hellre sett att vi använt något av dessa. Mysql används kanske till wordpress sidor men så mycket 
mer tror jag inte man använder det i produktion mot större applikationer.

Gjorde du några vägval, eller extra saker, när du utvecklade basklassen för modeller?
Nja, jag höll mig ganska stright forward och simpelt. Jag kände att det kunde bli mer strul än vad jag normalt hade. Så jag valde att ha samma struktur som user och kommentars delen.

Beskriv vilka vägval du gjorde och hur du valde att implementera kommentarer i databasen.
Jag använde mycket från user uppgiften, jag har en setupComment som raderar om databasen finns och sedan skapar en ny med namn, comment och created. 
Efter det valde jag att använda cform för att generera ett formulär där jag postar kommentaren samt namnet på den ska postar. Created värdet kommer automatiskt när 
kommentaren postas. Jag använde sedan save funktionen i CDatabaseModel för att instera datan till databasen. 
För att hålla det simpelt valde jag att visa kommentarena på samma sida som man postar en kommentar. Där använder jag en template fil som heter commentsdb och skickar med alla kommentarer som hämtas via en getComments funktion i CDatabaseModel och sedan loopar ut alla kommentarer. 

Gjorde du extrauppgiften? Beskriv i så fall hur du tänkte och vilket resultat du fick.
Nja, jag hittade ingen extra uppgift i någon av dessa 2 uppgifter. 


Kmom03:
------------------------------------

Vad tycker du om CSS-ramverk i allmänhet och vilka tidigare erfarenheter har du av dem?
Jag tycker "Css"ramverk är något man ska använda, det är mycket simpelt att använda och jag använder bootstrap dagligen när jag utvecklar. Jag tycker det är
onödigt att skriva kod själv samt behöva anpassa den för responsivited. Ramverket sköter allt detta, bara man väljer rätt.
Vad tycker du om LESS, lessphp och Semantic.gs?
Helt okej, inget jag kommer använda. Less är smart och smidigt, resten... nja... Jag håller mig till de saker jag kan och vet att jag klarar av.
Vad tycker du om gridbaserad layout, vertikalt och horisontellt?
gridbaserade hemsidor är grunden, jag tycker det är skit viktigt att använda det så sidan får en struktur. Jag tyckte min grid på themat gick så där.. jag hade kunna göra bättre för att den skulla passa in helt. Vet inte riktigt vad som gick fel.
Har du några kommentarer om Font Awesome, Bootstrap, Normalize?
Jag tycker Font Awesome och Bootstrap är bland det bästa verktygen jag vet, Normalize kan jag inte riktigt uttrycka mig om då jag inte använt den i en produktmiljö mot en kund. Men de övriga 2, good!.
Beskriv ditt tema, hur tänkte du när du gjorde det, gjorde du några utsvävningar?
Jag gjorde inget fancy, jag gjorde en sida med allting på, Font Awesome, grid, lite text som typografi.
Antog du utmaningen som extra uppgift?
Jag vill faktist inte lägga upp den på github, jag skäms så mycket så jag vill faktist inte visa upp den om ett företag headhuntar mig via github. Laddar gärna upp på ett bitbucket repo privat. Hojta till om ni vill se en printscreen på det.

Kmom02:
------------------------------------

Hur känns det att jobba med Composer?
Jag tycker det är ett smidigt sätt att kunna ladda ner och använda paket. Jag tyckte dock det var lite krångligt i början och löste uppgiften på något sätt som jag tror man helst
inte ska göra, ändra i root filerna. Alltså i vendor mappen. Kanske man skulle fört över filerna till webroot's src eller så gjorde jag rätt. Inte helt hundra. Nå väl, det fungerar iallafall.
Vad tror du om de paket som finns i Packagist, är det något du kan tänka dig att använda och hittade du något spännande att inkludera i ditt ramverk?
Jag valde att inte leta så mycket i Packagist, jag gjorde valet att direkt börja med kommentarsystemet och försöka integrera det med sidan.
Hur var begreppen att förstå med klasser som kontroller som tjänster som dispatchas, fick du ihop allt?
I början var det svårt att förstå allting t.ex när man skapar en länk edit så lägs Action på automatisk så i kontrollern blir det editAction. Det hade jag lite strul med. Eftersom tiden gick fick jag en klarare överblick över hur allting fungerade och integrerade med varandra.
Hittade du svagheter i koden som följde med phpmvc/comment? Kunde du förbättra något?
phpmvc/comment täcker en grundläggande kommentarsystem, ingenting som i praktiken hade fungerat i större webbplatser så den blandannat går efter sessioner som jag förstått och med tiden försvinner från sidan. Helt klar utvecklingsbar men också användningsbar.



Kmom01:
------------------------------------

Nu när kursmoment 01 är avklarat känns det som en bra start i phpmvc kursen, under färdens gång har jag stött på några frågetecken, det största problemet jag hade var att navigera mig runt bland alla filer och mappar på ramverke samt att förstå vad som gör vad, till exempel finns det flera src-mappar som blev lite förvirrande. Guiden tyckte jag var ganska oklar VART man skulle skriva in koden, man kan se detta som pros eller con, att mos inte skrivit var man skall skriva in koden kräver en del tänkande medans det kan ta en sådan jäkla lång tid. Sedan uppfattade jag inte direkt i guiden att man endast skulle använda sig utav en frontcontroller dvs inte flera .php filer. Nu när momentet är klart förstår man att det är väldigt bra att använda sig utav MVC på större webbplatser för att allt skall få en strukur samt tror jag att det är en säkerhetsrisk att inte köra med ett ramverk även att allting blir simplare som att använda sig utav vyer samt templare filer för alla objekt så som header, footer, meny osv..

Som utvecklingsmiljö använder jag en windows 10 burk med text editorn Sublime, för att clona via git använder gitbash. Jag har arbetat med ramverk tidigare i oophp kursen som också kallades Anax. De avancerade begreppen var inte familiära hos mig i början men nu i efterhand kan man se att allting har ett samband.
