#!/usr/bin/perl -w

use MIME::Lite::TT;
use utf8;
use Encode;

my $template = <<TEMPLATE;
Vážené kolegyně a kolegové,

Moravská zemská knihovna v Brně z pověření Ministerstva kultury ČR vyhlašuje projekt Česká knihovna 2018 na podporu nákupu nekomerčních titulů uměleckých děl české literatury, české ilustrované beletrie pro děti a mládež, vědy o literatuře pro profesionální veřejné knihovny a knihovny vysokých škol evidovaných dle zákona č. 257/2001 Sb., o knihovnách a podmínkách provozování veřejných knihovnických a informačních služeb (knihovní zákon).
Bližší informace najdete na webových stránkách: http://www.mzk.cz/pro-knihovny/ceska-knihovna .

Pokud splňujete podmínky přijetí do projektu, použijte přihlašovací údaje, které Vám zasíláme:

e-mailová adresa: [% user %]
heslo: [% password %]

Vygenerovanou elektronickou verzi vyplněné Žádosti a Objednávky, opatřené podpisem a razítkem, zašlete poštou, prosím, nejpozději do 24. května 2018.

Upozornění: věnujte, prosím, pozornost příloze v mailu!


Těšíme se na spolupráci s Vámi.
Se srdečným pozdravem
Zdeňka Machková

Moravská zemská knihovna
Česká knihovna
Kounicova 65a
601 87 Brno
tel.č.: 541 646 301
e-mail: ceskaknihovna\@mzk.cz
TEMPLATE

open INPUT, "<kontakty.txt";
while (my $line = <INPUT>) {
   chomp $line;
   my ($addr, $user, $password) = split ',', $line;
   my %params = (user => $user, password => $password);
   print "$user $password\n";
   my %options = (EVAL_PERL=>1);
   my $msg = MIME::Lite::TT->new(
      From => 'ceskaknihovna@mzk.cz',
      To => $addr,
      Subject => encode("MIME-Header", "Projekt Česká knihovna na rok 2018 - vyhlášení podmínek pro knihovny"), #'=?UTF-8?Projekt Česká knihovna na rok 2018 - vyhlášení podmínek pro knihovny',
      Character => 'utf-8',
      Template => \$template,
      TmplParams => \%params,
      TmplOptions => \%options,
   );
   $msg->attr('Content-type', 'text/plain; charset="utf-8"');
   $msg->attach(
        Type        => 'AUTO',
        Path        => './seznam.doc',
        Readnow     => 1,
        Filename    => 'seznam.doc',
   );
   $msg->send('smtp', 'smtp.mzk.cz', Timeout => 60 );
}
