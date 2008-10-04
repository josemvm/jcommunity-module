<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* jSmtp, based on SMTP, a  PHP SMTP class by Chris Ryan
* Adaptation for PHP5 And Jelix : Laurent Jouanneau
*
* Original Version 1.02
* Define an SMTP class that can be used to connect
* and communicate with any SMTP server. It implements
* all the SMTP functions defined in RFC821 except TURN.
*
* @package     jelix
* @subpackage  utils
* @author      Chris Ryan
* @contributor Laurent Jouanneau
* @copyright   Chris Ryan,  2006 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
class jSmtp{
	public $SMTP_PORT = 25;
	public $CRLF = "\r\n";
	public $do_debug;
	private $smtp_conn;
	private $error;
	private $helo_rply;
	function __construct(){
		$this->smtp_conn = 0;
		$this->error = null;
		$this->helo_rply = null;
		$this->do_debug = 0;
	}
	function Connect($host,$port=0,$tval=30){
		$this->error = null;
		if($this->connected()){
			$this->error =
				array("error" => "Already connected to a server");
			return false;
		}
		if(empty($port)){
			$port = $this->SMTP_PORT;
		}
		$this->smtp_conn = fsockopen($host,
									 $port,
									 $errno,
									 $errstr,
									 $tval);
		if(empty($this->smtp_conn)){
			$this->error = array("error" => "Failed to connect to server",
								 "errno" => $errno,
								 "errstr" => $errstr);
			if($this->do_debug >= 1){
				jLog::log("SMTP -> ERROR: " . $this->error["error"] .
						 ": $errstr ($errno)");
			}
			return false;
		}
		if(substr(PHP_OS, 0, 3) != "WIN")
		   socket_set_timeout($this->smtp_conn, $tval, 0);
		$announce = $this->get_lines();
		if($this->do_debug >= 2){
			jLog::log( "SMTP -> FROM SERVER:". $announce);
		}
		return true;
	}
	function Authenticate($username, $password){
		fputs($this->smtp_conn,"AUTH LOGIN" . $this->CRLF);
		$rply = $this->get_lines();
		$code = substr($rply,0,3);
		if($code != 334){
			$this->error =
				array("error" => "AUTH not accepted from server",
					  "smtp_code" => $code,
					  "smtp_msg" => substr($rply,4));
			if($this->do_debug >= 1){
				jLog::log( "SMTP -> ERROR: " . $this->error["error"] .
						 ": " . $rply);
			}
			return false;
		}
		fputs($this->smtp_conn, base64_encode($username) . $this->CRLF);
		$rply = $this->get_lines();
		$code = substr($rply,0,3);
		if($code != 334){
			$this->error =
				array("error" => "Username not accepted from server",
					  "smtp_code" => $code,
					  "smtp_msg" => substr($rply,4));
			if($this->do_debug >= 1){
				jLog::log( "SMTP -> ERROR: " . $this->error["error"] .
						 ": " . $rply);
			}
			return false;
		}
		fputs($this->smtp_conn, base64_encode($password) . $this->CRLF);
		$rply = $this->get_lines();
		$code = substr($rply,0,3);
		if($code != 235){
			$this->error =
				array("error" => "Password not accepted from server",
					  "smtp_code" => $code,
					  "smtp_msg" => substr($rply,4));
			if($this->do_debug >= 1){
				jLog::log( "SMTP -> ERROR: " . $this->error["error"] .
						 ": " . $rply);
			}
			return false;
		}
		return true;
	}
	function Connected(){
		if(!empty($this->smtp_conn)){
			$sock_status = socket_get_status($this->smtp_conn);
			if($sock_status["eof"]){
				if($this->do_debug >= 1){
					jLog::log( "SMTP -> NOTICE: EOF caught while checking if connected");
				}
				$this->Close();
				return false;
			}
			return true;
		}
		return false;
	}
	function Close(){
		$this->error = null;
		$this->helo_rply = null;
		if(!empty($this->smtp_conn)){
			fclose($this->smtp_conn);
			$this->smtp_conn = 0;
		}
	}
	function Data($msg_data){
		$this->error = null;
		if(!$this->connected()){
			$this->error = array(
					"error" => "Called Data() without being connected");
			return false;
		}
		fputs($this->smtp_conn,"DATA" . $this->CRLF);
		$rply = $this->get_lines();
		$code = substr($rply,0,3);
		if($this->do_debug >= 2){
			jLog::log( "SMTP -> FROM SERVER:" . $rply);
		}
		if($code != 354){
			$this->error =
				array("error" => "DATA command not accepted from server",
					  "smtp_code" => $code,
					  "smtp_msg" => substr($rply,4));
			if($this->do_debug >= 1){
				jLog::log( "SMTP -> ERROR: " . $this->error["error"] .": " . $rply);
			}
			return false;
		}
		$msg_data = str_replace("\r\n","\n",$msg_data);
		$msg_data = str_replace("\r","\n",$msg_data);
		$lines = explode("\n",$msg_data);
		$field = substr($lines[0],0,strpos($lines[0],":"));
		$in_headers = false;
		if(!empty($field) && !strstr($field," ")){
			$in_headers = true;
		}
		$max_line_length = 998;
		while(list(,$line) = @each($lines)){
			$lines_out = null;
			if($line == "" && $in_headers){
				$in_headers = false;
			}
			while(strlen($line) > $max_line_length){
				$pos = strrpos(substr($line,0,$max_line_length)," ");
				if(!$pos){
					$pos = $max_line_length - 1;
				}
				$lines_out[] = substr($line,0,$pos);
				$line = substr($line,$pos + 1);
				if($in_headers){
					$line = "\t" . $line;
				}
			}
			$lines_out[] = $line;
			while(list(,$line_out) = @each($lines_out)){
				if(strlen($line_out) > 0)
				{
					if(substr($line_out, 0, 1) == "."){
						$line_out = "." . $line_out;
					}
				}
				fputs($this->smtp_conn,$line_out . $this->CRLF);
			}
		}
		fputs($this->smtp_conn, $this->CRLF . "." . $this->CRLF);
		$rply = $this->get_lines();
		$code = substr($rply,0,3);
		if($this->do_debug >= 2){
			jLog::log( "SMTP -> FROM SERVER:" . $rply);
		}
		if($code != 250){
			$this->error =
				array("error" => "DATA not accepted from server",
					  "smtp_code" => $code,
					  "smtp_msg" => substr($rply,4));
			if($this->do_debug >= 1){
				jLog::log( "SMTP -> ERROR: " . $this->error["error"] . ": " . $rply);
			}
			return false;
		}
		return true;
	}
	function Expand($name){
		$this->error = null;
		if(!$this->connected()){
			$this->error = array(
					"error" => "Called Expand() without being connected");
			return false;
		}
		fputs($this->smtp_conn,"EXPN " . $name . $this->CRLF);
		$rply = $this->get_lines();
		$code = substr($rply,0,3);
		if($this->do_debug >= 2){
			jLog::log( "SMTP -> FROM SERVER:". $rply);
		}
		if($code != 250){
			$this->error =
				array("error" => "EXPN not accepted from server",
					  "smtp_code" => $code,
					  "smtp_msg" => substr($rply,4));
			if($this->do_debug >= 1){
				jLog::log( "SMTP -> ERROR: " . $this->error["error"] .": " . $rply);
			}
			return false;
		}
		$entries = explode($this->CRLF,$rply);
		while(list(,$l) = @each($entries)){
			$list[] = substr($l,4);
		}
		return $list;
	}
	function Hello($host=""){
		$this->error = null;
		if(!$this->connected()){
			$this->error = array(
					"error" => "Called Hello() without being connected");
			return false;
		}
		if(empty($host)){
			$host = "localhost";
		}
		if(!$this->SendHello("EHLO", $host))
		{
			if(!$this->SendHello("HELO", $host))
				return false;
		}
		return true;
	}
	function SendHello($hello, $host){
		fputs($this->smtp_conn, $hello . " " . $host . $this->CRLF);
		$rply = $this->get_lines();
		$code = substr($rply,0,3);
		if($this->do_debug >= 2){
			jLog::log( "SMTP -> FROM SERVER: "  . $rply);
		}
		if($code != 250){
			$this->error =
				array("error" => $hello . " not accepted from server",
					  "smtp_code" => $code,
					  "smtp_msg" => substr($rply,4));
			if($this->do_debug >= 1){
				jLog::log( "SMTP -> ERROR: " . $this->error["error"] . ": " . $rply);
			}
			return false;
		}
		$this->helo_rply = $rply;
		return true;
	}
	function Help($keyword=""){
		$this->error = null;
		if(!$this->connected()){
			$this->error = array(
					"error" => "Called Help() without being connected");
			return false;
		}
		$extra = "";
		if(!empty($keyword)){
			$extra = " " . $keyword;
		}
		fputs($this->smtp_conn,"HELP" . $extra . $this->CRLF);
		$rply = $this->get_lines();
		$code = substr($rply,0,3);
		if($this->do_debug >= 2){
			jLog::log( "SMTP -> FROM SERVER:"  . $rply);
		}
		if($code != 211 && $code != 214){
			$this->error =
				array("error" => "HELP not accepted from server",
					  "smtp_code" => $code,
					  "smtp_msg" => substr($rply,4));
			if($this->do_debug >= 1){
				jLog::log( "SMTP -> ERROR: " . $this->error["error"] . ": " . $rply);
			}
			return false;
		}
		return $rply;
	}
	function Mail($from){
		$this->error = null;
		if(!$this->connected()){
			$this->error = array(
					"error" => "Called Mail() without being connected");
			return false;
		}
		fputs($this->smtp_conn,"MAIL FROM:<" . $from . ">" . $this->CRLF);
		$rply = $this->get_lines();
		$code = substr($rply,0,3);
		if($this->do_debug >= 2){
			jLog::log( "SMTP -> FROM SERVER:"  . $rply);
		}
		if($code != 250){
			$this->error =
				array("error" => "MAIL not accepted from server",
					  "smtp_code" => $code,
					  "smtp_msg" => substr($rply,4));
			if($this->do_debug >= 1){
				jLog::log( "SMTP -> ERROR: " . $this->error["error"] . ": " . $rply);
			}
			return false;
		}
		return true;
	}
	function Noop(){
		$this->error = null;
		if(!$this->connected()){
			$this->error = array(
					"error" => "Called Noop() without being connected");
			return false;
		}
		fputs($this->smtp_conn,"NOOP" . $this->CRLF);
		$rply = $this->get_lines();
		$code = substr($rply,0,3);
		if($this->do_debug >= 2){
			jLog::log( "SMTP -> FROM SERVER:" . $rply);
		}
		if($code != 250){
			$this->error =
				array("error" => "NOOP not accepted from server",
					  "smtp_code" => $code,
					  "smtp_msg" => substr($rply,4));
			if($this->do_debug >= 1){
				jLog::log( "SMTP -> ERROR: " . $this->error["error"] .  ": " . $rply);
			}
			return false;
		}
		return true;
	}
	function Quit($close_on_error=true){
		$this->error = null;
		if(!$this->connected()){
			$this->error = array(
					"error" => "Called Quit() without being connected");
			return false;
		}
		fputs($this->smtp_conn,"quit" . $this->CRLF);
		$byemsg = $this->get_lines();
		if($this->do_debug >= 2){
			jLog::log( "SMTP -> FROM SERVER:" .  $byemsg);
		}
		$rval = true;
		$e = null;
		$code = substr($byemsg,0,3);
		if($code != 221){
			$e = array("error" => "SMTP server rejected quit command",
					   "smtp_code" => $code,
					   "smtp_rply" => substr($byemsg,4));
			$rval = false;
			if($this->do_debug >= 1){
				jLog::log( "SMTP -> ERROR: " . $e["error"] . ": " . $byemsg);
			}
		}
		if(empty($e) || $close_on_error){
			$this->Close();
		}
		return $rval;
	}
	function Recipient($to){
		$this->error = null;
		if(!$this->connected()){
			$this->error = array(
					"error" => "Called Recipient() without being connected");
			return false;
		}
		fputs($this->smtp_conn,"RCPT TO:<" . $to . ">" . $this->CRLF);
		$rply = $this->get_lines();
		$code = substr($rply,0,3);
		if($this->do_debug >= 2){
			jLog::log( "SMTP -> FROM SERVER:" . $rply);
		}
		if($code != 250 && $code != 251){
			$this->error =
				array("error" => "RCPT not accepted from server",
					  "smtp_code" => $code,
					  "smtp_msg" => substr($rply,4));
			if($this->do_debug >= 1){
				jLog::log("SMTP -> ERROR: " . $this->error["error"] . ": " . $rply);
			}
			return false;
		}
		return true;
	}
	function Reset(){
		$this->error = null;
		if(!$this->connected()){
			$this->error = array(
					"error" => "Called Reset() without being connected");
			return false;
		}
		fputs($this->smtp_conn,"RSET" . $this->CRLF);
		$rply = $this->get_lines();
		$code = substr($rply,0,3);
		if($this->do_debug >= 2){
			jLog::log( "SMTP -> FROM SERVER:" . $rply);
		}
		if($code != 250){
			$this->error =
				array("error" => "RSET failed",
					  "smtp_code" => $code,
					  "smtp_msg" => substr($rply,4));
			if($this->do_debug >= 1){
				jLog::log( "SMTP -> ERROR: " . $this->error["error"] . ": " . $rply);
			}
			return false;
		}
		return true;
	}
	function Send($from){
		$this->error = null;
		if(!$this->connected()){
			$this->error = array(
					"error" => "Called Send() without being connected");
			return false;
		}
		fputs($this->smtp_conn,"SEND FROM:" . $from . $this->CRLF);
		$rply = $this->get_lines();
		$code = substr($rply,0,3);
		if($this->do_debug >= 2){
			jLog::log( "SMTP -> FROM SERVER:" . $rply);
		}
		if($code != 250){
			$this->error =
				array("error" => "SEND not accepted from server",
					  "smtp_code" => $code,
					  "smtp_msg" => substr($rply,4));
			if($this->do_debug >= 1){
				jLog::log( "SMTP -> ERROR: " . $this->error["error"] . ": " . $rply);
			}
			return false;
		}
		return true;
	}
	function SendAndMail($from){
		$this->error = null;
		if(!$this->connected()){
			$this->error = array(
				"error" => "Called SendAndMail() without being connected");
			return false;
		}
		fputs($this->smtp_conn,"SAML FROM:" . $from . $this->CRLF);
		$rply = $this->get_lines();
		$code = substr($rply,0,3);
		if($this->do_debug >= 2){
			jLog::log( "SMTP -> FROM SERVER:" . $rply);
		}
		if($code != 250){
			$this->error =
				array("error" => "SAML not accepted from server",
					  "smtp_code" => $code,
					  "smtp_msg" => substr($rply,4));
			if($this->do_debug >= 1){
				jLog::log( "SMTP -> ERROR: " . $this->error["error"] . ": " . $rply);
			}
			return false;
		}
		return true;
	}
	function SendOrMail($from){
		$this->error = null;
		if(!$this->connected()){
			$this->error = array(
				"error" => "Called SendOrMail() without being connected");
			return false;
		}
		fputs($this->smtp_conn,"SOML FROM:" . $from . $this->CRLF);
		$rply = $this->get_lines();
		$code = substr($rply,0,3);
		if($this->do_debug >= 2){
			jLog::log( "SMTP -> FROM SERVER:" . $rply);
		}
		if($code != 250){
			$this->error =
				array("error" => "SOML not accepted from server",
					  "smtp_code" => $code,
					  "smtp_msg" => substr($rply,4));
			if($this->do_debug >= 1){
				jLog::log( "SMTP -> ERROR: " . $this->error["error"] .": " . $rply);
			}
			return false;
		}
		return true;
	}
	function Turn(){
		$this->error = array("error" => "This method, TURN, of the SMTP ".
										"is not implemented");
		if($this->do_debug >= 1){
			jLog::log( "SMTP -> NOTICE: " . $this->error["error"]);
		}
		return false;
	}
	function Verify($name){
		$this->error = null;
		if(!$this->connected()){
			$this->error = array(
					"error" => "Called Verify() without being connected");
			return false;
		}
		fputs($this->smtp_conn,"VRFY " . $name . $this->CRLF);
		$rply = $this->get_lines();
		$code = substr($rply,0,3);
		if($this->do_debug >= 2){
			jLog::log( "SMTP -> FROM SERVER:". $rply);
		}
		if($code != 250 && $code != 251){
			$this->error =
				array("error" => "VRFY failed on name '$name'",
					  "smtp_code" => $code,
					  "smtp_msg" => substr($rply,4));
			if($this->do_debug >= 1){
				jLog::log( "SMTP -> ERROR: " . $this->error["error"] . ": " . $rply);
			}
			return false;
		}
		return $rply;
	}
	protected function get_lines(){
		$data = "";
		while($str = fgets($this->smtp_conn,515)){
			if($this->do_debug >= 4){
				jLog::log( "SMTP -> get_lines(): \$data was \"$data\"");
				jLog::log( "SMTP -> get_lines(): \$str is \"$str\"");
			}
			$data .= $str;
			if($this->do_debug >= 4){
				jLog::log( "SMTP -> get_lines(): \$data is \"$data\"");
			}
			if(substr($str,3,1) == " "){ break;}
		}
		return $data;
	}
}