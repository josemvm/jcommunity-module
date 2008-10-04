<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* jMailer : based on PHPMailer - PHP email class
* Class for sending email using either
* sendmail, PHP mail(), or SMTP.  Methods are
* based upon the standard AspEmail(tm) classes.
*
* Original author : Brent R. Matzelle
* Adaptation for PHP5 And Jelix : Laurent Jouanneau
*
* @package     jelix
* @subpackage  utils
* @author      Brent R. Matzelle
* @contributor Laurent Jouanneau, Kévin Lepeltier
* @copyright   2001 - 2003  Brent R. Matzelle, 2006 Laurent Jouanneau
* @copyright   2008 Kévin Lepeltier
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
class jMailer{
	public $Priority		  = 3;
	public $CharSet		   = "iso-8859-1";
	public $ContentType		= "text/plain";
	public $Encoding		  = "8bit";
	public $From			   = "root@localhost";
	public $FromName		   = "";
	public $Sender			= "";
	public $Subject		   = "";
	public $Body			   = "";
	public $bodyTpl;
	public $AltBody		   = "";
	public $WordWrap		  = 0;
	public $Mailer			= "mail";
	public $Sendmail		  = "/usr/sbin/sendmail";
	public $Version		   = "1.73";
	public $ConfirmReadingTo  = "";
	public $Hostname		  = "";
	public $Host		= "localhost";
	public $Port		= 25;
	public $Helo		= "";
	public $SMTPAuth	 = false;
	public $Username	 = "";
	public $Password	 = "";
	public $Timeout	  = 10;
	public $SMTPDebug	= false;
	public $SMTPKeepAlive = false;
	protected $smtp			= NULL;
	protected $to			  = array();
	protected $cc			  = array();
	protected $bcc			 = array();
	protected $ReplyTo		 = array();
	protected $attachment	  = array();
	protected $CustomHeader	= array();
	protected $message_type	= "";
	protected $boundary		= array();
	protected $language		= array();
	protected $error_count	 = 0;
	protected $LE			  = "\n";
	function __construct(){
		global $gJConfig;
		$this->CharSet = $gJConfig->charset;
		$this->Mailer = $gJConfig->mailer['mailerType'];
		$this->Hostname = $gJConfig->mailer['hostname'];
		$this->Sendmail = $gJConfig->mailer['sendmailPath'];
		$this->Host = $gJConfig->mailer['smtpHost'];
		$this->Port = $gJConfig->mailer['smtpPort'];
		$this->Helo = $gJConfig->mailer['smtpHelo'];
		$this->SMTPAuth = $gJConfig->mailer['smtpAuth'];
		$this->Username = $gJConfig->mailer['smtpUsername'];
		$this->Password = $gJConfig->mailer['smtpPassword'];
		$this->Timeout = $gJConfig->mailer['smtpTimeout'];
		if($gJConfig->mailer['webmasterEmail'] != ''){
			$this->From = $gJConfig->mailer['webmasterEmail'];
		}
		$this->FromName = $gJConfig->mailer['webmasterName'];
	}
	function IsHTML($bool){
		if($bool == true)
			$this->ContentType = "text/html";
		else
			$this->ContentType = "text/plain";
	}
	function IsSMTP(){
		$this->Mailer = "smtp";
	}
	function IsMail(){
		$this->Mailer = "mail";
	}
	function IsSendmail(){
		$this->Mailer = "sendmail";
	}
	function IsQmail(){
		$this->Sendmail = "/var/qmail/bin/sendmail";
		$this->Mailer = "sendmail";
	}
	function AddAddress($address, $name = ""){
		$cur = count($this->to);
		$this->to[$cur][0] = trim($address);
		$this->to[$cur][1] = $name;
	}
	function AddCC($address, $name = ""){
		$cur = count($this->cc);
		$this->cc[$cur][0] = trim($address);
		$this->cc[$cur][1] = $name;
	}
	function AddBCC($address, $name = ""){
		$cur = count($this->bcc);
		$this->bcc[$cur][0] = trim($address);
		$this->bcc[$cur][1] = $name;
	}
	function AddReplyTo($address, $name = ""){
		$cur = count($this->ReplyTo);
		$this->ReplyTo[$cur][0] = trim($address);
		$this->ReplyTo[$cur][1] = $name;
	}
	function getAddrName($address){
		preg_match('`^([^<]*)<([^>]*)>$`', $address, $tab);
		array_shift($tab);
		return $tab;
	}
	function Tpl( $selector){
		$this->bodyTpl = $selector;
	}
	function Send(){
		$header = "";
		$body = "";
		$result = true;
		if( isset($this->bodyTpl) && $this->bodyTpl != ""){
			$mailtpl = new jTpl();
			$metas = $mailtpl->meta( $this->bodyTpl);
			if( isset($metas['Subject']))
				$this->Subject = $metas['Subject'];
			if( isset($metas['Priority']))
				$this->Priority = $metas['Priority'];
			$mailtpl->assign('Priority', $this->Priority);
			if( isset($metas['From'])){
				$adr = $this->getAddrName( $metas['From']);
				$this->From = $adr[1];
				$this->FromName = $adr[0];
			}
			$mailtpl->assign('From', $this->From);
			$mailtpl->assign('FromName', $this->FromName);
			if( isset($metas['Sender']))
				$this->Sender = $metas['Sender'];
			$mailtpl->assign('Sender', $this->Sender);
			if( isset($metas['to']))
				foreach( $metas['to'] as $val)
					$this->to[] = $this->getAddrName( $val);
			$mailtpl->assign('to', $this->to);
			if( isset($metas['cc']))
				foreach( $metas['cc'] as $val)
					$this->cc[] = $this->getAddrName( $val);
			$mailtpl->assign('cc', $this->cc);
			if( isset($metas['bcc']))
				foreach( $metas['bcc'] as $val)
					$this->bcc[] = $this->getAddrName( $val);
			$mailtpl->assign('bcc', $this->bcc);
			if( isset($metas['ReplyTo']))
				foreach( $metas['ReplyTo'] as $val)
					$this->ReplyTo[] = $this->getAddrName( $val);
			$mailtpl->assign('ReplyTo', $this->ReplyTo);
			$this->Body = $mailtpl->fetch( $this->bodyTpl);
		}
		if((count($this->to) + count($this->cc) + count($this->bcc)) < 1)
		{
			throw new jException('jelix~errors.mail.provide_address');
		}
		if(!empty($this->AltBody))
			$this->ContentType = "multipart/alternative";
		$this->error_count = 0;
		$this->SetMessageType();
		$header .= $this->CreateHeader();
		$body = $this->CreateBody();
		if($body == ""){ return false;}
		switch($this->Mailer)
		{
			case "sendmail":
				$result = $this->SendmailSend($header, $body);
				break;
			case "mail":
				$result = $this->MailSend($header, $body);
				break;
			case "smtp":
				$result = $this->SmtpSend($header, $body);
				break;
			default:
				throw new jException('jelix~errors.mail.mailer_not_supported', $this->Mailer);
				break;
		}
		return $result;
	}
	protected function SendmailSend($header, $body){
		if($this->Sender != "")
			$sendmail = sprintf("%s -oi -f %s -t", escapeshellcmd($this->Sendmail), escapeshellarg($this->Sender));
		else
			$sendmail = sprintf("%s -oi -t", escapeshellcmd($this->Sendmail));
		if(!@$mail = popen($sendmail, "w"))
		{
			throw new jException('jelix~errors.mail.execute', $this->Sendmail);
		}
		fputs($mail, $header);
		fputs($mail, $body);
		$result = pclose($mail) >> 8 & 0xFF;
		if($result != 0)
		{
			throw new jException('jelix~errors.mail.execute', $this->Sendmail);
		}
		return true;
	}
	protected function MailSend($header, $body){
		$to = "";
		for($i = 0; $i < count($this->to); $i++)
		{
			if($i != 0){ $to .= ", ";}
			$to .= $this->to[$i][0];
		}
		if($this->Sender != "" && strlen(ini_get("safe_mode"))< 1)
		{
			$old_from = ini_get("sendmail_from");
			ini_set("sendmail_from", $this->Sender);
			$params = sprintf("-oi -f %s", $this->Sender);
			$rt = @mail($to, $this->EncodeHeader($this->Subject), $body,
						$header, $params);
		}
		else
			$rt = @mail($to, $this->EncodeHeader($this->Subject), $body, $header);
		if(isset($old_from))
			ini_set("sendmail_from", $old_from);
		if(!$rt)
		{
			throw new jException('jelix~errors.mail.instantiate');
		}
		return true;
	}
	protected function SmtpSend($header, $body){
		require_once(JELIX_LIB_UTILS_PATH.'jSmtp.class.php');
		$bad_rcpt = array();
		if(!$this->SmtpConnect())
			return false;
		$smtp_from =($this->Sender == "") ? $this->From : $this->Sender;
		if(!$this->smtp->Mail($smtp_from))
		{
			$this->smtp->Reset();
			throw new jException('jelix~errors.mail.from_failed',$smtp_from);
		}
		for($i = 0; $i < count($this->to); $i++)
		{
			if(!$this->smtp->Recipient($this->to[$i][0]))
				$bad_rcpt[] = $this->to[$i][0];
		}
		for($i = 0; $i < count($this->cc); $i++)
		{
			if(!$this->smtp->Recipient($this->cc[$i][0]))
				$bad_rcpt[] = $this->cc[$i][0];
		}
		for($i = 0; $i < count($this->bcc); $i++)
		{
			if(!$this->smtp->Recipient($this->bcc[$i][0]))
				$bad_rcpt[] = $this->bcc[$i][0];
		}
		if(count($bad_rcpt) > 0)
		{
			$error = '';
			for($i = 0; $i < count($bad_rcpt); $i++)
			{
				if($i != 0){ $error .= ", ";}
				$error .= $bad_rcpt[$i];
			}
			$this->smtp->Reset();
			throw new jException('jelix~errors.mail.recipients_failed',$error);
		}
		if(!$this->smtp->Data($header . $body))
		{
			$this->smtp->Reset();
			throw new jException('jelix~errors.mail.data_not_accepted');
		}
		if($this->SMTPKeepAlive == true)
			$this->smtp->Reset();
		else
			$this->SmtpClose();
		return true;
	}
	protected function SmtpConnect(){
		if($this->smtp == NULL){ $this->smtp = new jSmtp();}
		$this->smtp->do_debug = $this->SMTPDebug;
		$hosts = explode(";", $this->Host);
		$index = 0;
		$connection =($this->smtp->Connected());
		while($index < count($hosts) && $connection == false)
		{
			if(strstr($hosts[$index], ":"))
				list($host, $port) = explode(":", $hosts[$index]);
			else
			{
				$host = $hosts[$index];
				$port = $this->Port;
			}
			if($this->smtp->Connect($host, $port, $this->Timeout))
			{
				if($this->Helo != '')
					$this->smtp->Hello($this->Helo);
				else
					$this->smtp->Hello($this->ServerHostname());
				if($this->SMTPAuth)
				{
					if(!$this->smtp->Authenticate($this->Username,
												  $this->Password))
					{
						$this->smtp->Reset();
						throw new jException('jelix~errors.mail.authenticate');
					}
				}
				$connection = true;
			}
			$index++;
		}
		if(!$connection)
			throw new jException('jelix~errors.mail.connect_host');
		return $connection;
	}
	public function SmtpClose(){
		if($this->smtp != NULL)
		{
			if($this->smtp->Connected())
			{
				$this->smtp->Quit();
				$this->smtp->Close();
			}
		}
	}
	protected function AddrAppend($type, $addr){
		$addr_str = $type . ": ";
		$addr_str .= $this->AddrFormat($addr[0]);
		if(count($addr) > 1)
		{
			for($i = 1; $i < count($addr); $i++)
				$addr_str .= ", " . $this->AddrFormat($addr[$i]);
		}
		$addr_str .= $this->LE;
		return $addr_str;
	}
	protected function AddrFormat($addr){
		if(empty($addr[1]))
			$formatted = $addr[0];
		else
		{
			$formatted = $this->EncodeHeader($addr[1], 'phrase') . " <" .
						 $addr[0] . ">";
		}
		return $formatted;
	}
	protected function WrapText($message, $length, $qp_mode = false){
		$soft_break =($qp_mode) ? sprintf(" =%s", $this->LE) : $this->LE;
		$message = $this->FixEOL($message);
		if(substr($message, -1) == $this->LE){
			$message = substr($message, 0, -1);
		}
		$line = explode($this->LE, $message);
		$message = '';
		for($i=0 ;$i < count($line); $i++){
			$line_part = explode(' ', $line[$i]);
			$buf = '';
			for($e = 0; $e<count($line_part); $e++){
				$word = $line_part[$e];
				if($qp_mode and(strlen($word) > $length)){
					$space_left = $length - strlen($buf) - 1;
					if($e != 0){
						if($space_left > 20){
							$len = $space_left;
							if(substr($word, $len - 1, 1) == '='){
								$len--;
							} elseif(substr($word, $len - 2, 1) == '='){
								$len -= 2;
							}
							$part = substr($word, 0, $len);
							$word = substr($word, $len);
							$buf .= ' ' . $part;
							$message .= $buf . sprintf("=%s", $this->LE);
						} else{
							$message .= $buf . $soft_break;
						}
						$buf = '';
					}
					while(strlen($word) > 0){
						$len = $length;
						if(substr($word, $len - 1, 1) == '='){
							$len--;
						} elseif(substr($word, $len - 2, 1) == '='){
							$len -= 2;
						}
						$part = substr($word, 0, $len);
						$word = substr($word, $len);
						if(strlen($word) > 0){
							$message .= $part . sprintf("=%s", $this->LE);
						} else{
							$buf = $part;
						}
					}
				} else{
					$buf_o = $buf;
					$buf .=($e == 0) ? $word :(' ' . $word);
					if(strlen($buf) > $length and $buf_o != ''){
						$message .= $buf_o . $soft_break;
						$buf = $word;
					}
				}
			}
			$message .= $buf . $this->LE;
		}
		return $message;
	}
	protected function SetWordWrap(){
		if($this->WordWrap < 1)
			return;
		switch($this->message_type)
		{
		   case "alt":
		   case "alt_attachments":
			  $this->AltBody = $this->WrapText($this->AltBody, $this->WordWrap);
			  break;
		   default:
			  $this->Body = $this->WrapText($this->Body, $this->WordWrap);
			  break;
		}
	}
	protected function CreateHeader(){
		$result = "";
		$uniq_id = md5(uniqid(time()));
		$this->boundary[1] = "b1_" . $uniq_id;
		$this->boundary[2] = "b2_" . $uniq_id;
		$result .= $this->HeaderLine("Date", $this->RFCDate());
		if($this->Sender == "")
			$result .= $this->HeaderLine("Return-Path", trim($this->From));
		else
			$result .= $this->HeaderLine("Return-Path", trim($this->Sender));
		if($this->Mailer != "mail")
		{
			if(count($this->to) > 0)
				$result .= $this->AddrAppend("To", $this->to);
			else if(count($this->cc) == 0)
				$result .= $this->HeaderLine("To", "undisclosed-recipients:;");
			if(count($this->cc) > 0)
				$result .= $this->AddrAppend("Cc", $this->cc);
		}
		$from = array();
		$from[0][0] = trim($this->From);
		$from[0][1] = $this->FromName;
		$result .= $this->AddrAppend("From", $from);
		if((($this->Mailer == "sendmail") ||($this->Mailer == "mail")) &&(count($this->bcc) > 0))
			$result .= $this->AddrAppend("Bcc", $this->bcc);
		if(count($this->ReplyTo) > 0)
			$result .= $this->AddrAppend("Reply-to", $this->ReplyTo);
		if($this->Mailer != "mail")
			$result .= $this->HeaderLine("Subject", $this->EncodeHeader(trim($this->Subject)));
		$result .= sprintf("Message-ID: <%s@%s>%s", $uniq_id, $this->ServerHostname(), $this->LE);
		$result .= $this->HeaderLine("X-Priority", $this->Priority);
		$result .= $this->HeaderLine("X-Mailer", "PHPMailer [version " . $this->Version . "]");
		if($this->ConfirmReadingTo != "")
		{
			$result .= $this->HeaderLine("Disposition-Notification-To",
					   "<" . trim($this->ConfirmReadingTo) . ">");
		}
		for($index = 0; $index < count($this->CustomHeader); $index++)
		{
			$result .= $this->HeaderLine(trim($this->CustomHeader[$index][0]),
					   $this->EncodeHeader(trim($this->CustomHeader[$index][1])));
		}
		$result .= $this->HeaderLine("MIME-Version", "1.0");
		switch($this->message_type)
		{
			case "plain":
				$result .= $this->HeaderLine("Content-Transfer-Encoding", $this->Encoding);
				$result .= sprintf("Content-Type: %s; charset=\"%s\"",
									$this->ContentType, $this->CharSet);
				break;
			case "attachments":
			case "alt_attachments":
				if($this->InlineImageExists())
				{
					$result .= sprintf("Content-Type: %s;%s\ttype=\"text/html\";%s\tboundary=\"%s\"%s",
									"multipart/related", $this->LE, $this->LE,
									$this->boundary[1], $this->LE);
				}
				else
				{
					$result .= $this->HeaderLine("Content-Type", "multipart/mixed;");
					$result .= $this->TextLine("\tboundary=\"" . $this->boundary[1] . '"');
				}
				break;
			case "alt":
				$result .= $this->HeaderLine("Content-Type", "multipart/alternative;");
				$result .= $this->TextLine("\tboundary=\"" . $this->boundary[1] . '"');
				break;
		}
		if($this->Mailer != "mail")
			$result .= $this->LE.$this->LE;
		return $result;
	}
	protected function CreateBody(){
		$result = "";
		$this->SetWordWrap();
		switch($this->message_type)
		{
			case "alt":
				$result .= $this->GetBoundary($this->boundary[1], "",
											  "text/plain", "");
				$result .= $this->EncodeString($this->AltBody, $this->Encoding);
				$result .= $this->LE.$this->LE;
				$result .= $this->GetBoundary($this->boundary[1], "",
											  "text/html", "");
				$result .= $this->EncodeString($this->Body, $this->Encoding);
				$result .= $this->LE.$this->LE;
				$result .= $this->EndBoundary($this->boundary[1]);
				break;
			case "plain":
				$result .= $this->EncodeString($this->Body, $this->Encoding);
				break;
			case "attachments":
				$result .= $this->GetBoundary($this->boundary[1], "", "", "");
				$result .= $this->EncodeString($this->Body, $this->Encoding);
				$result .= $this->LE;
				$result .= $this->AttachAll();
				break;
			case "alt_attachments":
				$result .= sprintf("--%s%s", $this->boundary[1], $this->LE);
				$result .= sprintf("Content-Type: %s;%s" .
								   "\tboundary=\"%s\"%s",
								   "multipart/alternative", $this->LE,
								   $this->boundary[2], $this->LE.$this->LE);
				$result .= $this->GetBoundary($this->boundary[2], "",
											  "text/plain", "") . $this->LE;
				$result .= $this->EncodeString($this->AltBody, $this->Encoding);
				$result .= $this->LE.$this->LE;
				$result .= $this->GetBoundary($this->boundary[2], "",
											  "text/html", "") . $this->LE;
				$result .= $this->EncodeString($this->Body, $this->Encoding);
				$result .= $this->LE.$this->LE;
				$result .= $this->EndBoundary($this->boundary[2]);
				$result .= $this->AttachAll();
				break;
		}
		return $result;
	}
	protected function GetBoundary($boundary, $charSet, $contentType, $encoding){
		$result = "";
		if($charSet == ""){ $charSet = $this->CharSet;}
		if($contentType == ""){ $contentType = $this->ContentType;}
		if($encoding == ""){ $encoding = $this->Encoding;}
		$result .= $this->TextLine("--" . $boundary);
		$result .= sprintf("Content-Type: %s; charset = \"%s\"",
							$contentType, $charSet);
		$result .= $this->LE;
		$result .= $this->HeaderLine("Content-Transfer-Encoding", $encoding);
		$result .= $this->LE;
		return $result;
	}
	protected function EndBoundary($boundary){
		return $this->LE . "--" . $boundary . "--" . $this->LE;
	}
	protected function SetMessageType(){
		if(count($this->attachment) || strlen($this->AltBody)){
			if(count($this->attachment) > 0)
				$this->message_type = "attachments";
			if(strlen($this->AltBody) > 0 && count($this->attachment) < 1)
				$this->message_type = "alt";
			if(strlen($this->AltBody) > 0 && count($this->attachment) > 0)
				$this->message_type = "alt_attachments";
		}else
			$this->message_type = "plain";
	}
	protected function HeaderLine($name, $value){
		return $name . ": " . $value . $this->LE;
	}
	protected function TextLine($value){
		return $value . $this->LE;
	}
	function AddAttachment($path, $name = "", $encoding = "base64",
						   $type = "application/octet-stream"){
		if(!@is_file($path))
		{
			throw new jException('jelix~errors.mail.file_access',$path);
		}
		$filename = basename($path);
		if($name == "")
			$name = $filename;
		$cur = count($this->attachment);
		$this->attachment[$cur][0] = $path;
		$this->attachment[$cur][1] = $filename;
		$this->attachment[$cur][2] = $name;
		$this->attachment[$cur][3] = $encoding;
		$this->attachment[$cur][4] = $type;
		$this->attachment[$cur][5] = false;
		$this->attachment[$cur][6] = "attachment";
		$this->attachment[$cur][7] = 0;
		return true;
	}
	protected function AttachAll(){
		$mime = array();
		for($i = 0; $i < count($this->attachment); $i++)
		{
			$bString = $this->attachment[$i][5];
			if($bString)
				$string = $this->attachment[$i][0];
			else
				$path = $this->attachment[$i][0];
			$filename	= $this->attachment[$i][1];
			$name		= $this->attachment[$i][2];
			$encoding	= $this->attachment[$i][3];
			$type		= $this->attachment[$i][4];
			$disposition = $this->attachment[$i][6];
			$cid		 = $this->attachment[$i][7];
			$mime[] = sprintf("--%s%s", $this->boundary[1], $this->LE);
			$mime[] = sprintf("Content-Type: %s; name=\"%s\"%s", $type, $name, $this->LE);
			$mime[] = sprintf("Content-Transfer-Encoding: %s%s", $encoding, $this->LE);
			if($disposition == "inline")
				$mime[] = sprintf("Content-ID: <%s>%s", $cid, $this->LE);
			$mime[] = sprintf("Content-Disposition: %s; filename=\"%s\"%s",
							  $disposition, $name, $this->LE.$this->LE);
			if($bString)
			{
				$mime[] = $this->EncodeString($string, $encoding);
				$mime[] = $this->LE.$this->LE;
			}
			else
			{
				$mime[] = $this->EncodeFile($path, $encoding);
				$mime[] = $this->LE.$this->LE;
			}
		}
		$mime[] = sprintf("--%s--%s", $this->boundary[1], $this->LE);
		return join("", $mime);
	}
	protected function EncodeFile($path, $encoding = "base64"){
		if(!@$fd = fopen($path, "rb"))
		{
			throw new jException('jelix~errors.mail.file_open',$path);
		}
		$magic_quotes = get_magic_quotes_runtime();
		set_magic_quotes_runtime(0);
		$file_buffer = fread($fd, filesize($path));
		$file_buffer = $this->EncodeString($file_buffer, $encoding);
		fclose($fd);
		set_magic_quotes_runtime($magic_quotes);
		return $file_buffer;
	}
	protected function EncodeString($str, $encoding = "base64"){
		$encoded = "";
		switch(strtolower($encoding)){
		  case "base64":
			  $encoded = chunk_split(base64_encode($str), 76, $this->LE);
			  break;
		  case "7bit":
		  case "8bit":
			  $encoded = $this->FixEOL($str);
			  if(substr($encoded, -(strlen($this->LE))) != $this->LE)
				$encoded .= $this->LE;
			  break;
		  case "binary":
			  $encoded = $str;
			  break;
		  case "quoted-printable":
			  $encoded = $this->EncodeQP($str);
			  break;
		  default:
			  throw new jException('jelix~errors.mail.encoding',$encoding);
		}
		return $encoded;
	}
	protected function EncodeHeader($str, $position = 'text'){
	  $x = 0;
	  switch(strtolower($position)){
		case 'phrase':
		  if(!preg_match('/[\200-\377]/', $str)){
			$encoded = addcslashes($str, "\0..\37\177\\\"");
			if(($str == $encoded) && !preg_match('/[^A-Za-z0-9!#$%&\'*+\/=?^_`{|}~ -]/', $str))
			  return($encoded);
			else
			  return("\"$encoded\"");
		  }
		  $x = preg_match_all('/[^\040\041\043-\133\135-\176]/', $str, $matches);
		  break;
		case 'comment':
		  $x = preg_match_all('/[()"]/', $str, $matches);
		case 'text':
		default:
		  $x += preg_match_all('/[\000-\010\013\014\016-\037\177-\377]/', $str, $matches);
		  break;
	  }
	  if($x == 0)
		return($str);
	  $maxlen = 75 - 7 - strlen($this->CharSet);
	  if(strlen($str)/3 < $x){
		$encoding = 'B';
		$encoded = base64_encode($str);
		$maxlen -= $maxlen % 4;
		$encoded = trim(chunk_split($encoded, $maxlen, "\n"));
	  } else{
		$encoding = 'Q';
		$encoded = $this->EncodeQ($str, $position);
		$encoded = $this->WrapText($encoded, $maxlen, true);
		$encoded = str_replace("=".$this->LE, "\n", trim($encoded));
	  }
	  $encoded = preg_replace('/^(.*)$/m', " =?".$this->CharSet."?$encoding?\\1?=", $encoded);
	  $encoded = trim(str_replace("\n", $this->LE, $encoded));
	  return $encoded;
	}
	protected function EncodeQP($str){
		$encoded = $this->FixEOL($str);
		if(substr($encoded, -(strlen($this->LE))) != $this->LE)
			$encoded .= $this->LE;
		$encoded = preg_replace('/([\000-\010\013\014\016-\037\075\177-\377])/e',
				  "'='.sprintf('%02X', ord('\\1'))", $encoded);
		$encoded = preg_replace("/([\011\040])".$this->LE."/e",
				  "'='.sprintf('%02X', ord('\\1')).'".$this->LE."'", $encoded);
		$encoded = $this->WrapText($encoded, 74, true);
		return $encoded;
	}
	protected function EncodeQ($str, $position = "text"){
		$encoded = preg_replace("[\r\n]", "", $str);
		switch(strtolower($position)){
		  case "phrase":
			$encoded = preg_replace("/([^A-Za-z0-9!*+\/ -])/e", "'='.sprintf('%02X', ord('\\1'))", $encoded);
			break;
		  case "comment":
			$encoded = preg_replace("/([\(\)\"])/e", "'='.sprintf('%02X', ord('\\1'))", $encoded);
		  case "text":
		  default:
			$encoded = preg_replace('/([\000-\011\013\014\016-\037\075\077\137\177-\377])/e',
				  "'='.sprintf('%02X', ord('\\1'))", $encoded);
			break;
		}
		$encoded = str_replace(" ", "_", $encoded);
		return $encoded;
	}
	public function AddStringAttachment($string, $filename, $encoding = "base64",
								 $type = "application/octet-stream"){
		$cur = count($this->attachment);
		$this->attachment[$cur][0] = $string;
		$this->attachment[$cur][1] = $filename;
		$this->attachment[$cur][2] = $filename;
		$this->attachment[$cur][3] = $encoding;
		$this->attachment[$cur][4] = $type;
		$this->attachment[$cur][5] = true;
		$this->attachment[$cur][6] = "attachment";
		$this->attachment[$cur][7] = 0;
	}
	public function AddEmbeddedImage($path, $cid, $name = "", $encoding = "base64",
							  $type = "application/octet-stream"){
		if(!@is_file($path))
		{
			throw new jException('jelix~errors.mail.file_access',$path);
		}
		$filename = basename($path);
		if($name == "")
			$name = $filename;
		$cur = count($this->attachment);
		$this->attachment[$cur][0] = $path;
		$this->attachment[$cur][1] = $filename;
		$this->attachment[$cur][2] = $name;
		$this->attachment[$cur][3] = $encoding;
		$this->attachment[$cur][4] = $type;
		$this->attachment[$cur][5] = false;
		$this->attachment[$cur][6] = "inline";
		$this->attachment[$cur][7] = $cid;
		return true;
	}
	protected function InlineImageExists(){
		$result = false;
		for($i = 0; $i < count($this->attachment); $i++)
		{
			if($this->attachment[$i][6] == "inline")
			{
				$result = true;
				break;
			}
		}
		return $result;
	}
	function ClearAddresses(){
		$this->to = array();
	}
	function ClearCCs(){
		$this->cc = array();
	}
	function ClearBCCs(){
		$this->bcc = array();
	}
	function ClearReplyTos(){
		$this->ReplyTo = array();
	}
	function ClearAllRecipients(){
		$this->to = array();
		$this->cc = array();
		$this->bcc = array();
	}
	function ClearAttachments(){
		$this->attachment = array();
	}
	function ClearCustomHeaders(){
		$this->CustomHeader = array();
	}
	protected function RFCDate(){
		$tz = date("Z");
		$tzs =($tz < 0) ? "-" : "+";
		$tz = abs($tz);
		$tz =($tz/3600)*100 +($tz%3600)/60;
		$result = sprintf("%s %s%04d", date("D, j M Y H:i:s"), $tzs, $tz);
		return $result;
	}
	protected function ServerHostname(){
		if($this->Hostname != "")
			$result = $this->Hostname;
		elseif(isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] != "")
			$result = $_SERVER['SERVER_NAME'];
		else
			$result = "localhost.localdomain";
		return $result;
	}
	protected function FixEOL($str){
		$str = str_replace("\r\n", "\n", $str);
		$str = str_replace("\r", "\n", $str);
		$str = str_replace("\n", $this->LE, $str);
		return $str;
	}
	function AddCustomHeader($custom_header){
		$this->CustomHeader[] = explode(":", $custom_header, 2);
	}
}