<?
include('BarcodeGenerator.php');

class EBarcode extends CWidget
{
	private $print = true;
	
	public $code = '';
	public $encoding = 'ANY';
	public $scale = 1;
	public $mode = 'gif';
	public $height = 0;
	public $space = '';

	public $htmlOptions = array('class'=>'barcode');
	
	public $fontLocation = '';
	public $assetUrl = '';
	public $genBarCodeLocation = '/usr/local/bin/genbarcode';

	public function init()
    {
		if (!function_exists("imagecreate"))
		{
			echo "You don't have the gd2 extension enabled<BR>\n";
			echo "<BR>\n";
			echo "<BR>\n";
			echo "Short HOWTO<BR>\n";
			echo "<BR>\n";
			echo "Debian: # apt-get install php4-gd2<BR>\n";
			echo "<BR>\n";
			echo "SuSE: ask YaST<BR>\n";
			echo "<BR>\n";
			echo "OpenBSD: # pkg_add /path/php4-gd-4.X.X.tgz (read output, you have to enable it)<BR>\n";
			echo "<BR>\n";
			echo "Windows: Download the PHP zip package from <A href=\"http://www.php.net/downloads.php\">php.net</A>, NOT the windows-installer, unzip the php_gd2.dll to C:\PHP (this is the default install dir) and uncomment 'extension=php_gd2.dll' in C:\WINNT\php.ini (or where ever your os is installed)<BR>\n";
			echo "<BR>\n";
			echo "<BR>\n";
			echo "The author of php-barcode will give not support on this topic!<BR>\n";
			echo "<BR>\n";
			echo "<BR>\n";
			echo "<A HREF=\"http://www.ashberg.de/php-barcode/\">Folke Ashberg's OpenSource PHP-Barcode</A><BR>\n";
			$this->print = false;
		}

		Yii::app()->getAssetManager()->publish(dirname(__FILE__).DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'FreeSansBold.ttf');
		Yii::app()->getAssetManager()->publish(dirname(__FILE__).DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'white.png');
		Yii::app()->getAssetManager()->publish(dirname(__FILE__).DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'black.png');
		
        if($this->fontLocation == '')
        {
			$this->fontLocation = Yii::app()->getAssetManager()->getPublishedPath(dirname(__FILE__).DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'FreeSansBold.ttf');
		}
        if($this->assetUrl == '')
        {
			$this->assetUrl = Yii::app()->getAssetManager()->getPublishedUrl(dirname(__FILE__).DIRECTORY_SEPARATOR.'assets').DIRECTORY_SEPARATOR;
        }
        parent::init();
    }
    
	public function run()
	{
		if ($this->print)
		{
			$bcg = new BarcodeGenerator;
			$bcg->code = $this->code;
			$bcg->encoding = $this->encoding;
			$bcg->scale = $this->scale;
			$bcg->mode = $this->mode;
			$bcg->height = $this->height;
			$bcg->space = $this->space;
			$bcg->html_options = $this->htmlOptions;
			$bcg->font_loc = $this->fontLocation;
			$bcg->image_url = $this->assetUrl;
			$bcg->genbarcode_loc = $this->genBarCodeLocation;
			$bcg->barcode_print();
		}
	}
}
?>
