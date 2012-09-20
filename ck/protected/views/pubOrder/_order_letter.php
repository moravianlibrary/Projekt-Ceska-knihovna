<table width="100%" class="nomargin">
	<tr>
		<td width="60%">&nbsp;</td>
		<td width="40%"><?echo implode('<br />', $publisher->organisation->fullAddress)?></td>
	</tr>
</table>

<strong>Věc: Projekt Česká knihovna <?echo param('projectYear')?></strong>

<br />

<p><?echo ($publisher->organisation->salutation != null) ? $publisher->organisation->salutation : 'Vážení'?>,</p>

<p>v příloze Vám zasíláme objednávku <?if ($type == 'R') echo '(rezerva)'?> titulů Vašeho nakladatelství, kterou jsme sestavili na základě výběru veřejných knihoven ČR přihlášených do projektu &quot;Česká knihovna <?echo param('projectYear')?>&quot;. Uvádíme v ní vedle pořadového čísla z nabídkového seznamu autora, název publikace, požadovaný počet výtisků a cenu. Tato cena byla uvedena na Vaší přihlášce do výběrového řízení, a proto ji považujeme za závaznou a není možné ji zvýšit.</p>

<p>Tituly již vydané dodejte prosím co nejdříve <strong>do Technického ústředí knihoven v Brně</strong> (<strong>Pozor změna adresy:</strong> Moravská zemská knihovna Brno, TÚK, <strong>Kounicova 65a</strong> - křižovatka ulic Kounicovy a Hrnčířské).</p>

<p><u>Tuto skutečnost oznamte předem D. Perstické</u> (telefon 541 646 301, fax: 541 646 300, e-mail: tuk@mzk.cz). Publikace, které dosud nevyšly, nám zašlete ihned po jejich vydání.</p>

<p><strong>Upozorňujeme Vás, že finanční prostředky uvolněné na tento projekt se vztahují pouze k roku <?echo param('projectYear')?>. Z tohoto důvodu Vás prosíme o dodání objednaných knih i faktur do <u><?echo DT::toLoc(param('pubFinalDate'))?></u>. Pokud se tak nestane, pozbývá přiložená objednávka platnost.</strong></p>

<p>Těšíme se na dobrou spolupráci</p>

<p>P.S.: U všech faktur, které nám zašlete, uvádějte u naší adresy heslo &quot;Česká knihovna&quot;.</p>

<p>V Brně dne <?echo DT::locToday()?>.</p>

<table width="100%" class="nomargin">
	<tr>
		<td width="60%">&nbsp;</td>
		<td width="40%">
			Vyřizuje: D. Perstická<br /><br />
			Moravská zemská knihovna v Brně<br />
			Technické ústředí knihoven<br />
			Kounicova  65a<br />
			601 87 &nbsp; Brno
		</td>
	</tr>
</table>

<br /><br />

Příloha: objednávka publikací


