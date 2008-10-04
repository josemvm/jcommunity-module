<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  acl
* @author      Laurent Jouanneau
* @copyright   2006-2008 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
* @since 1.1
*/
class jAcl2DbManager{
	private function __construct(){}
	public static function addRight($group, $subject, $resource=''){
		$profil = jAcl2Db::getProfil();
		$sbj = jDao::get('jelix~jacl2subject', $profil)->get($subject);
		if(!$sbj) return false;
		if($resource === null) $resource='';
		$daoright = jDao::get('jelix~jacl2rights', $profil);
		$right = $daoright->get($subject,$group,$resource);
		if(!$right){
			$right = jDao::createRecord('jelix~jacl2rights', $profil);
			$right->id_aclsbj = $subject;
			$right->id_aclgrp = $group;
			$right->id_aclres = $resource;
			$daoright->insert($right);
		}
		jAcl2::clearCache();
		return true;
	}
	public static function removeRight($group, $subject, $resource=''){
		if($resource === null) $resource='';
		jDao::get('jelix~jacl2rights', jAcl2Db::getProfil())
			->delete($subject,$group,$resource);
		jAcl2::clearCache();
	}
	public static function setRightsOnGroup($group, $rights){
		$dao = jDao::get('jelix~jacl2rights', jAcl2Db::getProfil());
		$dao->deleteByGroup($group);
		foreach($rights as $sbj=>$val){
			if($val != '')
			  self::addRight($group,$sbj);
		}
		jAcl2::clearCache();
	}
	public static function removeResourceRight($subject, $resource){
		jDao::get('jelix~jacl2rights', jAcl2Db::getProfil())->deleteBySubjRes($subject, $resource);
		jAcl2::clearCache();
	}
	public static function addSubject($subject, $label_key){
		$p = jAcl2Db::getProfil();
		$subj = jDao::createRecord('jelix~jacl2subject',$p);
		$subj->id_aclsbj=$subject;
		$subj->label_key =$label_key;
		jDao::get('jelix~jacl2subject',$p)->insert($subj);
		jAcl2::clearCache();
	}
	public static function removeSubject($subject){
		$p = jAcl2Db::getProfil();
		jDao::get('jelix~jacl2rights',$p)->deleteBySubject($subject);
		jDao::get('jelix~jacl2subject',$p)->delete($subject);
		jAcl2::clearCache();
	}
}