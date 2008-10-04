<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage jtpl_plugin
* @author     Laurent Jouanneau
* @copyright  2005-2006 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence    GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
require_once(LIB_PATH.'diff/diffhtml.php');
function jtpl_function_html_diff($tpl, $str1,$str2, $nodiffmsg='Pas de différence')
{
	$diff = new Diff(explode("\n",$str1),explode("\n",$str2));
	if($diff->isEmpty()){
		echo $nodiffmsg;
	}else{
		$fmt = new HtmlUnifiedDiffFormatter();
		echo $fmt->format($diff);
	}
}