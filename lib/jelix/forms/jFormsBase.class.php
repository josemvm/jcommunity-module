<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  forms
* @author      Laurent Jouanneau
* @contributor Dominique Papin
* @contributor Bastien Jaillot
* @copyright   2006-2008 Laurent Jouanneau, 2007 Dominique Papin, 2008 Bastien Jaillot
* @link        http://www.jelix.org
* @licence     http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
require(JELIX_LIB_PATH.'forms/jFormsControl.class.php');
require(JELIX_LIB_PATH.'forms/jFormsDatasource.class.php');
require(JELIX_LIB_UTILS_PATH.'jDatatype.class.php');
class jExceptionForms extends jException{
}
abstract class jFormsBase{
	protected $controls = array();
	protected $rootControls = array();
	protected $submits = array();
	protected $reset = null;
	protected $uploads = array();
	protected $hiddens = array();
	protected $htmleditors = array();
	protected $container = null;
	protected $builders = array();
	protected $modifiedControls = array();
	protected $sel;
	public function __construct($sel, $container, $reset = false){
		$this->container = $container;
		if($reset){
			$this->container->clear();
		}
		$this->container->updatetime = time();
		$this->sel = $sel;
	}
	public function getSelector(){
		return $this->sel;
	}
	public function initFromRequest(){
		$req = $GLOBALS['gJCoord']->request;
		$this->modifiedControls=array();
		foreach($this->rootControls as $name=>$ctrl){
			if(!$this->container->isActivated($name) || $this->container->isReadOnly($name))
				continue;
			$ctrl->setValueFromRequest($req);
		}
	}
	public function check(){
		$this->container->errors = array();
		foreach($this->rootControls as $name=>$ctrl){
			if($this->container->isActivated($name))
				$ctrl->check();
		}
		return count($this->container->errors) == 0;
	}
	public function initFromDao($daoSelector, $key = null, $dbProfil=''){
		if($key === null)
			$key = $this->container->formId;
		$dao = jDao::create($daoSelector, $dbProfil);
		$daorec = $dao->get($key);
		if(!$daorec){
			if(is_array($key))
				$key = var_export($key,true);
			throw new jExceptionForms('jelix~formserr.bad.formid.for.dao',
									  array($daoSelector, $key, $this->sel));
		}
		$prop = $dao->getProperties();
		foreach($this->controls as $name=>$ctrl){
			if(isset($prop[$name])){
				$ctrl->setDataFromDao($daorec->$name, $prop[$name]['datatype']);
			}
		}
	}
	public function prepareDaoFromControls($daoSelector, $key = null, $dbProfil=''){
		$dao = jDao::get($daoSelector, $dbProfil);
		if($key === null)
			$key = $this->container->formId;
		if($key != null &&($daorec = $dao->get($key))){
			$toInsert= false;
		}else{
			$daorec = jDao::createRecord($daoSelector, $dbProfil);
			if($key != null)
				$daorec->setPk($key);
			$toInsert= true;
		}
		$prop = $dao->getProperties();
		foreach($this->controls as $name=>$ctrl){
			if(!isset($prop[$name]))
				continue;
			if(is_array($this->container->data[$name])){
				if( count($this->container->data[$name]) ==1){
					$daorec->$name = $this->container->data[$name][0];
				}else{
					continue;
				}
			}else{
				$daorec->$name = $this->container->data[$name];
			}
			if($daorec->$name == '' && !$prop[$name]['required']){
				$daorec->$name = null;
			}else if($daorec->$name == '' && $prop[$name]['defaultValue'] !== null
					&& in_array($prop[$name]['datatype'],
								array('int','integer','double','float'))){
				$daorec->$name = $prop[$name]['defaultValue'];
			}else if( $prop[$name]['datatype'] == 'boolean'){
				$daorec->$name =($daorec->$name == '1'|| $daorec->$name == 'true'
								  || $daorec->$name == 't');
			}else if($ctrl->datatype instanceof jDatatypeLocaleDateTime
					 && $prop[$name]['datatype'] == 'datetime'){
				$dt = new jDateTime();
				$dt->setFromString($daorec->$name, jDateTime::LANG_DTFORMAT);
				$daorec->$name = $dt->toString(jDateTime::DB_DTFORMAT);
			}elseif($ctrl->datatype instanceof jDatatypeLocaleDate
					&& $prop[$name]['datatype'] == 'date'){
				$dt = new jDateTime();
				$dt->setFromString($daorec->$name, jDateTime::LANG_DFORMAT);
				$daorec->$name = $dt->toString(jDateTime::DB_DFORMAT);
			}
		}
		return compact("daorec", "dao", "toInsert");
	}
	public function saveToDao($daoSelector, $key = null, $dbProfil=''){
		extract($this->prepareDaoFromControls($daoSelector,$key,$dbProfil));
		if($toInsert){
			$dao->insert($daorec);
		}else{
			$dao->update($daorec);
		}
		return $daorec->getPk();
	}
	public function initControlFromDao($name, $daoSelector, $primaryKey = null, $primaryKeyNames=null, $dbProfil=''){
		if(!$this->controls[$name]->isContainer()){
			throw new jExceptionForms('jelix~formserr.control.not.container', array($name, $this->sel));
		}
		if(!$this->container->formId)
			throw new jExceptionForms('jelix~formserr.formid.undefined.for.dao', array($name, $this->sel));
		if($primaryKey === null)
			$primaryKey = $this->container->formId;
		if(!is_array($primaryKey))
			$primaryKey =array($primaryKey);
		$dao = jDao::create($daoSelector, $dbProfil);
		$conditions = jDao::createConditions();
		if($primaryKeyNames)
			$pkNamelist = $primaryKeyNames;
		else
			$pkNamelist = $dao->getPrimaryKeyNames();
		foreach($primaryKey as $k=>$pk){
			$conditions->addCondition($pkNamelist[$k], '=', $pk);
		}
		$results = $dao->findBy($conditions);
		$valuefield = $pkNamelist[$k+1];
		$val = array();
		foreach($results as $res){
			$val[]=$res->$valuefield;
		}
		$this->controls[$name]->setData($val);
	}
	public function saveControlToDao($controlName, $daoSelector, $primaryKey = null, $primaryKeyNames=null, $dbProfil=''){
		if(!$this->controls[$controlName]->isContainer()){
			throw new jExceptionForms('jelix~formserr.control.not.container', array($controlName, $this->sel));
		}
		$values = $this->container->data[$controlName];
		if(!is_array($values) && $values != '')
			throw new jExceptionForms('jelix~formserr.value.not.array', array($controlName, $this->sel));
		if(!$this->container->formId && !$primaryKey)
			throw new jExceptionForms('jelix~formserr.formid.undefined.for.dao', array($controlName, $this->sel));
		if($primaryKey === null)
			$primaryKey = $this->container->formId;
		if(!is_array($primaryKey))
			$primaryKey =array($primaryKey);
		$dao = jDao::create($daoSelector);
		$daorec = jDao::createRecord($daoSelector);
		$conditions = jDao::createConditions();
		if($primaryKeyNames)
			$pkNamelist = $primaryKeyNames;
		else
			$pkNamelist = $dao->getPrimaryKeyNames();
		foreach($primaryKey as $k=>$pk){
			$conditions->addCondition($pkNamelist[$k], '=', $pk);
			$daorec->{$pkNamelist[$k]} = $pk;
		}
		$dao->deleteBy($conditions);
		if(is_array($values)){
			$valuefield = $pkNamelist[$k+1];
			foreach($values as $value){
				$daorec->$valuefield = $value;
				$dao->insert($daorec);
			}
		}
	}
	public function getErrors(){  return $this->container->errors;}
	public function setErrorOn($field, $mesg){
		$this->container->errors[$field]=$mesg;
	}
	public function setData($name, $value){
		$this->controls[$name]->setData($value);
	}
	public function getData($name){
		if(isset($this->container->data[$name]))
			return $this->container->data[$name];
		else return null;
	}
	public function getAllData(){ return $this->container->data;}
	public function getDatas(){
		trigger_error('jFormsBase::getDatas is deprecated, use getAllData instead',E_USER_NOTICE);
		return $this->container->data;
	}
	function setModifiedFlag($name){
		$this->modifiedControls[$name] = $this->container->data[$name];
	}
	public function deactivate($name, $deactivation=true){
		$this->controls[$name]->deactivate($deactivation);
	}
	public function isActivated($name){
		return $this->container->isActivated($name);
	}
	public function setReadOnly($name, $r = true){
		$this->controls[$name]->setReadOnly($r);
	}
	public function isReadOnly($name){
		return $this->container->isReadOnly($name);
	}
	public function getContainer(){ return $this->container;}
	public function getRootControls(){ return $this->rootControls;}
	public function getControls(){ return $this->controls;}
	public function getControl($name){ return $this->controls[$name];}
	public function getSubmits(){ return $this->submits;}
	public function getHiddens(){ return $this->hiddens;}
	public function getHtmlEditors(){ return $this->htmleditors;}
	public function getModifiedControls(){ return $this->modifiedControls;}
	public function getReset(){ return $this->reset;}
	public function id(){ return $this->container->formId;}
	public function hasUpload(){ return count($this->uploads)>0;}
	public function getBuilder($buildertype){
		global $gJConfig;
		if($buildertype == '') $buildertype = 'html';
		if(isset($gJConfig->_pluginsPathList_jforms[$buildertype])){
			if(isset($this->builders[$buildertype]))
				return $this->builders[$buildertype];
			include_once(JELIX_LIB_PATH.'forms/jFormsBuilderBase.class.php');
			include_once($gJConfig->_pluginsPathList_jforms[$buildertype].$buildertype.'.jformsbuilder.php');
			$c = $buildertype.'JformsBuilder';
			$o = $this->builders[$buildertype] = new $c($this);
			return $o;
		}else{
			throw new jExceptionForms('jelix~formserr.invalid.form.builder', array($buildertype, $this->sel));
		}
	}
	public function saveFile($controlName, $path='', $alternateName=''){
		if($path == ''){
			$path = JELIX_APP_VAR_PATH.'uploads/'.$this->sel.'/';
		} else if(substr($path, -1, 1) != '/'){
			$path.='/';
		}
		if(!isset($this->controls[$controlName]) || $this->controls[$controlName]->type != 'upload')
			throw new jExceptionForms('jelix~formserr.invalid.upload.control.name', array($controlName, $this->sel));
		if(!isset($_FILES[$controlName]) || $_FILES[$controlName]['error']!= UPLOAD_ERR_OK)
			return false;
		if($this->controls[$controlName]->maxsize && $_FILES[$controlName]['size'] > $this->controls[$controlName]->maxsize){
			return false;
		}
		jFile::createDir($path);
		if($alternateName == ''){
			$path.= $_FILES[$controlName]['name'];
		} else{
			$path.= $alternateName;
		}
		return move_uploaded_file($_FILES[$controlName]['tmp_name'], $path);
	}
	public function saveAllFiles($path=''){
		if($path == ''){
			$path = JELIX_APP_VAR_PATH.'uploads/'.$this->sel.'/';
		} else if(substr($path, -1, 1) != '/'){
			$path.='/';
		}
		if(count($this->uploads))
			jFile::createDir($path);
		foreach($this->uploads as $ref=>$ctrl){
			if(!isset($_FILES[$ref]) || $_FILES[$ref]['error']!= UPLOAD_ERR_OK)
				continue;
			if($ctrl->maxsize && $_FILES[$ref]['size'] > $ctrl->maxsize)
				continue;
			move_uploaded_file($_FILES[$ref]['tmp_name'], $path.$_FILES[$ref]['name']);
		}
	}
	public function addControl($control){
		$this->rootControls [$control->ref] = $control;
		$this->addChildControl($control);
		if($control instanceof jFormsControlGroups){
			foreach($control->getChildControls() as $ctrl)
				$this->addChildControl($ctrl);
		}
	}
	function removeControl($name){
		if(!isset($this->rootControls [$name]))
			return;
		unset($this->rootControls [$name]);
		unset($this->controls [$name]);
		unset($this->submits [$name]);
		if($this->reset && $this->reset->ref == $name)
			$this->reset = null;
		unset($this->uploads [$name]);
		unset($this->hiddens [$name]);
		unset($this->htmleditors [$name]);
		unset($this->container->data[$name]);
	}
	public function addChildControl($control){
		$this->controls [$control->ref] = $control;
		if($control->type =='submit')
			$this->submits [$control->ref] = $control;
		else if($control->type =='reset')
			$this->reset = $control;
		else if($control->type =='upload')
			$this->uploads [$control->ref] = $control;
		else if($control->type =='hidden')
			$this->hiddens [$control->ref] = $control;
		else if($control->type == 'htmleditor')
			$this->htmleditors [$control->ref] = $control;
		$control->setForm($this);
		if(!isset($this->container->data[$control->ref])){
			if( $control->datatype instanceof jDatatypeDateTime && $control->defaultValue == 'now'){
				$dt = new jDateTime();
				$dt->now();
				$this->container->data[$control->ref] = $dt->toString($control->datatype->getFormat());
			}
			else{
				$this->container->data[$control->ref] = $control->defaultValue;
			}
		}
	}
}