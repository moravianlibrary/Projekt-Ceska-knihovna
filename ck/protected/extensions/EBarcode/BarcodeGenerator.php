<?
/*
 * PHP-Barcode 0.4
 
 * PHP-Barcode generates
 *   - Barcode-Images using libgd2 (png, jpg, gif)
 *   - HTML-Images (using 1x1 pixel and html-table)
 *   - silly Text-Barcodes
 *
 * PHP-Barcode encodes using
 *   - a built-in EAN-13/ISBN Encoder
 *   - genbarcode (by Folke Ashberg), a command line
 *     barcode-encoder which uses GNU-Barcode
 *     genbarcode can encode EAN-13, EAN-8, UPC, ISBN, 39, 128(a,b,c),
 *     I25, 128RAW, CBR, MSI, PLS
 *     genbarcode is available at www.ashberg.de/php-barcode 
 
 * (C) 2001,2002,2003,2004,2011 by Folke Ashberg <folke@ashberg.de>
 
 * The newest version can be found at http://www.ashberg.de/php-barcode
 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.

 */

class BarcodeGenerator
{

	/* CONFIGURATION */
	
	public $code = '';
	public $encoding = 'ANY';
	public $scale = 1;
	public $mode = 'gif';
	public $height = 0;
	public $space = '';
	
	protected $bars = array();

	public $html_options = array('class'=>'barcode');

	/* ******************************************************************** */
	/*                          COLORS                                      */
	/* ******************************************************************** */
	public $bar_color=Array(0,0,0);
	public $bg_color=Array(255,255,255);
	public $text_color=Array(0,0,0);

	/* ******************************************************************** */
	/*                          FONT FILE                                   */
	/* ******************************************************************** */
	/* location the the ttf-font */
	public $font_loc='/usr/share/fonts/ttf/FreeSansBold.ttf';
	public $image_url='';

	/* ******************************************************************** */
	/*                          GENBARCODE                                  */
	/* ******************************************************************** */
	/* location of 'genbarcode'
	* leave blank if you don't have them :(
	* genbarcode is needed to render encodings other than EAN-12/EAN-13/ISBN
	*/
	//$genbarcode_loc="c:\winnt\genbarcode.exe";
	public $genbarcode_loc='/usr/local/bin/genbarcode';


	/* CONFIGURATION ENDS HERE */

	/* 
	* barcode_outimage(text, bars [, scale [, mode [, total_y [, space ]]]] )
	*
	*  Outputs an image using libgd
	*
	*    text   : the text-line (<position>:<font-size>:<character> ...)
	*    bars   : where to place the bars  (<space-width><bar-width><space-width><bar-width>...)
	*    scale  : scale factor ( 1 < scale < unlimited (scale 50 will produce
	*                                                   5400x300 pixels when
	*                                                   using EAN-13!!!))
	*    mode   : png,gif,jpg, depending on libgd ! (default='png')
	*    total_y: the total height of the image ( default: scale * 60 )
	*    space  : space
	*             default:
	*		$space[top]   = 2 * $scale;
	*		$space[bottom]= 2 * $scale;
	*		$space[left]  = 2 * $scale;
	*		$space[right] = 2 * $scale;
	*/
	public function barcode_outimage()
	{
		/* set defaults */
		$scale = $this->scale;
		if ($scale<1) $scale=1;
		
		$total_y = (int)$this->height;
		if ($total_y<1)
			$total_y=(int)$scale * 60;
		
		$space = $this->space;
		if (!$space)
			$space=array('top'=>2*$scale,'bottom'=>2*$scale,'left'=>2*$scale,'right'=>2*$scale);
		
		/* count total width */
		$xpos=0;
		$width=true;
		for ($i=0;$i<strlen($this->bars['bars']);$i++)
		{
			$val=strtolower($this->bars['bars'][$i]);
			if ($width)
			{
				$xpos+=$val*$scale;
				$width=false;
				continue;
			}
			if (preg_match("#[a-z]#", $val))
			{
				/* tall bar */
				$val=ord($val)-ord('a')+1;
			} 
			$xpos+=$val*$scale;
			$width=true;
		}

		/* allocate the image */
		$total_x=( $xpos )+$space['right']+$space['right'];
		$xpos=$space['left'];
		
		$im=imagecreate($total_x, $total_y);
		/* create two images */
		$col_bg=ImageColorAllocate($im,$this->bg_color[0],$this->bg_color[1],$this->bg_color[2]);
		$col_bar=ImageColorAllocate($im,$this->bar_color[0],$this->bar_color[1],$this->bar_color[2]);
		$col_text=ImageColorAllocate($im,$this->text_color[0],$this->text_color[1],$this->text_color[2]);
		$height=round($total_y-($scale*10));
		$height2=round($total_y-$space['bottom']);

		/* paint the bars */
		$width=true;
		for ($i=0;$i<strlen($this->bars['bars']);$i++)
		{
			$val=strtolower($this->bars['bars'][$i]);
			if ($width)
			{
				$xpos+=$val*$scale;
				$width=false;
				continue;
			}
			if (preg_match("#[a-z]#", $val))
			{
				/* tall bar */
				$val=ord($val)-ord('a')+1;
				$h=$height2;
			}
			else
				$h=$height;
			imagefilledrectangle($im, $xpos, $space['top'], $xpos+($val*$scale)-1, $h, $col_bar);
			$xpos+=$val*$scale;
			$width=true;
		}
		
		/* write out the text */
		$chars=explode(" ", $this->bars['text']);
		reset($chars);
		while (list($n, $v)=each($chars))
		{
			if (trim($v))
			{
				$inf=explode(":", $v);
				$fontsize=$scale*($inf[1]/1.8);
				$fontheight=$total_y-($fontsize/2.7)+2;
				@imagettftext($im, $fontsize, 0, $space['left']+($scale*$inf[0])+2,
				$fontheight, $col_text, $this->font_loc, $inf[2]);
			}
		}

		/* output the image */
		$mode=strtolower($this->mode);
		if ($mode=='jpg' || $mode=='jpeg')
		{
			header("Content-Type: image/jpeg; name=\"barcode.jpg\"");
			imagejpeg($im);
		}
		elseif ($mode=='gif')
			{
				header("Content-Type: image/gif; name=\"barcode.gif\"");
				imagegif($im);
			}
			elseif ($mode=='png')
				{
					header("Content-Type: image/png; name=\"barcode.png\"");
					imagepng($im);
				}
				else
				{
					$fname = '/tmp/'.md5(uniqid(rand()));
					imagegif($im, $fname);
					$f = fopen($fname, 'r');
					$img = fread($f, filesize($fname));
					fclose($f);
					unlink($fname);
					return base64_encode($img);
				}
	}

	/*
	* barcode_outtext(code, bars)
	*
	*  Returns (!) a barcode as plain-text
	*  ATTENTION: this is very silly!
	*
	*    text   : the text-line (<position>:<font-size>:<character> ...)
	*    bars   : where to place the bars  (<space-width><bar-width><space-width><bar-width>...)
	*/
	public function barcode_outtext()
	{
		$width=true;
		$xpos=$heigh2=0;
		$bar_line="";
		for ($i=0;$i<strlen($this->bars['bars']);$i++)
		{
			$val=strtolower($this->bars['bars'][$i]);
			if ($width)
			{
				$xpos+=$val;
				$width=false;
					for ($a=0;$a<$val;$a++)
						$bar_line.="-";
				continue;
			}
			if (preg_match("#[a-z]#", $val))
			{
				$val=ord($val)-ord('a')+1;
				$h=$heigh2;
				for ($a=0;$a<$val;$a++)
					$bar_line.="I";
			}
			else
				for ($a=0;$a<$val;$a++)
					$bar_line.="#";
			$xpos+=$val;
			$width=true;
		}
		return $bar_line;
	}

	/* 
	* barcode_outhtml(text, bars [, scale [, total_y [, space ]]] )
	*
	*  returns(!) HTML-Code for barcode-image using html-code (using a table and with black.png and white.png)
	*
	*    text   : the text-line (<position>:<font-size>:<character> ...)
	*    bars   : where to place the bars  (<space-width><bar-width><space-width><bar-width>...)
	*    scale  : scale factor ( 1 < scale < unlimited (scale 50 will produce
	*                                                   5400x300 pixels when
	*                                                   using EAN-13!!!))
	*    total_y: the total height of the image ( default: scale * 60 )
	*    space  : space
	*             default:
	*		$space[top]   = 2 * $scale;
	*		$space[bottom]= 2 * $scale;
	*		$space[left]  = 2 * $scale;
	*		$space[right] = 2 * $scale;
	*/
	public function barcode_outhtml()
	{
		/* set defaults */
		$scale = $this->scale;
		if ($scale<1) $scale=1;
		
		$total_y = (int)$this->height;
		if ($total_y<1)
			$total_y=(int)$scale * 60;
		
		$space = $this->space;
		if (!$space)
			$space=array('top'=>2*$scale,'bottom'=>2*$scale,'left'=>2*$scale,'right'=>2*$scale);

		/* generate html-code */
		$height=round($total_y-($scale*10));
		$height2=round($total_y)-$space['bottom'];
		$out=
			'<table border="0" cellspacing="0" cellpadding="0" bgcolor="white" class="noborder nomargin">'."\n".
			'<tr><td><img src="'.$this->image_url.'white.png" height="'.$space['top'].'" width="1" alt=" "></td></tr>'."\n".
			'<tr><td>'."\n".
			'<img src="'.$this->image_url.'white.png" height="'.$height2.'" width="'.$space['left'].'" alt="#"/>';
		
		$width=true;
		for ($i=0;$i<strlen($this->bars['bars']);$i++)
		{
			$val=strtolower($this->bars['bars'][$i]);
			if ($width)
			{
				$w=$val*$scale;
				if ($w>0)
					$out.='<img src="'.$this->image_url.'white.png" height="'.$total_y.'" width="'.$w.'" align="top" alt="" />';
				$width=false;
				continue;
			}
			if (preg_match("#[a-z]#", $val))
			{
				//hoher strich
				$val=ord($val)-ord('a')+1;
				$h=$height2;
			}
			else
				$h=$height;
			$w=$val*$scale;
			if ($w>0)
				$out.='<img src="'.$this->image_url.'black.png" height="'.$h.'" width="'.$w.'" align="top" />';
			$width=true;
		}
		$out.=
			'<img src="'.$this->image_url.'white.png" height="'.$height2.'" width=".'.$space['right'].'" />'.
			'</td></tr>'."\n".
			'<tr><td align="center">'.$this->code.'</td></tr>'."\n".
			'</table>'."\n";
		//for ($i=0;$i<strlen($this->bars['bars']);$i+=2) print $line[$i]."<B>".$line[$i+1]."</B>&nbsp;";
		return $out;
	}

	/* 
	* barcode_outtable(text, bars [, scale [, total_y [, space ]]] )
	*
	*  returns(!) HTML-Code for barcode-image using html-code (using a table and with black.png and white.png)
	*
	*    text   : the text-line (<position>:<font-size>:<character> ...)
	*    bars   : where to place the bars  (<space-width><bar-width><space-width><bar-width>...)
	*    scale  : scale factor ( 1 < scale < unlimited (scale 50 will produce
	*                                                   5400x300 pixels when
	*                                                   using EAN-13!!!))
	*    total_y: the total height of the image ( default: scale * 60 )
	*    space  : space
	*             default:
	*		$space[top]   = 2 * $scale;
	*		$space[bottom]= 2 * $scale;
	*		$space[left]  = 2 * $scale;
	*		$space[right] = 2 * $scale;
	*/
	public function barcode_outtable()
	{
		/* set defaults */
		$scale = $this->scale;
		if ($scale<1) $scale=1;
		
		$total_y = (int)$this->height;
		if ($total_y<1)
			$total_y=(int)$scale * 60;
		
		$space = $this->space;
		if (!$space)
			$space=array('top'=>2*$scale,'bottom'=>2*$scale,'left'=>2*$scale,'right'=>2*$scale);

		/* generate html-code */
		$height=round($total_y-($scale*10));
		$height2=round($total_y)-$space['bottom'];
		$out=
			'<table border="0" cellspacing="0" cellpadding="0" bgcolor="white" class="noborder nomargin">'."\n".
			'<tr>'."\n";
		
		$width=true;
		for ($i=0;$i<strlen($this->bars['bars']);$i++)
		{
			$val=strtolower($this->bars['bars'][$i]);
			if ($width)
			{
				$w=$val*$scale;
				if ($w>0)
					$out.='<td style="background: white;" height="'.$total_y.'" width="'.$w.'"></td>'."\n";
				$width=false;
				continue;
			}
			if (preg_match("#[a-z]#", $val))
			{
				//hoher strich
				$val=ord($val)-ord('a')+1;
				$h=$height2;
			}
			else
				$h=$height;
			$w=$val*$scale;
			if ($w>0)
				$out.='<td style="background: black" height="'.$total_y.'" width="'.$w.'"></td>'."\n";
			$width=true;
		}
		$out.=
			'</tr>'."\n".
			'<tr><td align="center" colspan="'.(strlen($this->bars['bars'])).'">'.$this->code.'</td></tr>'."\n".
			'</table>'."\n";
		//for ($i=0;$i<strlen($this->bars['bars']);$i+=2) print $line[$i]."<B>".$line[$i+1]."</B>&nbsp;";
		return $out;
	}
	
	/* barcode_encode_genbarcode(code, encoding)
	*   encodes $code with $encoding using genbarcode
	*
	*   return:
	*    array[encoding] : the encoding which has been used
	*    array[bars]     : the bars
	*    array[text]     : text-positioning info
	*/
	public function barcode_encode_genbarcode()
	{
		$code = $this->code;
		$encoding = $this->encoding;
		/* delete EAN-13 checksum */
		if (preg_match("#^ean$#i", $encoding) && strlen($code)==13)
			$code=substr($code,0,12);
		$encoding=preg_replace("#[|\\\\]#", "_", $encoding);
		$code=preg_replace("#[|\\\\]#", "_", $code);
		$cmd=$this->genbarcode_loc." ".escapeshellarg($code)." ".escapeshellarg(strtoupper($encoding))."";
		//print "'$cmd'<BR>\n";
		$fp=popen($cmd, "r");
		if ($fp)
		{
			$bars=fgets($fp, 1024);
			$text=fgets($fp, 1024);
			$encoding=fgets($fp, 1024);
			pclose($fp);
		}
		else
			return false;
		$ret=array(
			"encoding" => trim($encoding),
			"bars" => trim($bars),
			"text" => trim($text)
			);
		if (!$ret['encoding']) return false;
		if (!$ret['bars']) return false;
		if (!$ret['text']) return false;
		return $ret;
	}

	/* barcode_encode(code, encoding)
	*   encodes $code with $encoding using genbarcode OR built-in encoder
	*   if you don't have genbarcode only EAN-13/ISBN is possible
	*
	* You can use the following encodings (when you have genbarcode):
	*   ANY    choose best-fit (default)
	*   EAN    8 or 13 EAN-Code
	*   UPC    12-digit EAN 
	*   ISBN   isbn numbers (still EAN-13) 
	*   39     code 39 
	*   128    code 128 (a,b,c: autoselection) 
	*   128C   code 128 (compact form for digits)
	*   128B   code 128, full printable ascii 
	*   I25    interleaved 2 of 5 (only digits) 
	*   128RAW Raw code 128 (by Leonid A. Broukhis)
	*   CBR    Codabar (by Leonid A. Broukhis) 
	*   MSI    MSI (by Leonid A. Broukhis) 
	*   PLS    Plessey (by Leonid A. Broukhis)
	* 
	*   return:
	*    array[encoding] : the encoding which has been used
	*    array[bars]     : the bars
	*    array[text]     : text-positioning info
	*/
	public function barcode_encode()
	{
		if (
			((preg_match("#^ean$#i", $this->encoding) && ( strlen($this->code)==12 || strlen($this->code)==13)))	
			||
			(($this->encoding) && (preg_match("#^isbn$#i", $this->encoding)) && (( strlen($this->code)==9 || strlen($this->code)==10) || (((preg_match("#^978#", $this->code) && strlen($this->code)==12) || (strlen($this->code)==13)))))
			||
			(preg_match("#^ANY$#i", $this->encoding) && preg_match("#^[0-9]{12,13}$#", $this->code))		
			)
		{
			/* use built-in EAN-Encoder */
			$encoder = new BarcodeEncoder;
			$encoder->code = $this->code;
			$encoder->encoding = $this->encoding;
			$this->bars=$encoder->barcode_encode_ean();
		}
		elseif (file_exists($this->genbarcode_loc))
			{
				/* use genbarcode */
				$this->bars=$this->barcode_encode_genbarcode();
			}
			else
			{
				print "php-barcode needs an external programm for encodings other then EAN/ISBN<BR>\n";
				print "<ul>\n";
				print "<li>download gnu-barcode from <a href=\"http://www.gnu.org/software/barcode/\">www.gnu.org/software/barcode/</a></li>\n";
				print "<li>compile and install them</li>\n";
				print "<li>download genbarcode from <a href=\"http://www.ashberg.de/php-barcode/\">www.ashberg.de/php-barcode/</a></li>\n";
				print "<li>compile and install them</li>\n";
				print "<li>specify path to genbarcode in php-barcode.php</li>\n";
				print "</ul>\n";
				print "<br />\n";
				print "<a href=\"http://www.ashberg.de/php-barcode/\">Folke Ashberg's OpenSource PHP-Barcode</a><br />\n";
				return false;
			}
	}

	/* barcode_print(code [, encoding [, scale [, mode ]]] );
	*
	*  encodes and prints a barcode
	*
	*   return:
	*    array[encoding] : the encoding which has been used
	*    array[bars]     : the bars
	*    array[text]     : text-positioning info
	*/
	public function barcode_print()
	{
		$this->barcode_encode();
		if (!$this->bars)
			return;
		
		if (!array_key_exists('alt', $this->html_options))
			$this->html_options['alt'] = $this->code;

		$html_options = '';
		foreach ($this->html_options as $attr=>$val)
			$html_options .= " ${attr}=\"${val}\"";

		if (preg_match("#^(text|txt|plain)$#i", $this->mode))
			echo $this->barcode_outtext();
		elseif (preg_match("#^(html|htm)$#i", $this->mode))
				echo $this->barcode_outhtml();
			elseif (preg_match("#^(tab|table)$#i", $this->mode))
					echo $this->barcode_outtable();
				elseif (preg_match("#^(b64|base64)$#i", $this->mode))
						echo $this->barcode_outimage();
					elseif (preg_match("#^(line|inline)$#i", $this->mode))
							echo '<img src="data:image/gif;base64,'.$this->barcode_outimage().'" '.$html_options.' />';
						elseif (preg_match("#^(div|idiv)$#i", $this->mode))
								echo '<div class="right"><img src="data:image/gif;base64,'.$this->barcode_outimage().'" '.$html_options.' /></div><div class="clear"></div>';
							else
								$this->barcode_outimage();
	}
}


/*

 * Built-In Encoders
 * Part of PHP-Barcode 0.4
 
 * (C) 2001,2002,2003,2004,2011 by Folke Ashberg <folke@ashberg.de>
 
 * The newest version can be found at http://www.ashberg.de/php-barcode
 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.

 */
 
class BarcodeEncoder
{
	public $encoding = 'EAN-13';
	public $code = '';
	
	public function barcode_gen_ean_sum($ean)
	{
		$even=true;
		$esum=0;
		$osum=0;
		for ($i=strlen($this->code)-1;$i>=0;$i--)
		{
			if ($even)
				$esum+=$this->code[$i];
			else
				$osum+=$this->code[$i];
			$even=!$even;
		}
		return (10-((3*$esum+$osum)%10))%10;
	}

	/* barcode_encode_ean(code [, encoding])
	*   encodes $ean with EAN-13 using builtin functions
	*
	*   return:
	*    array[encoding] : the encoding which has been used (EAN-13)
	*    array[bars]     : the bars
	*    array[text]     : text-positioning info
	*/
	public function barcode_encode_ean()
	{
		$digits=array(3211,2221,2122,1411,1132,1231,1114,1312,1213,3112);
		$mirror=array("000000","001011","001101","001110","010011","011001","011100","010101","010110","011010");
		$guards=array("9a1a","1a1a1","a1a");

		$ean=trim($this->code);
		if (preg_match("#[^0-9]#i",$ean))
			return array("text"=>"Invalid EAN-Code");

		$encoding=strtoupper($this->encoding);
		if ($encoding=="ISBN")
			if (!preg_match("#^978#", $ean))
				$ean="978".$ean;
		if (preg_match("#^978#", $ean))
			$encoding="ISBN";
		if (strlen($ean)<12 || strlen($ean)>13)
			return array("text"=>"Invalid $encoding Code (must have 12/13 numbers)");

		$ean=substr($ean,0,12);
		$eansum=$this->barcode_gen_ean_sum($ean);
		$ean.=$eansum;
		$line=$guards[0];
		for ($i=1;$i<13;$i++)
		{
			$str=$digits[$ean[$i]];
			if ($i<7 && $mirror[$ean[0]][$i-1]==1)
				$line.=strrev($str);
			else
				$line.=$str;
			if ($i==6)
				$line.=$guards[1];
		}
		$line.=$guards[2];

		/* create text */
		$pos=0;
		$text="";
		for ($a=0;$a<13;$a++)
		{
			if ($a>0) $text.=" ";
			$text.="$pos:12:{$ean[$a]}";
			if ($a==0)
				$pos+=12;
			else
				if ($a==6)
					$pos+=12;
				else
					$pos+=7;
		}

		return array(
			"encoding" => $encoding,
			"bars" => $line,
			"text" => $text
			);
	}
}
?>
