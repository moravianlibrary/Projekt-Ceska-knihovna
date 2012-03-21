<div class="flash-notice" id="liborder_total">
<strong>Celková cena základní objednávky: </strong><?echo currf($basicPrice);?> (max. <?echo currf(param('libBasicLimit'));?>)
<br />
<strong>Celkový počet svazků rezervy: </strong><?echo app()->format->formatPcs($reserveCount)?> (max. <?echo app()->format->formatPcs(param('libReserveLimit'));?>)
</div>
