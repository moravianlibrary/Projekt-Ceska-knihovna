<?
class DropDownItem
{
	private static $_items=array(
		'User.register_as'=>array('publisher'=>'Nakladatel', 'library'=>'Knihovna / Kontaktní místo', ),
		'Organisation.region'=>array('A'=>'Hlavní město Praha', 'C'=>'Jihočeský kraj', 'B'=>'Jihomoravský kraj', 'K'=>'Karlovarský kraj', 'J'=>'Kraj Vysočina', 'H'=>'Královéhradecký kraj', 'L'=>'Liberecký kraj', 'T'=>'Moravskoslezský kraj', 'M'=>'Olomoucký kraj', 'E'=>'Pardubický kraj', 'P'=>'Plzeňský kraj', 'S'=>'Středočeský kraj', 'U'=>'Ústecký kraj', 'Z'=>'Zlínský kraj', ),
		'Library.type'=>array(''=>'jiný typ (vypište)', 'vysokoškolská'=>'vysokoškolská', 'krajská'=>'krajská', 'městská'=>'městská', 'místní/obecní'=>'místní/obecní', ),
		'Book.status'=>array(''=>'nekontrolováno', '0'=>'splňuje podmínky', '1'=>'nesplňuje podmínky - vročení', '2'=>'nesplňuje podmínky - překlad', '3'=>'nesplňuje podmínky - CD/DVD', '4'=>'nesplňuje podmínky - jiný důvod (detaily v komentáři)', ),
		/*
		'Book.format'=>array('A0'=>'A0', 'A1'=>'A1', 'A2'=>'A2', 'A3'=>'A3', 'A4'=>'A4', 'A5'=>'A5', 'A6'=>'A6', 'A7'=>'A7', 'A8'=>'A8', 'A9'=>'A9', 'A10'=>'A10', 'B0'=>'B0', 'B1'=>'B1', 'B2'=>'B2', 'B3'=>'B3', 'B4'=>'B4', 'B5'=>'B5', 'B6'=>'B6', 'B7'=>'B7', 'B8'=>'B8', 'B9'=>'B9', 'B10'=>'B10', 'C0'=>'C0', 'C1'=>'C1', 'C2'=>'C2', 'C3'=>'C3', 'C4'=>'C4', 'C5'=>'C5', 'C6'=>'C6', 'C76'=>'C76', 'C7'=>'C7', 'C8'=>'C8', 'C9'=>'C9', 'C10'=>'C10', ),
		'Book.binding'=>array('V1'=>'V1', 'V2'=>'V2', 'V3'=>'V3', 'V4'=>'V4', 'V5'=>'V5', 'V6'=>'V6', 'V7'=>'V7', 'V8'=>'V8', 'V9'=>'V9', ),
		*/
		'Book.binding'=>array(''=>'jiná vazba (vypište)', 'pevná'=>'pevná', 'brožovaná'=>'brožovaná', ),
		'Voting.type'=>array('R'=>'bodování', 'P'=>'hlasování', ),
		'LibOrder.type'=>array('B'=>'základní objednávka', 'R'=>'rezerva', ),
		'PubOrder.type'=>array('B'=>'základní objednávka', 'R'=>'rezerva', ),
		'Stock.type'=>array('B'=>'základní objednávka', 'R'=>'rezerva', ),
		'YesNo'=>array('1'=>'ano', '0'=>'ne', ),
	);

    public static function items($type)
    {
       return self::$_items[$type];
    }

    public static function item($type,$code)
    {
        return isset(self::$_items[$type][$code]) ? self::$_items[$type][$code] : false;
    }
}