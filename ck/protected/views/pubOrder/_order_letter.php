<table width="100%" class="nomargin">
	<tr>
		<td width="60%">&nbsp;</td>
		<td width="40%"><?echo implode('<br />', $publisher->organisation->fullAddress)?></td>
	</tr>
</table>

<strong>Věc: Projekt Česká knihovna <?echo param('projectYear')?> <?/*if ($type == 'R') echo '- objednávka rezerva'*/?> </strong>

<br />

<p><?echo ($publisher->organisation->salutation != null) ? $publisher->organisation->salutation : 'Vážení nakladatelé'?>,</p>

<p>v příloze Vám zasíláme objednávku <?/*if ($type == 'R') echo '(rezerva)'*/?> titulů Vašeho nakladatelství, kterou jsme sestavili na základě výběru veřejných knihoven ČR přihlášených do projektu &quot;Česká knihovna <?echo param('projectYear')?>&quot;. Uvádíme v ní vedle pořadového čísla z nabídkového seznamu autora, název publikace, ISBN, požadovaný počet výtisků a cenu. Tato cena byla uvedena na Vaší přihlášce do výběrového řízení, a proto ji považujeme za závaznou a není možné ji zvýšit.</p>

<p>Tituly dodejte prosím <strong>do oddělení Česká knihovna Moravské zemské knihovny v Brně</strong> (<strong>Kounicova 65a</strong> - křižovatka ulic Kounicovy a Hrnčířské).</p>

<p>Na termínu dodání publikací do Moravské zemské knihovny v Brně je nutné se předem domluvit s pí Machkovou (telefon 541 646 301, e-mail: ceskaknihovna@mzk.cz).</p>

<p><strong>Upozorňujeme Vás, že finanční prostředky uvolněné na tento projekt se vztahují pouze k roku <?echo param('projectYear')?>.</br> Z tohoto důvodu Vás prosíme o dodání objednaných knih i faktur do <u><?echo DT::toLoc(param('pubFinalDate'))?></u>. Pokud se tak nestane, pozbývá přiložená objednávka platnost.</strong></p>

<p>Těšíme se na dobrou spolupráci.</p>

<p>U všech faktur, které nám zašlete, uvádějte u naší adresy heslo &quot;Česká knihovna&quot;.</p>

<p>V Brně dne <?echo param('printOrderDate')?></p>

<table width="100%" class="nomargin">
	<tr>
		<td width="60%">&nbsp;</td>
		<td width="40%">
			Vyřizuje: Z. Machková<br /><br />
			Moravská zemská knihovna v Brně<br />
			Česká knihovna<br />
			Kounicova  65a<br />
			601 87 &nbsp; Brno
		</td>
	</tr>
</table>

<br /><br />

Příloha: objednávka publikací   


