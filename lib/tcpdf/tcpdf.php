<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/

/**
 * TCPDF Class.
 * @package com.tecnick.tcpdf
 */
require_once(dirname(__FILE__).'/config/tcpdf_config.php');
if(!class_exists('TCPDF', false)){
	define('PDF_PRODUCER','TCPDF 1.53.0.TC034 (http://tcpdf.sourceforge.net)');
	class TCPDF{
		protected $page;
		protected $n;
		protected $offsets;
		protected $buffer;
		protected $pages;
		protected $state;
		protected $compress;
		protected $DefOrientation;
		protected $CurOrientation;
		protected $OrientationChanges;
		protected $k;
		protected $fwPt;
		protected $fhPt;
		protected $fw;
		protected $fh;
		protected $wPt;
		protected $hPt;
		protected $w;
		protected $h;
		protected $lMargin;
		protected $tMargin;
		protected $rMargin;
		protected $bMargin;
		protected $cMargin;
		protected $x;
		protected $y;
		protected $lasth;
		protected $LineWidth;
		protected $CoreFonts;
		protected $fonts;
		protected $FontFiles;
		protected $diffs;
		protected $images;
		protected $PageLinks;
		protected $links;
		protected $FontFamily;
		protected $FontStyle;
		protected $underline;
		protected $CurrentFont;
		protected $FontSizePt;
		protected $FontSize;
		protected $DrawColor;
		protected $FillColor;
		protected $TextColor;
		protected $ColorFlag;
		protected $ws;
		protected $AutoPageBreak;
		protected $PageBreakTrigger;
		protected $InFooter;
		protected $ZoomMode;
		protected $LayoutMode;
		protected $title;
		protected $subject;
		protected $author;
		protected $keywords;
		protected $creator;
		protected $AliasNbPages;
		protected $img_rb_x;
		protected $img_rb_y;
		protected $imgscale = 1;
		protected $isunicode = false;
		protected $PDFVersion = "1.3";
		private $header_margin;
		private $footer_margin;
		private $original_lMargin;
		private $original_rMargin;
		private $header_font;
		private $footer_font;
		private $l;
		private $barcode = false;
		private $print_header = true;
		private $print_footer = true;
		private $header_width = 0;
		private $header_logo = "";
		private $header_logo_width = 30;
		private $header_title = "";
		private $header_string = "";
		private $default_table_columns = 4;
		private $HREF;
		private $fontList;
		private $issetfont;
		private $issetcolor;
		private $listordered = false;
		private $listcount = 0;
		private $tableborder = 0;
		private $tdbegin = false;
		private $tdwidth = 0;
		private $tdheight = 0;
		private $tdalign = "L";
		private $tdbgcolor = false;
		private $tempfontsize = 10;
		private $b;
		private $u;
		private $i;
		private $lispacer = "";
		private $encoding = "UTF-8";
		private $internal_encoding;
		private $prevFillColor = array(255,255,255);
		private $prevTextColor = array(0,0,0);
		private $prevFontFamily;
		private $prevFontStyle;
		public function __construct($orientation='P', $unit='mm', $format='A4', $unicode=true, $encoding="UTF-8"){
			if(function_exists("mb_internal_encoding") AND mb_internal_encoding()){
				$this->internal_encoding = mb_internal_encoding();
				mb_internal_encoding("ASCII");
			}
			$this->_dochecks();
			$this->isunicode=$unicode;
			$this->page=0;
			$this->n=2;
			$this->buffer='';
			$this->pages=array();
			$this->OrientationChanges=array();
			$this->state=0;
			$this->fonts=array();
			$this->FontFiles=array();
			$this->diffs=array();
			$this->images=array();
			$this->links=array();
			$this->InFooter=false;
			$this->lasth=0;
			$this->FontFamily='';
			$this->FontStyle='';
			$this->FontSizePt=12;
			$this->underline=false;
			$this->DrawColor='0 G';
			$this->FillColor='0 g';
			$this->TextColor='0 g';
			$this->ColorFlag=false;
			$this->ws=0;
			$this->CoreFonts=array(
			'courier'=>'Courier',
			'courierB'=>'Courier-Bold',
			'courierI'=>'Courier-Oblique',
			'courierBI'=>'Courier-BoldOblique',
			'helvetica'=>'Helvetica',
			'helveticaB'=>'Helvetica-Bold',
			'helveticaI'=>'Helvetica-Oblique',
			'helveticaBI'=>'Helvetica-BoldOblique',
			'times'=>'Times-Roman',
			'timesB'=>'Times-Bold',
			'timesI'=>'Times-Italic',
			'timesBI'=>'Times-BoldItalic',
			'symbol'=>'Symbol',
			'zapfdingbats'=>'ZapfDingbats'
			);
			switch(strtolower($unit)){
				case 'pt':{$this->k=1; break;}
				case 'mm':{$this->k=72/25.4; break;}
				case 'cm':{$this->k=72/2.54; break;}
				case 'in':{$this->k=72; break;}
				default :{$this->Error('Incorrect unit: '.$unit); break;}
			}
			if(is_string($format)){
				switch(strtoupper($format)){
					case '4A0':{$format = array(4767.87,6740.79); break;}
					case '2A0':{$format = array(3370.39,4767.87); break;}
					case 'A0':{$format = array(2383.94,3370.39); break;}
					case 'A1':{$format = array(1683.78,2383.94); break;}
					case 'A2':{$format = array(1190.55,1683.78); break;}
					case 'A3':{$format = array(841.89,1190.55); break;}
					case 'A4': default:{$format = array(595.28,841.89); break;}
					case 'A5':{$format = array(419.53,595.28); break;}
					case 'A6':{$format = array(297.64,419.53); break;}
					case 'A7':{$format = array(209.76,297.64); break;}
					case 'A8':{$format = array(147.40,209.76); break;}
					case 'A9':{$format = array(104.88,147.40); break;}
					case 'A10':{$format = array(73.70,104.88); break;}
					case 'B0':{$format = array(2834.65,4008.19); break;}
					case 'B1':{$format = array(2004.09,2834.65); break;}
					case 'B2':{$format = array(1417.32,2004.09); break;}
					case 'B3':{$format = array(1000.63,1417.32); break;}
					case 'B4':{$format = array(708.66,1000.63); break;}
					case 'B5':{$format = array(498.90,708.66); break;}
					case 'B6':{$format = array(354.33,498.90); break;}
					case 'B7':{$format = array(249.45,354.33); break;}
					case 'B8':{$format = array(175.75,249.45); break;}
					case 'B9':{$format = array(124.72,175.75); break;}
					case 'B10':{$format = array(87.87,124.72); break;}
					case 'C0':{$format = array(2599.37,3676.54); break;}
					case 'C1':{$format = array(1836.85,2599.37); break;}
					case 'C2':{$format = array(1298.27,1836.85); break;}
					case 'C3':{$format = array(918.43,1298.27); break;}
					case 'C4':{$format = array(649.13,918.43); break;}
					case 'C5':{$format = array(459.21,649.13); break;}
					case 'C6':{$format = array(323.15,459.21); break;}
					case 'C7':{$format = array(229.61,323.15); break;}
					case 'C8':{$format = array(161.57,229.61); break;}
					case 'C9':{$format = array(113.39,161.57); break;}
					case 'C10':{$format = array(79.37,113.39); break;}
					case 'RA0':{$format = array(2437.80,3458.27); break;}
					case 'RA1':{$format = array(1729.13,2437.80); break;}
					case 'RA2':{$format = array(1218.90,1729.13); break;}
					case 'RA3':{$format = array(864.57,1218.90); break;}
					case 'RA4':{$format = array(609.45,864.57); break;}
					case 'SRA0':{$format = array(2551.18,3628.35); break;}
					case 'SRA1':{$format = array(1814.17,2551.18); break;}
					case 'SRA2':{$format = array(1275.59,1814.17); break;}
					case 'SRA3':{$format = array(907.09,1275.59); break;}
					case 'SRA4':{$format = array(637.80,907.09); break;}
					case 'LETTER':{$format = array(612.00,792.00); break;}
					case 'LEGAL':{$format = array(612.00,1008.00); break;}
					case 'EXECUTIVE':{$format = array(521.86,756.00); break;}
					case 'FOLIO':{$format = array(612.00,936.00); break;}
				}
				$this->fwPt=$format[0];
				$this->fhPt=$format[1];
			}
			else{
				$this->fwPt=$format[0]*$this->k;
				$this->fhPt=$format[1]*$this->k;
			}
			$this->fw=$this->fwPt/$this->k;
			$this->fh=$this->fhPt/$this->k;
			$orientation=strtolower($orientation);
			if($orientation=='p' or $orientation=='portrait'){
				$this->DefOrientation='P';
				$this->wPt=$this->fwPt;
				$this->hPt=$this->fhPt;
			}
			elseif($orientation=='l' or $orientation=='landscape'){
				$this->DefOrientation='L';
				$this->wPt=$this->fhPt;
				$this->hPt=$this->fwPt;
			}
			else{
				$this->Error('Incorrect orientation: '.$orientation);
			}
			$this->CurOrientation=$this->DefOrientation;
			$this->w=$this->wPt/$this->k;
			$this->h=$this->hPt/$this->k;
			$margin=28.35/$this->k;
			$this->SetMargins($margin,$margin);
			$this->cMargin=$margin/10;
			$this->LineWidth=.567/$this->k;
			$this->SetAutoPageBreak(true,2*$margin);
			$this->SetDisplayMode('fullwidth');
			$this->SetCompression(true);
			$this->PDFVersion = "1.3";
			$this->encoding = $encoding;
			$this->b = 0;
			$this->i = 0;
			$this->u = 0;
			$this->HREF = '';
			$this->fontlist = array("arial", "times", "courier", "helvetica", "symbol");
			$this->issetfont = false;
			$this->issetcolor = false;
			$this->tableborder = 0;
			$this->tdbegin = false;
			$this->tdwidth=  0;
			$this->tdheight = 0;
			$this->tdalign = "L";
			$this->tdbgcolor = false;
			$this->SetFillColor(200, 200, 200, true);
			$this->SetTextColor(0, 0, 0, true);
		}
		public function __destruct(){
			if(isset($this->internal_encoding) AND !empty($this->internal_encoding)){
				mb_internal_encoding($this->internal_encoding);
			}
		}
		public function setLastH($h){
			$this->lasth=$h;
		}
		public function setImageScale($scale){
			$this->imgscale=$scale;
		}
		public function getImageScale(){
			return $this->imgscale;
		}
		public function getPageWidth(){
			return $this->w;
		}
		public function getPageHeight(){
			return $this->h;
		}
		public function getBreakMargin(){
			return $this->bMargin;
		}
		public function getScaleFactor(){
			return $this->k;
		}
		public function SetMargins($left, $top, $right=-1){
			$this->lMargin=$left;
			$this->tMargin=$top;
			if($right==-1){
				$right=$left;
			}
			$this->rMargin=$right;
		}
		public function SetLeftMargin($margin){
			$this->lMargin=$margin;
			if(($this->page>0) and($this->x<$margin)){
				$this->x=$margin;
			}
		}
		public function SetTopMargin($margin){
			$this->tMargin=$margin;
		}
		public function SetRightMargin($margin){
			$this->rMargin=$margin;
		}
		public function SetAutoPageBreak($auto, $margin=0){
			$this->AutoPageBreak=$auto;
			$this->bMargin=$margin;
			$this->PageBreakTrigger=$this->h-$margin;
		}
		public function SetDisplayMode($zoom, $layout='continuous'){
			if($zoom=='fullpage' or $zoom=='fullwidth' or $zoom=='real' or $zoom=='default' or !is_string($zoom)){
				$this->ZoomMode=$zoom;
			}
			else{
				$this->Error('Incorrect zoom display mode: '.$zoom);
			}
			if($layout=='single' or $layout=='continuous' or $layout=='two' or $layout=='default'){
				$this->LayoutMode=$layout;
			}
			else{
				$this->Error('Incorrect layout display mode: '.$layout);
			}
		}
		public function SetCompression($compress){
			if(function_exists('gzcompress')){
				$this->compress=$compress;
			}
			else{
				$this->compress=false;
			}
		}
		public function SetTitle($title){
			$this->title=$title;
		}
		public function SetSubject($subject){
			$this->subject=$subject;
		}
		public function SetAuthor($author){
			$this->author=$author;
		}
		public function SetKeywords($keywords){
			$this->keywords=$keywords;
		}
		public function SetCreator($creator){
			$this->creator=$creator;
		}
		public function AliasNbPages($alias='{nb}'){
			$this->AliasNbPages = $this->_escapetext($alias);
		}
		public function Error($msg){
			die('<strong>TCPDF error: </strong>'.$msg);
		}
		public function Open(){
			$this->state=1;
		}
		public function Close(){
			if($this->state==3){
				return;
			}
			if($this->page==0){
				$this->AddPage();
			}
			$this->InFooter=true;
			$this->Footer();
			$this->InFooter=false;
			$this->_endpage();
			$this->_enddoc();
		}
		public function AddPage($orientation=''){
			if($this->state==0){
				$this->Open();
			}
			$family=$this->FontFamily;
			$style=$this->FontStyle.($this->underline ? 'U' : '');
			$size=$this->FontSizePt;
			$lw=$this->LineWidth;
			$dc=$this->DrawColor;
			$fc=$this->FillColor;
			$tc=$this->TextColor;
			$cf=$this->ColorFlag;
			if($this->page>0){
				$this->InFooter=true;
				$this->Footer();
				$this->InFooter=false;
				$this->_endpage();
			}
			$this->_beginpage($orientation);
			$this->_out('2 J');
			$this->LineWidth=$lw;
			$this->_out(sprintf('%.2f w',$lw*$this->k));
			if($family){
				$this->SetFont($family,$style,$size);
			}
			$this->DrawColor=$dc;
			if($dc!='0 G'){
				$this->_out($dc);
			}
			$this->FillColor=$fc;
			if($fc!='0 g'){
				$this->_out($fc);
			}
			$this->TextColor=$tc;
			$this->ColorFlag=$cf;
			$this->Header();
			if($this->LineWidth!=$lw){
				$this->LineWidth=$lw;
				$this->_out(sprintf('%.2f w',$lw*$this->k));
			}
			if($family){
				$this->SetFont($family,$style,$size);
			}
			if($this->DrawColor!=$dc){
				$this->DrawColor=$dc;
				$this->_out($dc);
			}
			if($this->FillColor!=$fc){
				$this->FillColor=$fc;
				$this->_out($fc);
			}
			$this->TextColor=$tc;
			$this->ColorFlag=$cf;
		}
		public function setHeaderData($ln="", $lw=0, $ht="", $hs=""){
			$this->header_logo = $ln;
			$this->header_logo_width = $lw;
			$this->header_title = $ht;
			$this->header_string = $hs;
		}
		public function setHeaderMargin($hm=10){
			$this->header_margin = $hm;
		}
		public function setFooterMargin($fm=10){
			$this->footer_margin = $fm;
		}
		public function setPrintHeader($val=true){
			$this->print_header = $val;
		}
		public function setPrintFooter($val=true){
			$this->print_footer = $val;
		}
		public function Header(){
			if($this->print_header){
				if(!isset($this->original_lMargin)){
					$this->original_lMargin = $this->lMargin;
				}
				if(!isset($this->original_rMargin)){
					$this->original_rMargin = $this->rMargin;
				}
				$this->SetXY($this->original_lMargin, $this->header_margin);
				if(($this->header_logo) AND($this->header_logo != K_BLANK_IMAGE)){
					$this->Image(K_PATH_IMAGES.$this->header_logo, $this->original_lMargin, $this->header_margin, $this->header_logo_width);
				}
				else{
					$this->img_rb_y = $this->GetY();
				}
				$cell_height = round((K_CELL_HEIGHT_RATIO * $this->header_font[2]) / $this->k, 2);
				$header_x = $this->original_lMargin +($this->header_logo_width * 1.05);
				$this->SetFont($this->header_font[0], 'B', $this->header_font[2] + 1);
				$this->SetX($header_x);
				$this->Cell($this->header_width, $cell_height, $this->header_title, 0, 1, 'L');
				$this->SetFont($this->header_font[0], $this->header_font[1], $this->header_font[2]);
				$this->SetX($header_x);
				$this->MultiCell($this->header_width, $cell_height, $this->header_string, 0, 'L', 0);
				if(empty($this->header_width)){
					$this->SetLineWidth(0.3);
					$this->SetDrawColor(0, 0, 0);
					$this->SetY(1 + max($this->img_rb_y, $this->GetY()));
					$this->SetX($this->original_lMargin);
					$this->Cell(0, 0, '', 'T', 0, 'C');
				}
				$this->SetXY($this->original_lMargin, $this->tMargin);
			}
		}
		public function Footer(){
			if($this->print_footer){
				if(!isset($this->original_lMargin)){
					$this->original_lMargin = $this->lMargin;
				}
				if(!isset($this->original_rMargin)){
					$this->original_rMargin = $this->rMargin;
				}
				$this->SetFont($this->footer_font[0], $this->footer_font[1] , $this->footer_font[2]);
				$line_width = 0.3;
				$this->SetLineWidth($line_width);
				$this->SetDrawColor(0, 0, 0);
				$footer_height = round((K_CELL_HEIGHT_RATIO * $this->footer_font[2]) / $this->k, 2);
				$footer_y = $this->h - $this->footer_margin - $footer_height;
				$this->SetXY($this->original_lMargin, $footer_y);
				if($this->barcode){
					$this->Ln();
					$barcode_width = round(($this->w - $this->original_lMargin - $this->original_rMargin));
					$this->writeBarcode($this->original_lMargin, $footer_y + $line_width, $barcode_width, $footer_height - $line_width, "C128B", false, false, 2, $this->barcode);
				}
				$this->SetXY($this->original_lMargin, $footer_y);
				$this->Cell(0, $footer_height, $this->l['w_page']." ".$this->PageNo().' / {nb}', 'T', 0, 'R');
			}
		}
		public function PageNo(){
			return $this->page;
		}
		public function SetDrawColor($r, $g=-1, $b=-1){
			if(($r==0 and $g==0 and $b==0) or $g==-1){
				$this->DrawColor=sprintf('%.3f G',$r/255);
			}
			else{
				$this->DrawColor=sprintf('%.3f %.3f %.3f RG',$r/255,$g/255,$b/255);
			}
			if($this->page>0){
				$this->_out($this->DrawColor);
			}
		}
		public function SetFillColor($r, $g=-1, $b=-1, $storeprev=false){
			if(($r==0 and $g==0 and $b==0) or $g==-1){
				$this->FillColor=sprintf('%.3f g',$r/255);
			}
			else{
				$this->FillColor=sprintf('%.3f %.3f %.3f rg',$r/255,$g/255,$b/255);
			}
			$this->ColorFlag=($this->FillColor!=$this->TextColor);
			if($this->page>0){
				$this->_out($this->FillColor);
			}
			if($storeprev){
				$this->prevFillColor = array($r, $g, $b);
			}
		}
		public function SetTextColor($r, $g=-1, $b=-1, $storeprev=false){
			if(($r==0 and $g==0 and $b==0) or $g==-1){
				$this->TextColor=sprintf('%.3f g',$r/255);
			}
			else{
				$this->TextColor=sprintf('%.3f %.3f %.3f rg',$r/255,$g/255,$b/255);
			}
			$this->ColorFlag=($this->FillColor!=$this->TextColor);
			if($storeprev){
				$this->prevTextColor = array($r, $g, $b);
			}
		}
		public function GetStringWidth($s){
			$s = (string)$s;
			$cw = &$this->CurrentFont['cw'];
			$w = 0;
			if($this->isunicode){
				$unicode = $this->UTF8StringToArray($s);
				foreach($unicode as $char){
					if(isset($cw[$char])){
						$w+=$cw[$char];
					} elseif(isset($cw[ord($char)])){
						$w+=$cw[ord($char)];
					} elseif(isset($cw[chr($char)])){
						$w+=$cw[chr($char)];
					} elseif(isset($this->CurrentFont['desc']['MissingWidth'])){
						$w += $this->CurrentFont['desc']['MissingWidth'];
					} else{
						$w += 500;
					}
				}
			} else{
				$l = strlen($s);
				for($i=0; $i<$l; $i++){
					if(isset($cw[$s{$i}])){
						$w += $cw[$s{$i}];
					} else if(isset($cw[ord($s{$i})])){
						$w += $cw[ord($s{$i})];
					}
				}
			}
			return($w * $this->FontSize / 1000);
		}
		public function SetLineWidth($width){
			$this->LineWidth=$width;
			if($this->page>0){
				$this->_out(sprintf('%.2f w',$width*$this->k));
			}
		}
		public function Line($x1, $y1, $x2, $y2){
			$this->_out(sprintf('%.2f %.2f m %.2f %.2f l S', $x1*$this->k,($this->h-$y1)*$this->k, $x2*$this->k,($this->h-$y2)*$this->k));
		}
		public function Rect($x, $y, $w, $h, $style=''){
			if($style=='F'){
				$op='f';
			}
			elseif($style=='FD' or $style=='DF'){
				$op='B';
			}
			else{
				$op='S';
			}
			$this->_out(sprintf('%.2f %.2f %.2f %.2f re %s',$x*$this->k,($this->h-$y)*$this->k,$w*$this->k,-$h*$this->k,$op));
		}
		public function AddFont($family, $style='', $file=''){
			if(empty($family)){
				return;
			}
			$family = strtolower($family);
			if((!$this->isunicode) AND($family == 'arial')){
				$family = 'helvetica';
			}
			$style=strtoupper($style);
			$style=str_replace('U','',$style);
			if($style == 'IB'){
				$style = 'BI';
			}
			$fontkey = $family.$style;
			if(isset($this->fonts[$fontkey])){
				return;
			}
			if($file==''){
				$file = str_replace(' ', '', $family).strtolower($style).'.php';
			}
			if(!file_exists($this->_getfontpath().$file)){
				$file = str_replace(' ', '', $family).'.php';
			}
			include($this->_getfontpath().$file);
			if(!isset($name) AND !isset($fpdf_charwidths)){
				$this->Error('Could not include font definition file');
			}
			$i = count($this->fonts)+1;
			if($this->isunicode){
				$this->fonts[$fontkey] = array('i'=>$i, 'type'=>$type, 'name'=>$name, 'desc'=>$desc, 'up'=>$up, 'ut'=>$ut, 'cw'=>$cw, 'enc'=>$enc, 'file'=>$file, 'ctg'=>$ctg);
				$fpdf_charwidths[$fontkey] = $cw;
			} else{
				$this->fonts[$fontkey]=array('i'=>$i, 'type'=>'core', 'name'=>$this->CoreFonts[$fontkey], 'up'=>-100, 'ut'=>50, 'cw'=>$fpdf_charwidths[$fontkey]);
			}
			if(isset($diff) AND(!empty($diff))){
				$d=0;
				$nb=count($this->diffs);
				for($i=1;$i<=$nb;$i++){
					if($this->diffs[$i]==$diff){
						$d=$i;
						break;
					}
				}
				if($d==0){
					$d=$nb+1;
					$this->diffs[$d]=$diff;
				}
				$this->fonts[$fontkey]['diff']=$d;
			}
			if(!empty($file)){
				if((strcasecmp($type,"TrueType") == 0) OR(strcasecmp($type,"TrueTypeUnicode") == 0)){
					$this->FontFiles[$file]=array('length1'=>$originalsize);
				}
				else{
					$this->FontFiles[$file]=array('length1'=>$size1,'length2'=>$size2);
				}
			}
		}
		public function SetFont($family, $style='', $size=0){
			$this->prevFontFamily = $this->FontFamily;
			$this->prevFontStyle = $this->FontStyle;
			global $fpdf_charwidths;
			$family=strtolower($family);
			if($family==''){
				$family=$this->FontFamily;
			}
			if((!$this->isunicode) AND($family == 'arial')){
				$family = 'helvetica';
			}
			elseif(($family=="symbol") OR($family=="zapfdingbats")){
				$style='';
			}
			$style=strtoupper($style);
			if(strpos($style,'U')!==false){
				$this->underline=true;
				$style=str_replace('U','',$style);
			}
			else{
				$this->underline=false;
			}
			if($style=='IB'){
				$style='BI';
			}
			if($size==0){
				$size=$this->FontSizePt;
			}
			if($this->isunicode){
				$this->AddFont($family, $style);
			}
			if(($this->FontFamily == $family) AND($this->FontStyle == $style) AND($this->FontSizePt == $size)){
				return;
			}
			$fontkey = $family.$style;
			if(!isset($this->fonts[$fontkey])){
				if(isset($this->CoreFonts[$fontkey])){
					if(!isset($fpdf_charwidths[$fontkey])){
						$file = $family;
						if(($family!='symbol') AND($family!='zapfdingbats')){
							$file .= strtolower($style);
						}
						if(!file_exists($this->_getfontpath().$file.'.php')){
							$file = $family;
							$fontkey = $family;
						}
						include($this->_getfontpath().$file.'.php');
						if(($this->isunicode AND !isset($ctg)) OR((!$this->isunicode) AND(!isset($fpdf_charwidths[$fontkey])))){
							$this->Error("Could not include font metric file [".$fontkey."]: ".$this->_getfontpath().$file.".php");
						}
					}
					$i = count($this->fonts) + 1;
					if($this->isunicode){
						$this->fonts[$fontkey] = array('i'=>$i, 'type'=>$type, 'name'=>$name, 'desc'=>$desc, 'up'=>$up, 'ut'=>$ut, 'cw'=>$cw, 'enc'=>$enc, 'file'=>$file, 'ctg'=>$ctg);
						$fpdf_charwidths[$fontkey] = $cw;
					} else{
						$this->fonts[$fontkey]=array('i'=>$i, 'type'=>'core', 'name'=>$this->CoreFonts[$fontkey], 'up'=>-100, 'ut'=>50, 'cw'=>$fpdf_charwidths[$fontkey]);
					}
				}
				else{
					$this->Error('Undefined font: '.$family.' '.$style);
				}
			}
			$this->FontFamily = $family;
			$this->FontStyle = $style;
			$this->FontSizePt = $size;
			$this->FontSize = $size / $this->k;
			$this->CurrentFont = &$this->fonts[$fontkey];
			if($this->page>0){
				$this->_out(sprintf('BT /F%d %.2f Tf ET', $this->CurrentFont['i'], $this->FontSizePt));
			}
		}
		public function SetFontSize($size){
			if($this->FontSizePt==$size){
				return;
			}
			$this->FontSizePt = $size;
			$this->FontSize = $size / $this->k;
			if($this->page > 0){
				$this->_out(sprintf('BT /F%d %.2f Tf ET', $this->CurrentFont['i'], $this->FontSizePt));
			}
		}
		public function AddLink(){
			$n=count($this->links)+1;
			$this->links[$n]=array(0,0);
			return $n;
		}
		public function SetLink($link, $y=0, $page=-1){
			if($y==-1){
				$y=$this->y;
			}
			if($page==-1){
				$page=$this->page;
			}
			$this->links[$link]=array($page,$y);
		}
		public function Link($x, $y, $w, $h, $link){
			$this->PageLinks[$this->page][] = array($x * $this->k, $this->hPt - $y * $this->k, $w * $this->k, $h*$this->k, $link);
		}
		public function Text($x, $y, $txt){
			$s=sprintf('BT %.2f %.2f Td (%s) Tj ET', $x * $this->k,($this->h-$y) * $this->k, $this->_escapetext($txt));
			if($this->underline AND($txt!='')){
				$s .= ' '.$this->_dounderline($x,$y,$txt);
			}
			if($this->ColorFlag){
				$s='q '.$this->TextColor.' '.$s.' Q';
			}
			$this->_out($s);
		}
		public function AcceptPageBreak(){
			return $this->AutoPageBreak;
		}
		public function Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link=''){
			$k=$this->k;
			if(($this->y + $h) > $this->PageBreakTrigger AND empty($this->InFooter) AND $this->AcceptPageBreak()){
				$x = $this->x;
				$ws = $this->ws;
				if($ws > 0){
					$this->ws = 0;
					$this->_out('0 Tw');
				}
				$this->AddPage($this->CurOrientation);
				$this->x = $x;
				if($ws > 0){
					$this->ws = $ws;
					$this->_out(sprintf('%.3f Tw',$ws * $k));
				}
			}
			if($w == 0){
				$w = $this->w - $this->rMargin - $this->x;
			}
			$s = '';
			if(($fill == 1) OR($border == 1)){
				if($fill == 1){
					$op =($border == 1) ? 'B' : 'f';
				}
				else{
					$op = 'S';
				}
				$s = sprintf('%.2f %.2f %.2f %.2f re %s ', $this->x * $k,($this->h - $this->y) * $k, $w * $k, -$h * $k, $op);
			}
			if(is_string($border)){
				$x=$this->x;
				$y=$this->y;
				if(strpos($border,'L')!==false){
					$s.=sprintf('%.2f %.2f m %.2f %.2f l S ',$x*$k,($this->h-$y)*$k,$x*$k,($this->h-($y+$h))*$k);
				}
				if(strpos($border,'T')!==false){
					$s.=sprintf('%.2f %.2f m %.2f %.2f l S ',$x*$k,($this->h-$y)*$k,($x+$w)*$k,($this->h-$y)*$k);
				}
				if(strpos($border,'R')!==false){
					$s.=sprintf('%.2f %.2f m %.2f %.2f l S ',($x+$w)*$k,($this->h-$y)*$k,($x+$w)*$k,($this->h-($y+$h))*$k);
				}
				if(strpos($border,'B')!==false){
					$s.=sprintf('%.2f %.2f m %.2f %.2f l S ',$x*$k,($this->h-($y+$h))*$k,($x+$w)*$k,($this->h-($y+$h))*$k);
				}
			}
			if($txt != ''){
				$width = $this->GetStringWidth($txt);
				if($align == 'R'){
					$dx = $w - $this->cMargin - $width;
				}
				elseif($align=='C'){
					$dx =($w - $width)/2;
				}
				elseif($align=='J'){
					$txt = $this->justify($w - $this->cMargin - 2,$width,$this->GetStringWidth(" "),$txt);
					$width = $this->GetStringWidth($txt);
					$dx = $this->cMargin;
				}
				else{
					$dx = $this->cMargin;
				}
				if($this->ColorFlag){
					$s .= 'q '.$this->TextColor.' ';
				}
				$txt2 = $this->_escapetext($txt);
				$s.=sprintf('BT %.2f %.2f Td (%s) Tj ET',($this->x + $dx) * $k,($this->h -($this->y + 0.5 * $h + 0.3 * $this->FontSize)) * $k, $txt2);
				if($this->underline){
					$s.=' '.$this->_dounderline($this->x + $dx, $this->y + 0.5 * $h + 0.3 * $this->FontSize, $txt);
				}
				if($this->ColorFlag){
					$s.=' Q';
				}
				if($link){
					$this->Link($this->x + $dx, $this->y + 0.5 * $h - 0.5 * $this->FontSize, $width, $this->FontSize, $link);
				}
			}
			if($s){
				$this->_out($s);
			}
			$this->lasth = $h;
			if($ln>0){
				$this->y += $h;
				if($ln == 1){
					$this->x = $this->lMargin;
				}
			}
			else{
				$this->x += $w;
			}
		}
		public function MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1){
			$prevx = $this->x;
			$prevy = $this->y;
			$cw = &$this->CurrentFont['cw'];
			if($w == 0){
				$w = $this->w - $this->rMargin - $this->x;
			}
			$wmax =($w - 2 * $this->cMargin);
			$s = str_replace("\r", '', $txt);
			$nb = strlen($s);
			$b=0;
			if($border){
				if($border==1){
					$border='LTRB';
					$b='LRT';
					$b2='LR';
				}
				else{
					$b2='';
					if(strpos($border,'L')!==false){
						$b2.='L';
					}
					if(strpos($border,'R')!==false){
						$b2.='R';
					}
					$b=(strpos($border,'T')!==false) ? $b2.'T' : $b2;
				}
			}
			$sep=-1;
			$i=0;
			$j=0;
			$l=0;
			$ns=0;
			$nl=1;
			while($i<$nb){
				$c = $s{$i};
				if(preg_match("/[\n]/u", $c)){
					if($this->ws > 0){
						$this->ws = 0;
						$this->_out('0 Tw');
					}
					$this->Cell($w, $h, substr($s, $j, $i-$j), $b, 2, $align, $fill);
					$i++;
					$sep=-1;
					$j=$i;
					$l=0;
					$ns=0;
					$nl++;
					if($border and $nl==2){
						$b = $b2;
					}
					continue;
				}
				if(preg_match("/[ ]/u", $c)){
					$sep = $i;
					$ls = $l;
					$ns++;
				}
				$l = $this->GetStringWidth(substr($s, $j, $i-$j));
				if($l > $wmax){
					if($sep == -1){
						if($i == $j){
							$i++;
						}
						if($this->ws > 0){
							$this->ws = 0;
							$this->_out('0 Tw');
						}
						$this->Cell($w, $h, substr($s, $j, $i-$j), $b, 2, $align, $fill);
					}
					else{
						if($align=='J'){
							$this->ws=($ns>1) ?($wmax-$ls)/1000*$this->FontSize/($ns-1) : 0;
							$this->_out(sprintf('%.3f Tw', $this->ws * $this->k));
						}
						$this->Cell($w, $h, substr($s, $j, $sep-$j), $b, 2, $align, $fill);
						$i = $sep + 1;
					}
					$sep=-1;
					$j=$i;
					$l=0;
					$ns=0;
					$nl++;
					if($border AND($nl==2)){
						$b=$b2;
					}
				}
				else{
					$i++;
				}
			}
			if($this->ws>0){
				$this->ws=0;
				$this->_out('0 Tw');
			}
			if($border and is_int(strpos($border,'B'))){
				$b.='B';
			}
			if($align == "J") $align = "L";
			$this->Cell($w, $h, substr($s, $j, $i-$j), $b, 2, $align, $fill);
			 if($ln == 1){
				$this->x = $this->lMargin;
			} elseif($ln == 0){
				$this->y = $prevy;
				$this->x = $prevx + $w;
			} elseif($ln == 2){
				$this->x = $prevx;
			}
			return $nl;
		}
		public function Write($h, $txt, $link='', $fill=0){
			$cw = &$this->CurrentFont['cw'];
			$w = $this->w - $this->rMargin - $this->x;
			$wmax =($w - 2 * $this->cMargin);
			$s = str_replace("\r", '', $txt);
			$nb = strlen($s);
			if(($nb==1) AND preg_match("/[ ]/u", $s)){
				$this->x += $this->GetStringWidth($s);
				return;
			}
			$sep=-1;
			$i=0;
			$j=0;
			$l=0;
			$nl=1;
			while($i<$nb){
				$c=$s{$i};
				if(preg_match("/[\n]/u", $c)){
					$this->Cell($w, $h, substr($s, $j, $i-$j), 0, 2, '', $fill, $link);
					$i++;
					$sep = -1;
					$j = $i;
					$l = 0;
					if($nl == 1){
						$this->x = $this->lMargin;
						$w = $this->w - $this->rMargin - $this->x;
						$wmax =($w - 2 * $this->cMargin);
					}
					$nl++;
					continue;
				}
				if(preg_match("/[ ]/u", $c)){
					$sep= $i;
				}
				$l = $this->GetStringWidth(substr($s, $j, $i-$j));
				if($l > $wmax){
					if($sep == -1){
						if($this->x > $this->lMargin){
							$this->x = $this->lMargin;
							$this->y += $h;
							$w=$this->w - $this->rMargin - $this->x;
							$wmax=($w - 2 * $this->cMargin);
							$i++;
							$nl++;
							continue;
						}
						if($i==$j){
							$i++;
						}
						$this->Cell($w, $h, substr($s, $j, $i-$j), 0, 2, '', $fill, $link);
					}
					else{
						$this->Cell($w, $h, substr($s, $j, $sep-$j), 0, 2, '', $fill, $link);
						$i=$sep+1;
					}
					$sep = -1;
					$j = $i;
					$l = 0;
					if($nl==1){
						$this->x = $this->lMargin;
						$w = $this->w - $this->rMargin - $this->x;
						$wmax =($w - 2 * $this->cMargin);
					}
					$nl++;
				}
				else{
					$i++;
				}
			}
			if($i!=$j){
				$this->Cell($this->GetStringWidth(substr($s, $j)), $h, substr($s, $j), 0, 0, '', $fill, $link);
			}
		}
		public function Image($file, $x, $y, $w=0, $h=0, $type='', $link=''){
			if(!isset($this->images[$file])){
				if($type == ''){
					$pos = strrpos($file,'.');
					if(empty($pos)){
						$this->Error('Image file has no extension and no type was specified: '.$file);
					}
					$type = substr($file, $pos+1);
				}
				$type = strtolower($type);
				$mqr = get_magic_quotes_runtime();
				set_magic_quotes_runtime(0);
				if($type == 'jpg' or $type == 'jpeg'){
					$info=$this->_parsejpg($file);
				}
				elseif($type == 'png'){
					$info=$this->_parsepng($file);
				}
				else{
					$mtd='_parse'.$type;
					if(!method_exists($this,$mtd)){
						$this->Error('Unsupported image type: '.$type);
					}
					$info=$this->$mtd($file);
				}
				set_magic_quotes_runtime($mqr);
				$info['i']=count($this->images)+1;
				$this->images[$file]=$info;
			}
			else{
				$info=$this->images[$file];
			}
			if(($w == 0) and($h == 0)){
				$w = $info['w'] /($this->imgscale * $this->k);
				$h = $info['h'] /($this->imgscale * $this->k);
			}
			if($w == 0){
				$w = $h * $info['w'] / $info['h'];
			}
			if($h == 0){
				$h = $w * $info['h'] / $info['w'];
			}
			$this->_out(sprintf('q %.2f 0 0 %.2f %.2f %.2f cm /I%d Do Q', $w*$this->k, $h*$this->k, $x*$this->k,($this->h-($y+$h))*$this->k, $info['i']));
			if($link){
				$this->Link($x, $y, $w, $h, $link);
			}
			$this->img_rb_x = $x + $w;
			$this->img_rb_y = $y + $h;
		}
		public function Ln($h=''){
			$this->x=$this->lMargin;
			if(is_string($h)){
				$this->y+=$this->lasth;
			}
			else{
				$this->y+=$h;
			}
		}
		public function GetX(){
			return $this->x;
		}
		public function SetX($x){
			if($x>=0){
				$this->x=$x;
			}
			else{
				$this->x=$this->w+$x;
			}
		}
		public function GetY(){
			return $this->y;
		}
		public function SetY($y){
			$this->x=$this->lMargin;
			if($y>=0){
				$this->y=$y;
			}
			else{
				$this->y=$this->h+$y;
			}
		}
		public function SetXY($x, $y){
			$this->SetY($y);
			$this->SetX($x);
		}
		public function Output($name='',$dest=''){
			if($this->state < 3){
				$this->Close();
			}
			if(is_bool($dest)){
				$dest=$dest ? 'D' : 'F';
			}
			$dest=strtoupper($dest);
			if($dest==''){
				if($name==''){
					$name='doc.pdf';
					$dest='I';
				} else{
					$dest='F';
				}
			}
			switch($dest){
				case 'I':{
					if(ob_get_contents()){
						$this->Error('Some data has already been output, can\'t send PDF file');
					}
					if(php_sapi_name()!='cli'){
						header('Content-Type: application/pdf');
						if(headers_sent()){
							$this->Error('Some data has already been output to browser, can\'t send PDF file');
						}
						header('Content-Length: '.strlen($this->buffer));
						header('Content-disposition: inline; filename="'.$name.'"');
					}
					echo $this->buffer;
					break;
				}
				case 'D':{
					if(ob_get_contents()){
						$this->Error('Some data has already been output, can\'t send PDF file');
					}
					if(isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'],'MSIE')){
						header('Content-Type: application/force-download');
					} else{
						header('Content-Type: application/octet-stream');
					}
					if(headers_sent()){
						$this->Error('Some data has already been output to browser, can\'t send PDF file');
					}
					header('Content-Length: '.strlen($this->buffer));
					header('Content-disposition: attachment; filename="'.$name.'"');
					echo $this->buffer;
					break;
				}
				case 'F':{
					$f=fopen($name,'wb');
					if(!$f){
						$this->Error('Unable to create output file: '.$name);
					}
					fwrite($f,$this->buffer,strlen($this->buffer));
					fclose($f);
					break;
				}
				case 'S':{
					return $this->buffer;
				}
				default:{
					$this->Error('Incorrect output destination: '.$dest);
				}
			}
			return '';
		}
		protected function _dochecks(){
			if(1.1==1){
				$this->Error('Don\'t alter the locale before including class file');
			}
			if(sprintf('%.1f',1.0)!='1.0'){
				setlocale(LC_NUMERIC,'C');
			}
		}
		protected function _getfontpath(){
			if(!defined('FPDF_FONTPATH') AND is_dir(dirname(__FILE__).'/font')){
				define('FPDF_FONTPATH', dirname(__FILE__).'/font/');
			}
			return defined('FPDF_FONTPATH') ? FPDF_FONTPATH : '';
		}
		protected function _begindoc(){
			$this->state=1;
			$this->_out('%PDF-1.3');
		}
		protected function _putpages(){
			$nb = $this->page;
			if(!empty($this->AliasNbPages)){
				$nbstr = $this->UTF8ToUTF16BE($nb, false);
				for($n=1;$n<=$nb;$n++){
					$this->pages[$n]=str_replace($this->AliasNbPages, $nbstr, $this->pages[$n]);
				}
			}
			if($this->DefOrientation=='P'){
				$wPt=$this->fwPt;
				$hPt=$this->fhPt;
			}
			else{
				$wPt=$this->fhPt;
				$hPt=$this->fwPt;
			}
			$filter=($this->compress) ? '/Filter /FlateDecode ' : '';
			for($n=1;$n<=$nb;$n++){
				$this->_newobj();
				$this->_out('<</Type /Page');
				$this->_out('/Parent 1 0 R');
				if(isset($this->OrientationChanges[$n])){
					$this->_out(sprintf('/MediaBox [0 0 %.2f %.2f]',$hPt,$wPt));
				}
				$this->_out('/Resources 2 0 R');
				if(isset($this->PageLinks[$n])){
					$annots='/Annots [';
					foreach($this->PageLinks[$n] as $pl){
						$rect=sprintf('%.2f %.2f %.2f %.2f',$pl[0],$pl[1],$pl[0]+$pl[2],$pl[1]-$pl[3]);
						$annots.='<</Type /Annot /Subtype /Link /Rect ['.$rect.'] /Border [0 0 0] ';
						if(is_string($pl[4])){
							$annots.='/A <</S /URI /URI ('.$this->_escape($pl[4]).')>>>>';
						}
						else{
							$l=$this->links[$pl[4]];
							$h=isset($this->OrientationChanges[$l[0]]) ? $wPt : $hPt;
							$annots.=sprintf('/Dest [%d 0 R /XYZ 0 %.2f null]>>',1+2*$l[0],$h-$l[1]*$this->k);
						}
					}
					$this->_out($annots.']');
				}
				$this->_out('/Contents '.($this->n+1).' 0 R>>');
				$this->_out('endobj');
				$p=($this->compress) ? gzcompress($this->pages[$n]) : $this->pages[$n];
				$this->_newobj();
				$this->_out('<<'.$filter.'/Length '.strlen($p).'>>');
				$this->_putstream($p);
				$this->_out('endobj');
			}
			$this->offsets[1]=strlen($this->buffer);
			$this->_out('1 0 obj');
			$this->_out('<</Type /Pages');
			$kids='/Kids [';
			for($i=0;$i<$nb;$i++){
				$kids.=(3+2*$i).' 0 R ';
			}
			$this->_out($kids.']');
			$this->_out('/Count '.$nb);
			$this->_out(sprintf('/MediaBox [0 0 %.2f %.2f]',$wPt,$hPt));
			$this->_out('>>');
			$this->_out('endobj');
		}
		protected function _putfonts(){
			$nf=$this->n;
			foreach($this->diffs as $diff){
				$this->_newobj();
				$this->_out('<</Type /Encoding /BaseEncoding /WinAnsiEncoding /Differences ['.$diff.']>>');
				$this->_out('endobj');
			}
			$mqr=get_magic_quotes_runtime();
			set_magic_quotes_runtime(0);
			foreach($this->FontFiles as $file=>$info){
				$this->_newobj();
				$this->FontFiles[$file]['n']=$this->n;
				$font='';
				$f=fopen($this->_getfontpath().strtolower($file),'rb',1);
				if(!$f){
					$this->Error('Font file not found: '.$file);
				}
				while(!feof($f)){
					$font .= fread($f, 8192);
				}
				fclose($f);
				$compressed=(substr($file,-2)=='.z');
				if(!$compressed && isset($info['length2'])){
					$header=(ord($font{0})==128);
					if($header){
						$font=substr($font,6);
					}
					if($header && ord($font{$info['length1']})==128){
						$font=substr($font,0,$info['length1']).substr($font,$info['length1']+6);
					}
				}
				$this->_out('<</Length '.strlen($font));
				if($compressed){
					$this->_out('/Filter /FlateDecode');
				}
				$this->_out('/Length1 '.$info['length1']);
				if(isset($info['length2'])){
					$this->_out('/Length2 '.$info['length2'].' /Length3 0');
				}
				$this->_out('>>');
				$this->_putstream($font);
				$this->_out('endobj');
			}
			set_magic_quotes_runtime($mqr);
			foreach($this->fonts as $k=>$font){
				$this->fonts[$k]['n']=$this->n+1;
				$type=$font['type'];
				$name=$font['name'];
				if($type=='core'){
					$this->_newobj();
					$this->_out('<</Type /Font');
					$this->_out('/BaseFont /'.$name);
					$this->_out('/Subtype /Type1');
					if($name!='Symbol' && $name!='ZapfDingbats'){
						$this->_out('/Encoding /WinAnsiEncoding');
					}
					$this->_out('>>');
					$this->_out('endobj');
				} elseif($type=='Type1' || $type=='TrueType'){
					$this->_newobj();
					$this->_out('<</Type /Font');
					$this->_out('/BaseFont /'.$name);
					$this->_out('/Subtype /'.$type);
					$this->_out('/FirstChar 32 /LastChar 255');
					$this->_out('/Widths '.($this->n+1).' 0 R');
					$this->_out('/FontDescriptor '.($this->n+2).' 0 R');
					if($font['enc']){
						if(isset($font['diff'])){
							$this->_out('/Encoding '.($nf+$font['diff']).' 0 R');
						} else{
							$this->_out('/Encoding /WinAnsiEncoding');
						}
					}
					$this->_out('>>');
					$this->_out('endobj');
					$this->_newobj();
					$cw=&$font['cw'];
					$s='[';
					for($i=32;$i<=255;$i++){
						$s.=$cw[chr($i)].' ';
					}
					$this->_out($s.']');
					$this->_out('endobj');
					$this->_newobj();
					$s='<</Type /FontDescriptor /FontName /'.$name;
					foreach($font['desc'] as $k=>$v){
						$s.=' /'.$k.' '.$v;
					}
					$file = $font['file'];
					if($file){
						$s.=' /FontFile'.($type=='Type1' ? '' : '2').' '.$this->FontFiles[$file]['n'].' 0 R';
					}
					$this->_out($s.'>>');
					$this->_out('endobj');
				} else{
					$mtd='_put'.strtolower($type);
					if(!method_exists($this, $mtd)){
						$this->Error('Unsupported font type: '.$type);
					}
					$this->$mtd($font);
				}
			}
		}
		protected function _putimages(){
			$filter=($this->compress) ? '/Filter /FlateDecode ' : '';
			reset($this->images);
			while(list($file,$info)=each($this->images)){
				$this->_newobj();
				$this->images[$file]['n']=$this->n;
				$this->_out('<</Type /XObject');
				$this->_out('/Subtype /Image');
				$this->_out('/Width '.$info['w']);
				$this->_out('/Height '.$info['h']);
				if($info['cs']=='Indexed'){
					$this->_out('/ColorSpace [/Indexed /DeviceRGB '.(strlen($info['pal'])/3-1).' '.($this->n+1).' 0 R]');
				}
				else{
					$this->_out('/ColorSpace /'.$info['cs']);
					if($info['cs']=='DeviceCMYK'){
						$this->_out('/Decode [1 0 1 0 1 0 1 0]');
					}
				}
				$this->_out('/BitsPerComponent '.$info['bpc']);
				if(isset($info['f'])){
					$this->_out('/Filter /'.$info['f']);
				}
				if(isset($info['parms'])){
					$this->_out($info['parms']);
				}
				if(isset($info['trns']) and is_array($info['trns'])){
					$trns='';
					for($i=0;$i<count($info['trns']);$i++){
						$trns.=$info['trns'][$i].' '.$info['trns'][$i].' ';
					}
					$this->_out('/Mask ['.$trns.']');
				}
				$this->_out('/Length '.strlen($info['data']).'>>');
				$this->_putstream($info['data']);
				unset($this->images[$file]['data']);
				$this->_out('endobj');
				if($info['cs']=='Indexed'){
					$this->_newobj();
					$pal=($this->compress) ? gzcompress($info['pal']) : $info['pal'];
					$this->_out('<<'.$filter.'/Length '.strlen($pal).'>>');
					$this->_putstream($pal);
					$this->_out('endobj');
				}
			}
		}
		function _putxobjectdict(){
			foreach($this->images as $image){
				$this->_out('/I'.$image['i'].' '.$image['n'].' 0 R');
			}
		}
		function _putresourcedict(){
			$this->_out('/ProcSet [/PDF /Text /ImageB /ImageC /ImageI]');
			$this->_out('/Font <<');
			foreach($this->fonts as $font){
				$this->_out('/F'.$font['i'].' '.$font['n'].' 0 R');
			}
			$this->_out('>>');
			$this->_out('/XObject <<');
			$this->_putxobjectdict();
			$this->_out('>>');
		}
		function _putresources(){
			$this->_putfonts();
			$this->_putimages();
			$this->offsets[2]=strlen($this->buffer);
			$this->_out('2 0 obj');
			$this->_out('<<');
			$this->_putresourcedict();
			$this->_out('>>');
			$this->_out('endobj');
		}
		protected function _putinfo(){
			$this->_out('/Producer '.$this->_textstring(PDF_PRODUCER));
			if(!empty($this->title)){
				$this->_out('/Title '.$this->_textstring($this->title));
			}
			if(!empty($this->subject)){
				$this->_out('/Subject '.$this->_textstring($this->subject));
			}
			if(!empty($this->author)){
				$this->_out('/Author '.$this->_textstring($this->author));
			}
			if(!empty($this->keywords)){
				$this->_out('/Keywords '.$this->_textstring($this->keywords));
			}
			if(!empty($this->creator)){
				$this->_out('/Creator '.$this->_textstring($this->creator));
			}
			$this->_out('/CreationDate '.$this->_textstring('D:'.date('YmdHis')));
		}
		protected function _putcatalog(){
			$this->_out('/Type /Catalog');
			$this->_out('/Pages 1 0 R');
			if($this->ZoomMode=='fullpage'){
				$this->_out('/OpenAction [3 0 R /Fit]');
			}
			elseif($this->ZoomMode=='fullwidth'){
				$this->_out('/OpenAction [3 0 R /FitH null]');
			}
			elseif($this->ZoomMode=='real'){
				$this->_out('/OpenAction [3 0 R /XYZ null null 1]');
			}
			elseif(!is_string($this->ZoomMode)){
				$this->_out('/OpenAction [3 0 R /XYZ null null '.($this->ZoomMode/100).']');
			}
			if($this->LayoutMode=='single'){
				$this->_out('/PageLayout /SinglePage');
			}
			elseif($this->LayoutMode=='continuous'){
				$this->_out('/PageLayout /OneColumn');
			}
			elseif($this->LayoutMode=='two'){
				$this->_out('/PageLayout /TwoColumnLeft');
			}
		}
		protected function _puttrailer(){
			$this->_out('/Size '.($this->n+1));
			$this->_out('/Root '.$this->n.' 0 R');
			$this->_out('/Info '.($this->n-1).' 0 R');
		}
		function _putheader(){
			$this->_out('%PDF-'.$this->PDFVersion);
		}
		protected function _enddoc(){
			$this->_putheader();
			$this->_putpages();
			$this->_putresources();
			$this->_newobj();
			$this->_out('<<');
			$this->_putinfo();
			$this->_out('>>');
			$this->_out('endobj');
			$this->_newobj();
			$this->_out('<<');
			$this->_putcatalog();
			$this->_out('>>');
			$this->_out('endobj');
			$o=strlen($this->buffer);
			$this->_out('xref');
			$this->_out('0 '.($this->n+1));
			$this->_out('0000000000 65535 f ');
			for($i=1;$i<=$this->n;$i++){
				$this->_out(sprintf('%010d 00000 n ',$this->offsets[$i]));
			}
			$this->_out('trailer');
			$this->_out('<<');
			$this->_puttrailer();
			$this->_out('>>');
			$this->_out('startxref');
			$this->_out($o);
			$this->_out('%%EOF');
			$this->state=3;
		}
		protected function _beginpage($orientation){
			$this->page++;
			$this->pages[$this->page]='';
			$this->state=2;
			$this->x=$this->lMargin;
			$this->y=$this->tMargin;
			$this->FontFamily='';
			if(empty($orientation)){
				$orientation=$this->DefOrientation;
			}
			else{
				$orientation=strtoupper($orientation{0});
				if($orientation!=$this->DefOrientation){
					$this->OrientationChanges[$this->page]=true;
				}
			}
			if($orientation!=$this->CurOrientation){
				if($orientation=='P'){
					$this->wPt=$this->fwPt;
					$this->hPt=$this->fhPt;
					$this->w=$this->fw;
					$this->h=$this->fh;
				}
				else{
					$this->wPt=$this->fhPt;
					$this->hPt=$this->fwPt;
					$this->w=$this->fh;
					$this->h=$this->fw;
				}
				$this->PageBreakTrigger=$this->h-$this->bMargin;
				$this->CurOrientation=$orientation;
			}
		}
		protected function _endpage(){
			$this->state=1;
		}
		protected function _newobj(){
			$this->n++;
			$this->offsets[$this->n]=strlen($this->buffer);
			$this->_out($this->n.' 0 obj');
		}
		protected function _dounderline($x,$y,$txt){
			$up = $this->CurrentFont['up'];
			$ut = $this->CurrentFont['ut'];
			$w = $this->GetStringWidth($txt) + $this->ws * substr_count($txt,' ');
			return sprintf('%.2f %.2f %.2f %.2f re f', $x * $this->k,($this->h -($y - $up / 1000 * $this->FontSize)) * $this->k, $w * $this->k, -$ut / 1000 * $this->FontSizePt);
		}
		protected function _parsejpg($file){
			$a=GetImageSize($file);
			if(empty($a)){
				$this->Error('Missing or incorrect image file: '.$file);
			}
			if($a[2]!=2){
				$this->Error('Not a JPEG file: '.$file);
			}
			if(!isset($a['channels']) or $a['channels']==3){
				$colspace='DeviceRGB';
			}
			elseif($a['channels']==4){
				$colspace='DeviceCMYK';
			}
			else{
				$colspace='DeviceGray';
			}
			$bpc=isset($a['bits']) ? $a['bits'] : 8;
			$f=fopen($file,'rb');
			$data='';
			while(!feof($f)){
				$data.=fread($f,4096);
			}
			fclose($f);
			return array('w'=>$a[0],'h'=>$a[1],'cs'=>$colspace,'bpc'=>$bpc,'f'=>'DCTDecode','data'=>$data);
		}
		protected function _parsepng($file){
			$f=fopen($file,'rb');
			if(empty($f)){
				$this->Error('Can\'t open image file: '.$file);
			}
			if(fread($f,8)!=chr(137).'PNG'.chr(13).chr(10).chr(26).chr(10)){
				$this->Error('Not a PNG file: '.$file);
			}
			fread($f,4);
			if(fread($f,4)!='IHDR'){
				$this->Error('Incorrect PNG file: '.$file);
			}
			$w=$this->_freadint($f);
			$h=$this->_freadint($f);
			$bpc=ord(fread($f,1));
			if($bpc>8){
				$this->Error('16-bit depth not supported: '.$file);
			}
			$ct=ord(fread($f,1));
			if($ct==0){
				$colspace='DeviceGray';
			}
			elseif($ct==2){
				$colspace='DeviceRGB';
			}
			elseif($ct==3){
				$colspace='Indexed';
			}
			else{
				$this->Error('Alpha channel not supported: '.$file);
			}
			if(ord(fread($f,1))!=0){
				$this->Error('Unknown compression method: '.$file);
			}
			if(ord(fread($f,1))!=0){
				$this->Error('Unknown filter method: '.$file);
			}
			if(ord(fread($f,1))!=0){
				$this->Error('Interlacing not supported: '.$file);
			}
			fread($f,4);
			$parms='/DecodeParms <</Predictor 15 /Colors '.($ct==2 ? 3 : 1).' /BitsPerComponent '.$bpc.' /Columns '.$w.'>>';
			$pal='';
			$trns='';
			$data='';
			do{
				$n=$this->_freadint($f);
				$type=fread($f,4);
				if($type=='PLTE'){
					$pal=fread($f,$n);
					fread($f,4);
				}
				elseif($type=='tRNS'){
					$t=fread($f,$n);
					if($ct==0){
						$trns=array(ord(substr($t,1,1)));
					}
					elseif($ct==2){
						$trns=array(ord(substr($t,1,1)),ord(substr($t,3,1)),ord(substr($t,5,1)));
					}
					else{
						$pos=strpos($t,chr(0));
						if($pos!==false){
							$trns=array($pos);
						}
					}
					fread($f,4);
				}
				elseif($type=='IDAT'){
					$data.=fread($f,$n);
					fread($f,4);
				}
				elseif($type=='IEND'){
					break;
				}
				else{
					fread($f,$n+4);
				}
			}
			while($n);
			if($colspace=='Indexed' and empty($pal)){
				$this->Error('Missing palette in '.$file);
			}
			fclose($f);
			return array('w'=>$w, 'h'=>$h, 'cs'=>$colspace, 'bpc'=>$bpc, 'f'=>'FlateDecode', 'parms'=>$parms, 'pal'=>$pal, 'trns'=>$trns, 'data'=>$data);
		}
		protected function _freadint($f){
			$a=unpack('Ni',fread($f,4));
			return $a['i'];
		}
		protected function _textstring($s){
			if($this->isunicode){
				$s = $this->UTF8ToUTF16BE($s, true);
			}
			return '('. $this->_escape($s).')';
		}
		function _escapetext($s){
			if($this->isunicode){
				$s = $this->UTF8ToUTF16BE($s, false);
			}
			return $this->_escape($s);
		}
		protected function _escape($s){
			return strtr($s, array(')' => '\\)', '(' => '\\(', '\\' => '\\\\', chr(13) => '\r'));
		}
		protected function _putstream($s){
			$this->_out('stream');
			$this->_out($s);
			$this->_out('endstream');
		}
		protected function _out($s){
			if($this->state==2){
				$this->pages[$this->page] .= $s."\n";
			}
			else{
				$this->buffer .= $s."\n";
			}
		}
		protected function _puttruetypeunicode($font){
			$this->_newobj();
			$this->_out('<</Type /Font');
			$this->_out('/Subtype /Type0');
			$this->_out('/BaseFont /'.$font['name'].'');
			$this->_out('/Encoding /Identity-H');
			$this->_out('/DescendantFonts ['.($this->n + 1).' 0 R]');
			$this->_out('/ToUnicode '.($this->n + 2).' 0 R');
			$this->_out('>>');
			$this->_out('endobj');
			$this->_newobj();
			$this->_out('<</Type /Font');
			$this->_out('/Subtype /CIDFontType2');
			$this->_out('/BaseFont /'.$font['name'].'');
			$this->_out('/CIDSystemInfo '.($this->n + 2).' 0 R');
			$this->_out('/FontDescriptor '.($this->n + 3).' 0 R');
			if(isset($font['desc']['MissingWidth'])){
				$this->_out('/DW '.$font['desc']['MissingWidth'].'');
			}
			$w = "";
			foreach($font['cw'] as $cid => $width){
				$w .= ''.$cid.' ['.$width.'] ';
			}
			$this->_out('/W ['.$w.']');
			$this->_out('/CIDToGIDMap '.($this->n + 4).' 0 R');
			$this->_out('>>');
			$this->_out('endobj');
			$this->_newobj();
			$this->_out('<</Length 383>>');
			$this->_out('stream');
			$this->_out('/CIDInit /ProcSet findresource begin');
			$this->_out('12 dict begin');
			$this->_out('begincmap');
			$this->_out('/CIDSystemInfo');
			$this->_out('<</Registry (Adobe)');
			$this->_out('/Ordering (UCS)');
			$this->_out('/Supplement 0');
			$this->_out('>> def');
			$this->_out('/CMapName /Adobe-Identity-UCS def');
			$this->_out('/CMapType 2 def');
			$this->_out('1 begincodespacerange');
			$this->_out('<0000> <FFFF>');
			$this->_out('endcodespacerange');
			$this->_out('1 beginbfrange');
			$this->_out('<0000> <FFFF> <0000>');
			$this->_out('endbfrange');
			$this->_out('endcmap');
			$this->_out('CMapName currentdict /CMap defineresource pop');
			$this->_out('end');
			$this->_out('end');
			$this->_out('endstream');
			$this->_out('endobj');
			$this->_newobj();
			$this->_out('<</Registry (Adobe)');
			$this->_out('/Ordering (UCS)');
			$this->_out('/Supplement 0');
			$this->_out('>>');
			$this->_out('endobj');
			$this->_newobj();
			$this->_out('<</Type /FontDescriptor');
			$this->_out('/FontName /'.$font['name']);
			foreach($font['desc'] as $key => $value){
				$this->_out('/'.$key.' '.$value);
			}
			if($font['file']){
				$this->_out('/FontFile2 '.$this->FontFiles[$font['file']]['n'].' 0 R');
			}
			$this->_out('>>');
			$this->_out('endobj');
			$this->_newobj();
			$ctgfile = $this->_getfontpath().strtolower($font['ctg']);
			if(!file_exists($ctgfile)){
				$this->Error('Font file not found: '.$ctgfile);
			}
			$size = filesize($ctgfile);
			$this->_out('<</Length '.$size.'');
			if(substr($ctgfile, -2) == '.z'){
				$this->_out('/Filter /FlateDecode');
			}
			$this->_out('>>');
			$this->_putstream(file_get_contents($ctgfile));
			$this->_out('endobj');
		}
		protected function UTF8StringToArray($str){
			if(!$this->isunicode){
				return $str;
			}
			$unicode = array();
			$bytes  = array();
			$numbytes  = 1;
			$str .= "";
			$length = strlen($str);
			for($i = 0; $i < $length; $i++){
				$char = ord($str{$i});
				if(count($bytes) == 0){
					if($char <= 0x7F){
						$unicode[] = $char;
						$numbytes = 1;
					} elseif(($char >> 0x05) == 0x06){
						$bytes[] =($char - 0xC0) << 0x06;
						$numbytes = 2;
					} elseif(($char >> 0x04) == 0x0E){
						$bytes[] =($char - 0xE0) << 0x0C;
						$numbytes = 3;
					} elseif(($char >> 0x03) == 0x1E){
						$bytes[] =($char - 0xF0) << 0x12;
						$numbytes = 4;
					} else{
						$unicode[] = 0xFFFD;
						$bytes = array();
						$numbytes = 1;
					}
				} elseif(($char >> 0x06) == 0x02){
					$bytes[] = $char - 0x80;
					if(count($bytes) == $numbytes){
						$char = $bytes[0];
						for($j = 1; $j < $numbytes; $j++){
							$char +=($bytes[$j] <<(($numbytes - $j - 1) * 0x06));
						}
						if((($char >= 0xD800) AND($char <= 0xDFFF)) OR($char >= 0x10FFFF)){
							$unicode[] = 0xFFFD;
						}
						else{
							$unicode[] = $char;
						}
						$bytes = array();
						$numbytes = 1;
					}
				} else{
					$unicode[] = 0xFFFD;
					$bytes = array();
					$numbytes = 1;
				}
			}
			return $unicode;
		}
		protected function UTF8ToUTF16BE($str, $setbom=true){
			if(!$this->isunicode){
				return $str;
			}
			$outstr = "";
			$unicode = $this->UTF8StringToArray($str);
			$numitems = count($unicode);
			if($setbom){
				$outstr .= "\xFE\xFF";
			}
			foreach($unicode as $char){
				if($char == 0xFFFD){
					$outstr .= "\xFF\xFD";
				} elseif($char < 0x10000){
					$outstr .= chr($char >> 0x08);
					$outstr .= chr($char & 0xFF);
				} else{
					$char -= 0x10000;
					$w1 = 0xD800 |($char >> 0x10);
					$w2 = 0xDC00 |($char & 0x3FF);
					$outstr .= chr($w1 >> 0x08);
					$outstr .= chr($w1 & 0xFF);
					$outstr .= chr($w2 >> 0x08);
					$outstr .= chr($w2 & 0xFF);
				}
			}
			return $outstr;
		}
		public function setHeaderFont($font){
			$this->header_font = $font;
		}
		public function setFooterFont($font){
			$this->footer_font = $font;
		}
		public function setLanguageArray($language){
			$this->l = $language;
		}
		public function setBarcode($bc=""){
			$this->barcode = $bc;
		}
		public function writeBarcode($x, $y, $w, $h, $type, $style, $font, $xres, $code){
			require_once(dirname(__FILE__)."/barcode/barcode.php");
			require_once(dirname(__FILE__)."/barcode/i25object.php");
			require_once(dirname(__FILE__)."/barcode/c39object.php");
			require_once(dirname(__FILE__)."/barcode/c128aobject.php");
			require_once(dirname(__FILE__)."/barcode/c128bobject.php");
			require_once(dirname(__FILE__)."/barcode/c128cobject.php");
			if(empty($code)){
				return;
			}
			if(empty($style)){
				$style  = BCS_ALIGN_LEFT;
				$style |= BCS_IMAGE_PNG;
				$style |= BCS_TRANSPARENT;
			}
			if(empty($font)){$font = BCD_DEFAULT_FONT;}
			if(empty($xres)){$xres = BCD_DEFAULT_XRES;}
			$scale_factor = 1.5 * $xres * $this->k;
			$bc_w = round($w * $scale_factor);
			$bc_h = round($h * $scale_factor);
			switch(strtoupper($type)){
				case "I25":{
					$obj = new I25Object($bc_w, $bc_h, $style, $code);
					break;
				}
				case "C128A":{
					$obj = new C128AObject($bc_w, $bc_h, $style, $code);
					break;
				}
				default:
				case "C128B":{
					$obj = new C128BObject($bc_w, $bc_h, $style, $code);
					break;
				}
				case "C128C":{
					$obj = new C128CObject($bc_w, $bc_h, $style, $code);
					break;
				}
				case "C39":{
					$obj = new C39Object($bc_w, $bc_h, $style, $code);
					break;
				}
			}
			$obj->SetFont($font);
			$obj->DrawObject($xres);
			$tmpName = tempnam(K_PATH_CACHE,'img');
			imagepng($obj->getImage(), $tmpName);
			$this->Image($tmpName, $x, $y, $w, $h, 'png');
			$obj->DestroyObject();
			unset($obj);
			unlink($tmpName);
		}
		public function getPDFData(){
			if($this->state < 3){
				$this->Close();
			}
			return $this->buffer;
		}
		public function writeHTML($html, $ln=true, $fill=0){
		$html=strip_tags($html,"<h1><h2><h3><h4><h5><h6><b><u><i><a><img><p><br><br/><strong><em><font><blockquote><li><ul><ol><hr><td><th><tr><table><sup><sub><small>");
			$repTable = array("\t" => " ", "\n" => " ", "\r" => " ", "\0" => " ", "\x0B" => " ");
			$html = strtr($html, $repTable);
			$pattern = '/(<[^>]+>)/Uu';
			$a = preg_split($pattern, $html, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
			if(empty($this->lasth)){
				$this->lasth = $this->FontSize * K_CELL_HEIGHT_RATIO;
			}
			foreach($a as $key=>$element){
				if(!preg_match($pattern, $element)){
					if($this->HREF){
						$this->addHtmlLink($this->HREF, $element, $fill);
					}
					elseif($this->tdbegin){
						if((strlen(trim($element)) > 0) AND($element != "&nbsp;")){
							$this->Cell($this->tdwidth, $this->tdheight, $this->unhtmlentities($element), $this->tableborder, '', $this->tdalign, $this->tdbgcolor);
						}
						elseif($element == "&nbsp;"){
							$this->Cell($this->tdwidth, $this->tdheight, '', $this->tableborder, '', $this->tdalign, $this->tdbgcolor);
						}
					}
					else{
						$this->Write($this->lasth, stripslashes($this->unhtmlentities($element)), '', $fill);
					}
				} else{
					$element = substr($element, 1, -1);
					if($element{0}=='/'){
						$this->closedHTMLTagHandler(strtolower(substr($element, 1)));
					}
					else{
						preg_match('/([a-zA-Z0-9]*)/', $element, $tag);
						$tag = strtolower($tag[0]);
						preg_match_all('/([^=\s]*)=["\']?([^"\']*)["\']?/', $element, $attr_array, PREG_PATTERN_ORDER);
						$attr = array();
						while(list($id,$name)=each($attr_array[1])){
							$attr[strtolower($name)] = $attr_array[2][$id];
						}
						$this->openHTMLTagHandler($tag, $attr, $fill);
					}
				}
			}
			if($ln){
				$this->Ln($this->lasth);
			}
		}
		public function writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0){
			if(empty($this->lasth)){
				$this->lasth = $this->FontSize * K_CELL_HEIGHT_RATIO;
			}
			if(empty($x)){
				$x = $this->GetX();
			}
			if(empty($y)){
				$y = $this->GetY();
			}
			$pagenum = $this->page;
			$this->SetX($x);
			$this->SetY($y);
			if(empty($w)){
				$w = $this->w - $x - $this->rMargin;
			}
			$lMargin = $this->lMargin;
			$rMargin = $this->rMargin;
			$this->SetLeftMargin($x);
			$this->SetRightMargin($this->w - $x - $w);
			$restspace = $this->getPageHeight() - $this->GetY() - $this->getBreakMargin();
			$this->writeHTML($html, true, $fill);
			$currentY =  $this->GetY();
			if($this->page > $pagenum){
				$currentpage = $this->page;
				$this->page = $pagenum;
				$this->SetY($this->getPageHeight() - $restspace - $this->getBreakMargin());
				$h = $restspace - 1;
				$this->Cell($w, $h, "", $border, $ln, 'L', 0);
				$this->page = $currentpage;
				$h = $currentY - $this->tMargin;
				$this->SetY($this->tMargin);
				$this->Cell($w, $h, "", $border, $ln, 'L', 0);
			} else{
				$h = max($h,($currentY - $y));
				$this->SetY($y);
				$this->Cell($w, $h, "", $border, $ln, 'L', 0);
			}
			$this->SetLeftMargin($lMargin);
			$this->SetRightMargin($rMargin);
			if($ln){
				$this->Ln(0);
			}
		}
		private function openHTMLTagHandler($tag, $attr, $fill=0){
			switch($tag){
				case 'table':{
					if((isset($attr['border'])) AND($attr['border'] != '')){
						$this->tableborder = $attr['border'];
					}
					else{
						$this->tableborder = 0;
					}
					break;
				}
				case 'tr':{
					break;
				}
				case 'td':
				case 'th':{
					if((isset($attr['width'])) AND($attr['width'] != '')){
						$this->tdwidth =($attr['width']/4);
					}
					else{
						$this->tdwidth =(($this->w - $this->lMargin - $this->rMargin) / $this->default_table_columns);
					}
					if((isset($attr['height'])) AND($attr['height'] != '')){
						$this->tdheight=($attr['height'] / $this->k);
					}
					else{
						$this->tdheight = $this->lasth;
					}
					if((isset($attr['align'])) AND($attr['align'] != '')){
						switch($attr['align']){
							case 'center':{
								$this->tdalign = "C";
								break;
							}
							case 'right':{
								$this->tdalign = "R";
								break;
							}
							default:
							case 'left':{
								$this->tdalign = "L";
								break;
							}
						}
					}
					if((isset($attr['bgcolor'])) AND($attr['bgcolor'] != '')){
						$coul = $this->convertColorHexToDec($attr['bgcolor']);
						$this->SetFillColor($coul['R'], $coul['G'], $coul['B']);
						$this->tdbgcolor=true;
					}
					$this->tdbegin=true;
					break;
				}
				case 'hr':{
					$this->Ln();
					if((isset($attr['width'])) AND($attr['width'] != '')){
						$hrWidth = $attr['width'];
					}
					else{
						$hrWidth = $this->w - $this->lMargin - $this->rMargin;
					}
					$x = $this->GetX();
					$y = $this->GetY();
					$this->SetLineWidth(0.2);
					$this->Line($x, $y, $x + $hrWidth, $y);
					$this->SetLineWidth(0.2);
					$this->Ln();
					break;
				}
				case 'strong':{
					$this->setStyle('b', true);
					break;
				}
				case 'em':{
					$this->setStyle('i', true);
					break;
				}
				case 'b':
				case 'i':
				case 'u':{
					$this->setStyle($tag, true);
					break;
				}
				case 'a':{
					$this->HREF = $attr['href'];
					break;
				}
				case 'img':{
					if(isset($attr['src'])){
						$attr['src'] = str_replace(K_PATH_URL_CACHE, K_PATH_CACHE, $attr['src']);
						if(!isset($attr['width'])){
							$attr['width'] = 0;
						}
						if(!isset($attr['height'])){
							$attr['height'] = 0;
						}
						$this->Image($attr['src'], $this->GetX(),$this->GetY(), $this->pixelsToMillimeters($attr['width']), $this->pixelsToMillimeters($attr['height']));
						$this->SetY($this->img_rb_y);
					}
					break;
				}
				case 'ul':{
					$this->listordered = false;
					$this->listcount = 0;
					break;
				}
				case 'ol':{
					$this->listordered = true;
					$this->listcount = 0;
					break;
				}
				case 'li':{
					$this->Ln();
					if($this->listordered){
						$this->lispacer = "    ".(++$this->listcount).". ";
					}
					else{
						$this->lispacer = "    -  ";
					}
					$this->Write($this->lasth, $this->lispacer, '', $fill);
					break;
				}
				case 'blockquote':
				case 'br':{
					$this->Ln();
					if(strlen($this->lispacer) > 0){
						$this->x += $this->GetStringWidth($this->lispacer);
					}
					break;
				}
				case 'p':{
					$this->Ln();
					$this->Ln();
					break;
				}
				case 'sup':{
					$currentFontSize = $this->FontSize;
					$this->tempfontsize = $this->FontSizePt;
					$this->SetFontSize($this->FontSizePt * K_SMALL_RATIO);
					$this->SetXY($this->GetX(), $this->GetY() -(($currentFontSize - $this->FontSize)*(K_SMALL_RATIO)));
					break;
				}
				case 'sub':{
					$currentFontSize = $this->FontSize;
					$this->tempfontsize = $this->FontSizePt;
					$this->SetFontSize($this->FontSizePt * K_SMALL_RATIO);
					$this->SetXY($this->GetX(), $this->GetY() +(($currentFontSize - $this->FontSize)*(K_SMALL_RATIO)));
					break;
				}
				case 'small':{
					$currentFontSize = $this->FontSize;
					$this->tempfontsize = $this->FontSizePt;
					$this->SetFontSize($this->FontSizePt * K_SMALL_RATIO);
					$this->SetXY($this->GetX(), $this->GetY() +(($currentFontSize - $this->FontSize)/3));
					break;
				}
				case 'font':{
					if(isset($attr['color']) AND $attr['color']!=''){
						$coul = $this->convertColorHexToDec($attr['color']);
						$this->SetTextColor($coul['R'],$coul['G'],$coul['B']);
						$this->issetcolor=true;
					}
					if(isset($attr['face']) and in_array(strtolower($attr['face']), $this->fontlist)){
						$this->SetFont(strtolower($attr['face']));
						$this->issetfont=true;
					}
					if(isset($attr['size'])){
						$headsize = intval($attr['size']);
					} else{
						$headsize = 0;
					}
					$currentFontSize = $this->FontSize;
					$this->tempfontsize = $this->FontSizePt;
					$this->SetFontSize($this->FontSizePt + $headsize);
					$this->lasth = $this->FontSize * K_CELL_HEIGHT_RATIO;
					break;
				}
				case 'h1':
				case 'h2':
				case 'h3':
				case 'h4':
				case 'h5':
				case 'h6':{
					$headsize =(4 - substr($tag, 1)) * 2;
					$currentFontSize = $this->FontSize;
					$this->tempfontsize = $this->FontSizePt;
					$this->SetFontSize($this->FontSizePt + $headsize);
					$this->setStyle('b', true);
					$this->lasth = $this->FontSize * K_CELL_HEIGHT_RATIO;
					break;
				}
			}
		}
		private function closedHTMLTagHandler($tag){
			switch($tag){
				case 'td':
				case 'th':{
					$this->tdbegin = false;
					$this->tdwidth = 0;
					$this->tdheight = 0;
					$this->tdalign = "L";
					$this->tdbgcolor = false;
					$this->SetFillColor($this->prevFillColor[0], $this->prevFillColor[1], $this->prevFillColor[2]);
					break;
				}
				case 'tr':{
					$this->Ln();
					break;
				}
				case 'table':{
					$this->tableborder=0;
					break;
				}
				case 'strong':{
					$this->setStyle('b', false);
					break;
				}
				case 'em':{
					$this->setStyle('i', false);
					break;
				}
				case 'b':
				case 'i':
				case 'u':{
					$this->setStyle($tag, false);
					break;
				}
				case 'a':{
					$this->HREF = '';
					break;
				}
				case 'sup':{
					$currentFontSize = $this->FontSize;
					$this->SetFontSize($this->tempfontsize);
					$this->tempfontsize = $this->FontSizePt;
					$this->SetXY($this->GetX(), $this->GetY() -(($currentFontSize - $this->FontSize)*(K_SMALL_RATIO)));
					break;
				}
				case 'sub':{
					$currentFontSize = $this->FontSize;
					$this->SetFontSize($this->tempfontsize);
					$this->tempfontsize = $this->FontSizePt;
					$this->SetXY($this->GetX(), $this->GetY() +(($currentFontSize - $this->FontSize)*(K_SMALL_RATIO)));
					break;
				}
				case 'small':{
					$currentFontSize = $this->FontSize;
					$this->SetFontSize($this->tempfontsize);
					$this->tempfontsize = $this->FontSizePt;
					$this->SetXY($this->GetX(), $this->GetY() -(($this->FontSize - $currentFontSize)/3));
					break;
				}
				case 'font':{
					if($this->issetcolor == true){
						$this->SetTextColor($this->prevTextColor[0], $this->prevTextColor[1], $this->prevTextColor[2]);
					}
					if($this->issetfont){
						$this->FontFamily = $this->prevFontFamily;
						$this->FontStyle = $this->prevFontStyle;
						$this->SetFont($this->FontFamily);
						$this->issetfont = false;
					}
					$currentFontSize = $this->FontSize;
					$this->SetFontSize($this->tempfontsize);
					$this->tempfontsize = $this->FontSizePt;
					$this->lasth = $this->FontSize * K_CELL_HEIGHT_RATIO;
					break;
				}
				case 'ul':{
					$this->Ln();
					break;
				}
				case 'ol':{
					$this->Ln();
					break;
				}
				case 'li':{
					$this->lispacer = "";
					break;
				}
				case 'h1':
				case 'h2':
				case 'h3':
				case 'h4':
				case 'h5':
				case 'h6':{
					$currentFontSize = $this->FontSize;
					$this->SetFontSize($this->tempfontsize);
					$this->tempfontsize = $this->FontSizePt;
					$this->setStyle('b', false);
					$this->Ln();
					$this->lasth = $this->FontSize * K_CELL_HEIGHT_RATIO;
					break;
				}
				default :{
					break;
				}
			}
		}
		private function setStyle($tag, $enable){
			$this->$tag +=($enable ? 1 : -1);
			$style='';
			foreach(array('b', 'i', 'u') as $s){
				if($this->$s > 0){
					$style .= $s;
				}
			}
			$this->SetFont('', $style);
		}
		public function addHtmlLink($url, $name, $fill=0){
			$this->SetTextColor(0, 0, 255);
			$this->setStyle('u', true);
			$this->Write($this->lasth, $name, $url, $fill);
			$this->setStyle('u', false);
			$this->SetTextColor(0);
		}
		private function convertColorHexToDec($color = "#000000"){
			$tbl_color = array();
			$tbl_color['R'] = hexdec(substr($color, 1, 2));
			$tbl_color['G'] = hexdec(substr($color, 3, 2));
			$tbl_color['B'] = hexdec(substr($color, 5, 2));
			return $tbl_color;
		}
		private function pixelsToMillimeters($px){
			return $px * 25.4 / 72;
		}
		public function unhtmlentities($text_to_convert){
			return html_entity_decode($text_to_convert, ENT_QUOTES, $this->encoding);
		}
		private function justify($rowWidth, $txtWidth, $spaceWidth, $txt){
			$spaceCount = floor(($rowWidth - $txtWidth)/$spaceWidth);
			if($spaceCount <= 1){
				return $txt;
			}
			$hlptxt = split(" ", $txt);
			if($hlptxt === FALSE){
				return $txt;
			}
			$wordCount = count($hlptxt);
			if(floor($spaceCount/$wordCount) >= 10){
				return $txt;
			}
			if($spaceCount < $wordCount){
				$hlptxt[0] = $hlptxt[0]." ";
				for($i=1; $i<=$spaceCount; $i++){
					$hlptxt[$i] = " ".$hlptxt[$i];
				}
			} else{
				$count = 0;
				while($count < $spaceCount){
					$hlptxt[0] = $hlptxt[0]." ";
					for($i=1; $i < $wordCount; $i++){
						$hlptxt[$i] = " ".$hlptxt[$i];
						$count++;
						if($count >= $spaceCount){
							break;
						}
					}
				}
			}
			$out = "";
			foreach($hlptxt as $word){
				$out .= $word." ";
			}
			return $out;
		}
	}
	if(isset($_SERVER['HTTP_USER_AGENT']) AND($_SERVER['HTTP_USER_AGENT']=='contype')){
		header('Content-Type: application/pdf');
		exit;
	}
}
?>