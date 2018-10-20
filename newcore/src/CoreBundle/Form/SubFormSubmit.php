<?php
/**
 * @copyright Copyright (c) 2012 – 2020 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2017年10月28日
 */
namespace CoreBundle\Form;

use CoreBundle\Functions\FormEntity;
use CoreBundle\Services\ServiceBase;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * 表单提交端的服务类，表单显示端不在本类范围
 * 针对关联双表单中的单个子表单的管理
 * 基于传入的数据$data，而非 $request
 * 
 */
class SubFormSubmit extends ServiceBase
{
    /**
     * 表单id
     * @var integer
     */ 
    protected $formId = 0;
    /**
     * 更新数据的id
     * @var integer
     */ 
    protected $id;    
    /**
     * 是否关联表单
     * @var boolean
     */
    protected $isBind;
    /**
     * 操作方式(add/update)
     * @var string
     */
    protected $operateType;
    /**
     * 传入表单的数据包(基于$_POST)
     * @var array
     */
    protected $data;
    /**
     * 本表单的 Entity,如果是纵表，为虚拟entity
     * @var entity|formEntity
     */    
    protected $entity;
    /**
     * 模型配置
     * @var array
     */
    protected $model;
    /**
     * 字段元数据(对纵表来说是虚拟的，不是表本身的fieldmapps)
     * @var array
     */
    protected $fieldMapps;
    /**
     * 真实的元数据,不管横纵表，都是表本身的元数据
     * @var array
     */
    protected $trueFieldMapps;
    /**
     * 表单配置
     * @var object
     */    
    protected $modelForm;
    /**
     * 表单字段配置
     * @var array
     */    
    protected $formAttrs;
    /**
     * 表单的数据服务实例
     * @var object
     */
    protected $service;      
    
    protected $registry;    

    public function __construct($formId, $isBind, $service, ContainerInterface $container)
    {
        $this->container = $container;
        $this->registry = $this->get('form.registry');        
        //表单数据
        $map = array();
        $map['id'] = $formId;
        $map['status'] = 1;
        $modelForm = $this->get('db.model_form')->findOneBy($map, array(), false);
        
        if(!is_object($modelForm)) {
            throw new \LogicException('表单配置不存在');
        }
        
        if($isBind && !$modelForm->getBindfield()) {//为关联表单设置默认值
            $modelForm->setBindfield('mid');
        }        

        $this->formId = $formId;
        $this->isBind = $isBind;
        $this->modelForm = $modelForm;
        $this->service = $service;

    }
    
    public function assignData($id, $data, $entity)
    {
        $this->id = $id;
        $this->operateType = $id ? 'update' : 'add';
        $this->data = $data;
        $this->entity = null;//批量提交时，清空上一次的数据
        if(!self::isVertical() && !self::isBind()) {//只允许横并主表单才允许传entity进来
            $this->entity = $entity;
        }
    }
    

    public function getFormId()
    {
        return $this->formId;
    }
    public function getId()
    {
        return $this->id;
    }  
    public function isBind()
    {
        return $this->isBind;
    } 
    public function getOperateType()
    {
        return $this->operateType;
    } 
    /**
     * 是否纵表表单
     * @return boolean
     */
    public function isVertical()
    {
        return (int)self::getModelForm()->getType();
    }
    /**
     * 是否纵表表单
     * @return boolean
     */
    public function getBindField()
    {
        return self::getModelForm()->getBindField();
    }
    /**
     * 取得模型服务
     * @return boolean
     */
    public function getService()
    {
        if(empty($this->service)) {
            $model = self::getModel();
            if(empty($model['service'])) {
                throw new \LogicException('表单未定义数据服务');
            }
            $this->service = $this->get($model['service']);
        }
        return $this->service;
    }
    
    public function getModelForm()
    {
        return $this->modelForm;
    }
    public function getEntity()
    {
        if(empty($this->entity)) {

            if(!$this->getFormId()) return null;

            if(self::isVertical()) {//纵表单模拟一个entity
                $this->entity = new FormEntity();
                foreach(self::getFieldMapps() as $k => $v) {
                    $method = self::ucWords($k);
                    $this->entity->createMethod($method);
                    if(isset($this->data[$k])) {
                        $this->entity->{'set'.$method}($this->data[$k]);
                    }
                }
            } else {
                if(self::isBind()) {
                    if(self::getId() && !$this->entity) {//修改需要读源对象
                        $this->entity = self::getService()->createOneIfNone(array(self::getBindField() => self::getId()));
                    }
                } else {
                    if(self::getId() && !$this->entity) {//修改需要读源对象
                        $this->entity = self::getService()->findOneBy(array('id' => self::getId()), array(), false);
                    }
                }
                if(self::getId() && !$this->entity) {
                    throw new \LogicException('需要修改的文件不存在！');
                }

                $this->entity = self::getService()->dataToEntity($this->data, empty($this->entity) ? null : $this->entity);  
                
            }
            if(!$this->entity) {
                throw new \LogicException('对象初始化失败！');
            }
        }
        return $this->entity;
    }    
    public function getFieldMapps()
    {
        if(empty($this->fieldMapps) && !is_array($this->fieldMapps)) {
            // 纵表表单的fieldMapps 来自于表单字段
            if(self::isVertical()) {
                $this->fieldMapps = array();
                foreach(self::getFormAttrs() as $k => $v) {
                    $this->fieldMapps[$k]['fieldName'] = $k;
                    $this->fieldMapps[$k]['options']['comment'] = $v['label'];
                    $this->fieldMapps[$k]['type'] = $v['type'];
                    $this->fieldMapps[$k]['columnName'] = $k;
                }
            } else {
                // 横表表单的 fieldMapps 来自于metadata
                $metadata = self::getService()->getClassMetadata();
                $this->fieldMapps = $metadata->fieldMapps;//取掉了id值
            }
        }
        return $this->fieldMapps;
    }
    
    //取得真实的fieldmapps
    public function getTrueFieldMapps()
    {
        if(empty($this->trueFieldMapps) && !is_array($this->trueFieldMapps)) {
            $metadata = self::getService()->getClassMetadata();
            $this->trueFieldMapps = $metadata->fieldMapps;
        }

        return $this->trueFieldMapps;
    }
    /**
     * 得到表单字段配置，注意会结合当前一些动态状况,在某些情况下有可能是空数组
     * @return array
     */
    public function getFormAttrs()
    {
        if(empty($this->formAttrs) && !is_array($this->formAttrs)) {//未生成过为null
    
            $checkGroup = self::_getCheckGroup(self::getModelForm());//只取当前校验组中的表单字段
            $map = array();
            foreach($checkGroup as $cg) {
                $map['name']['orX'][]['name'] = $cg;
            }
            
            $formAttrs = $this->get('db.model_form_attr')->findByFormid(self::getModelForm()->getId(),$map);
            if(empty($formAttrs)) $formAttrs = array();
            
            $_is_appointed = (int)$this->get('request')->get('_is_appointed', 0);
            if($this->getOperateType() == 'update') {//更新只处理表单中有该值的数据
                $_is_appointed = 1;
            }
                
            foreach($formAttrs as $_name => $fmAttr) {
                //开关标识，处理mconfig中关于开/关某个表单字段的设置
                if(self::_isFieldClosed($fmAttr)) {
                    unset($formAttrs[$_name]);
                    continue;
                }
                
                $_needValidate = false;
                switch(self::getOperateType()) {
                    case 'add':
                        if((int)$fmAttr['validate_time'] != 2) $_needValidate = true;
                        break;
                    case 'update':
                        if((int)$fmAttr['validate_time'] != 1)  $_needValidate = true;
                        break;
                    default:
                        $_needOperate = true;
                        break;
                }
                if(!$_needValidate) {
                    unset($formAttrs[$_name]);
                    continue;                    
                }
                
                //只验证传了值的字段
                if($_is_appointed && !isset($this->data[$_name])) {
                    unset($formAttrs[$_name]);
                    continue;
                }
                // 字段在当前风格中是否可用
                if ($fmAttr['bundle'] && !in_array($this->get('cc')->getThemeName(), explode(',', $fmAttr['bundle']))) {
                    unset($formAttrs[$_name]);
                    continue;
                }
            }
            
            $this->formAttrs = $formAttrs;
        }
        return $this->formAttrs;
    }
    
    public function getModel()
    {
        if(empty($this->model)) {
            $this->model = $this->get('db.models')->getData(self::getModelForm()->getModelId());
            if(!$this->model) {
                throw new \InvalidArgumentException('表单绑定的模型不存在');
            }
        }
        return $this->model;
    }
    //在这个过程中，formEntity 会写入一些与 表单字段及data不对应的新值
    public function doValidate($formEntity)
    {
        $formRequest = $this->data;
        $constraint = $validata = array();
            
        foreach($this->getFormAttrs() as $_name => $fmAttr) { 

            //处理规则.表单类型内部定义规则
            $typeForm = $this->registry->getType($fmAttr['type'] ?:'text');
            $innerType = $typeForm->getInnerType();            

            if(method_exists($innerType, 'handleRule')) {
                call_user_func_array(array($innerType, "handleRule"),array($fmAttr, $formEntity, $formRequest, $this->getFormAttrs()));                
            } elseif(isset($formRequest[$_name])) {
                $formEntity->{"set" . self::ucWords($_name)}($formRequest[$_name]);
            }
            
            //以下只处理与模型字段完全契合的表单字段。与模型字段不匹配的字段，需要在 handleRule 内处理完毕。
            if(!self::method_exists($formEntity, "get".self::ucWords($_name))) continue;

            //过淲敏感字
            self::_dealhtml($fmAttr, $formEntity);
    
            //检查唯一性(横表单才检查唯一性)
            self::_checkUnique($formEntity, $fmAttr);
    
            //非必填字段跳过//只有设置了required的才进行下面的验证
            if(!(bool)$fmAttr['required'] || $fmAttr['type'] == 'choice') continue;
    
            //验校初始化//校验赋值
            //$vname = lcfirst(self::ucWords($_name));
            $constraint[$_name] = array();
            $validata[$_name] = $formEntity->{"get".self::ucWords($_name)}();
            $constraint[$_name] = self::_getValidaOptions($fmAttr, $formEntity);
        }

        //数据校验
        $constraint = new Assert\Collection($constraint);
        $errors = $this->get('validator')->validate($validata, $constraint);
        if(count($errors)>0) {//如有错误信息刚返回错误提示
            throw new \InvalidArgumentException($errors[0]->getMessage());
        }
        //数据格式的校准(不会抛出异常)
        self::_haldeDataForamt($formEntity, self::getFieldMapps());

    }
    
    public function assembleFormEntity($formEntity)
    {
        if(self::getFormId()) {
            
            $formAttrs = self::getFormAttrs();
            foreach(self::getFieldMapps() as $k => $v) {
                $method = self::ucWords($k);
            
                if(self::method_exists($formEntity,'get'.$method) && !isset($formAttrs[$k])) {
                    continue;//主表已设置的字段，如果不是副表单的字段，则不再去覆盖。
                }
                 
                if(self::method_exists(self::getEntity(), 'get'.$method)) {
                    $formEntity->createMethod($method);
                    $formEntity->{'set'.$method}(self::getEntity()->{'get'.$method}());
                }
            }
        }
    }    
    //将验证更新后的数据返回给每个表单的entity
    //这个一定要在两个表单的验证都完成之后执行
    public function feedBackEntity($formEntity, array $keepAttrKeys = array())
    {
        if(self::getFormId()) {
            foreach(self::getFieldMapps() as $k => $v) {
                if($k == 'id') continue;
                if(isset($keepAttrKeys[$k])) continue;
                $_name = self::ucWords($k);
                self::getEntity()->{'set'.$_name}($formEntity->{'get'.$_name}());
            }
            self::_extraEntityHandle();

        }
    }    
    // 对结果entity进行的额外外理
    // 所有的额外处理都集中到这里
    public function doPersist($previousEntity)
    {
        $entity = self::getEntity();
        if(self::isVertical()) {
            $data = $entity->toArray(array_keys(self::getFieldMapps()));
            $map = array();
            $forceParam = array();
            //纵表单更新到库//类似系统配置的表单，但不用处理添加提交
            switch (self::getOperateType()) {
                case 'add':
                    if(self::isBind()) {//通常是属性表关联，关联主表的id
                        if(!is_object($previousEntity)) {
                            throw new \InvalidArgumentException('主表entity不能为空');
                        }
                        $forceParam[self::getBindField()] = $previousEntity->getId();

                    } else {
                        throw new \InvalidArgumentException('纵表单勿用于主添加！');//暂无此需求
                    }
                    
                    self::verticalAdd($data, $forceParam);
                    break;
                case 'update':
                    if(self::isBind()) {//通常是属性表关联，关联主表的id
                        if(!is_object($previousEntity)) {
                            throw new \InvalidArgumentException('主表entity不能为空');
                        }
                        $map[self::getBindField()] = $previousEntity->getId();

                    } else {//关联纵表的某个特征，如mconfig的ename
                        $_bindfield = self::getBindField();//关联字段拿来做主纵表的分类特征
                        if($_bindfield) {
                            $map[$_bindfield] = $this->get('request')->get($_bindfield,'');
                            if(!$map[$_bindfield]) {
                                throw new \LogicException("纵表单缺少[{$_bindfield}]值!");
                            }
                            unset($data[$_bindfield]);
                            $forceParam[$_bindfield] = $map[$_bindfield];
                        }
                    }

                    self::verticalUpdate($data, $map, $forceParam);
            
                    break;
            }
        
        } else {
            switch (self::getOperateType()) {
                case 'add'://如是副表，则要将主表entity的关联加入进来
                    
                    if(self::isBind()) {
                        if(!is_object($previousEntity)) {
                            throw new \InvalidArgumentException('主表entity不能为空');
                        }
                        $entity->{'set'.self::ucWords(self::getBindField())}($previousEntity->getId());
                    }
                    self::getService()->createEntity($entity);//entity入库
                    break;
                case 'update'://如是副表，关联在初始化entity时已经做好
                    
                    self::getService()->modifyEntity($entity);//entity入库

                    break;
            }       
        }
        return $entity;
    }    
    // 对结果entity进行的额外外理
    // 所有的额外处理都集中到这里
    private function _extraEntityHandle()
    {
        self::multipleImage(self::getEntity());
    }
  
	/**
	 * 纵表新增(同时也是关联表单新增数据的入口),根据所关联的纵表表单，增加纵表数据
	 * 只宜执行 表单更新后的内置补全，以及绑定表单的添加。主表单如果使用添加，会造成重复添加的情况
	 * 被当前类的 add/update/addImport方法使用
	 * FormValidation::addbindform 会使用本方法，以此解决一个表单中包含多个表(横表或纵表)的数据写入
	 * @param array $data 基本是 POST数据包，或被前期处理过的 POST数据包(如在update中传入时，将已经更新过的数据unset掉了)
	 */
    public function verticalAdd(array $data, array $forceParam = array())
    {
        if(empty($data))  return true;
        $formAttrs = $this->getFormAttrs();
        $fieldMapps = $this->getTruefieldMapps();
             
        //要看一下 area 的处理
        $vinfos = array();
        foreach($data as $k => $v) {
            if(!isset($formAttrs[$k])) continue;
            
            $vinfo = array();
            $vinfo['name'] = $k;
            $vinfo['title'] = $formAttrs[$k]['label'];
            $vinfo['value'] = is_array($v) ? implode(',',$v) : $v;
            
            //保留原有设计(针对mconfig的分类)
            $_ename = $this->get('request')->get('ename','');
            if(isset($fieldMapps['ename']) && $_ename) {
                $vinfo['ename'] = $_ename;
                //$vinfo['ename'] = $this->data['ename'];
            }

            foreach($forceParam as $key => $val) {
                $vinfo[$key] = $val;
            }
            
            // 设置其它传过来的参数值
            foreach($fieldMapps as $key => $val) {
                if(isset($vinfo[$key])) continue;
                if(in_array($key,array('id','is_delete'))) {
                    continue;
                }
                if(isset($this->data[$key])) {
                    $vinfo[$key] = $this->data[$key];
                }
            }
            $vinfos[] = $vinfo;
        }
        if($vinfos) {
            self::getService()->batchadd($vinfos);//会强制写入系统参数
        }
        
        return true;
    }
    
    /**
     * 纵表表单更新(基于参数)，写入数据库，可根据关联id更新多条纵表记录，也可同时按条件更新多表纵表记录。可以想象为这是被关联的一条模表记录的某些字段，保存为纵表的多条记录
     * 纵表类型的主表单、副(关联)表单都是通过这个方法来更新的，但模型是不同的。
     * 本方法只负责更新纵表中已有记录，当纵表中找不到与表单数据相对应的记录时，会新增记录到该表中。
     * 是否可以通过这里做纵表表单的验证//??
     * @param array $param     针对纵表的查询条件，查出的多条纵表记录分别对应 $data 中的相应数据
     * @param array $data      需要更新的数据,不管入口是主表单还是副表单，都是post数据包，只是在流程中会做调整
     * @param integer $form_id  表单id
     * @throws \LogicException
     */
    public function verticalUpdate($data, array $map, array $forceParam = array())
    {

        if(empty($data) || empty($map))  return true;
        //获得原数据
        $info = self::getService()->findBy($map);
        if(!$entities = $info['data'])
		    $entities = array();
        $formAttrs = $this->getFormAttrs();
        
        foreach($entities as $k => $v) {
             
            if(!isset($data[$v->getName()])) {//没有该更新数据的旧记录，留到后面新增
                unset($entities[$k]);
                continue;
            }
            // 所以纵表表单的字段一定要定义label
            if(!isset($formAttrs[$v->getName()]['label']))
                continue;

            // 该条数据未被修改，不需要更新
            // 所以纵表必须有以下字段：name,title,value,并且在这里设定了只要value及title不变，则认为不需要更新
            // title 是从表单配置中来的，而应用数据却从数据库中提取，如果要修改表单字段的label,只有重新提交所有数据，才能体现出来。
            if($v->getValue()==$data[$v->getName()] && $v->getTitle()==$formAttrs[$v->getName()]['label']) {
                unset($entities[$k]);
                unset($data[$v->getName()]);
                continue;
            }

            $v->setValue(is_array($data[$v->getName()])?implode(',',$data[$v->getName()]):$data[$v->getName()]);
            $v->setTitle($formAttrs[$v->getName()]['label']);

            // 需要强行批量设置的值
            $v = self::getService()->dataToEntity($forceParam, $v);
            unset($data[$v->getName()]);
        }
       
        if($entities) {
            self::getService()->batchupdate($entities);
        }

        if($data) {
            // 如果纵表中不存在数据的原记录，则要新增数据记录到纵表
            self::verticalAdd($data,array_merge($forceParam, $map));            
        }

    
        return true;
    }    
    
    //多图转多文的字段处理，只需添加时执行
    //只支持横表主表单
    protected function multipleImage($entity)
    {
        if(self::isBind() || self::isVertical() || self::getOperateType() != 'add') return;
        if(!is_object($entity)) return;
        if(!$fmAttrs = self::getFormAttrs()) return;
        $_entities = array();
        foreach($fmAttrs as $_name => $fmAttr) {
            
            $attr = $this->get('cc')->getQueryParam($fmAttr['attr']);    
            $multiple = isset($attr['multiple']) ? (int)$attr['multiple'] : 0;
    
            switch($fmAttr['type']) {
                case 'file':
                case 'image':
                case 'video':
                    if($multiple!=2) continue;
    
                    $field = self::ucWords($_name);

                    if(is_null(json_decode($entity->{"get".$field}())))
                        $fields = explode(',',$entity->{"get".$field}());
                    else
                        $fields = json_decode($entity->{"get".$field}(), true);
   
                    $endField = array_pop($fields);

                    foreach($fields as $k=>$v)
                    {
                        $_entities[$k] = isset($_entities[$k]) ? $_entities[$k] : clone($entity);
                        $_entities[$k]->{"set".$field}($v['source']);
                    }
       
                    $entity->{"set".$field}($endField['source']);//当前entity会按正常流程入库
                    break;
                default:
                    break;
            }
        }
        
        if($_entities) {
            self::getService()->batchadd($_entities);
        }
        
        return true;
    }
    /**
     * 根据表单中某个选项的值，来决定使用使用哪个验证组(验证不同的表单字段)。
     * 这个选项是挂在表单上的，如有关联表单，如何跨表单起作用？
     * @param object $modelForm     被关联表单的模型服务处理后，返回的数据对象，即前一模型服务的数据对象
     * @param array                 表单字段名数组
     */
    protected function _getCheckGroup($modelForm)
    {
        if(!is_object($modelForm) || !method_exists($modelForm, 'getInitcondition')) {
            throw new \InvalidArgumentException('$modelForm 应为一个 modelForm对象');
        }
        /*
         "initcondition" => "
         data-groupfield=type\r\n
         data-formgroup0=minwidth,minheight,trans,quality,img\r\n
         data-formgroup1=minwidth,minheight,trans,quality,img\r\n
         data-formgroup2=minwidth,minheight,textcontent,fontfile,fontsize,angle,fontcolor
         "
         * */
        if(!($initcondition = $modelForm->getInitcondition())) return array();
        $initcondition = $initcondition ? $this->get('cc')->getQueryArray($initcondition) : array();
        if(!$initcondition || !isset($initcondition['data-groupfield'])) return array();
    
        if(!($groupField = end($initcondition['data-groupfield']))) return array();
        unset($initcondition['data-groupfield']);
    
        $groupRule = array();
        foreach(array_keys($initcondition) as $kinit) {
            if (preg_match('/data-formgroup/', $kinit)) {
                $groupRule[str_replace(array('data-formgroup'),"", $kinit)] = end($initcondition[$kinit]);
                unset($initcondition[$kinit]);
            }
        }
    
        $checkGroup = "";
        if($groupField && $groupRule) {
            $groupValue = $this->get('request')->get($groupField,'');
            if(isset($groupRule[$groupValue]))
                $checkGroup = $groupRule[$groupValue];
        }
    
        $checkGroup = $checkGroup?explode(',', $checkGroup):array();
        return $checkGroup;
    }    
    /**
     * 检查字段是否关闭。根据系统配置mconfig中有相关参数的值来决定
     * @param array $fmAttr    表单字段配置
     * @param boolean          true 为关闭
     */
    private function _isFieldClosed(array $fmAttr)
    {
        if(empty($fmAttr['validate_rule'])) return false;
    
        $validateRule = explode('|', $fmAttr['validate_rule']);
        if(count($validateRule) < 2) return false;
    
        $mconfig = $this->get('db.mconfig')->getData(array('ename'=>$validateRule[1]));
    
        if(!isset($mconfig[$validateRule[0]]['value'])) return false;
    
        return (bool)$mconfig[$validateRule[0]]['value'] ? true : false;
    }    

    private function _dealhtml(array $fmAttr, $entity)
    {
        $getName = "get".self::ucWords($fmAttr['name']);
        $setName = "set".self::ucWords($fmAttr['name']);
    
        if(isset($fmAttr['dealhtml'])&&$fmAttr['dealhtml'])
        {
    
            $thisValue = $entity->{$getName}();
            $dealhtml = $fmAttr['dealhtml'];
            $dealhtmltags = $fmAttr['dealhtmltags'];
    
            switch($dealhtml)
            {
                case 'clearhtml':
                    if($dealhtmltags&&$thisValue&&is_string($dealhtmltags))
                    {
                        $this->get('core.dochtml')->init($thisValue);
                        $this->get('core.dochtml')->pQuery($dealhtmltags);
                        $entity->{$setName}($this->get('core.dochtml')->remove());
                    }
                    break;
                case 'disablehtml':
                    $entity->{$setName}($this->get('cc')->mhtmlSpecialchars($thisValue));
                    break;
                case 'safehtml':
                    $entity->{$setName}($this->get('cc')->safeStr($thisValue));
                    break;
                case 'html_decode':
                    $entity->{$setName}($this->get('cc')->deRepGlobalValue($thisValue));
                    break;
            }
        }
    }
    /**
     * 检查字段值的唯一性
     *
     * @param array $model 当前子表单的model配置
     * @param object $modelForm 当前子表单的model对象
     * @param array $fmAttr 表单某字段配置
     * @return boolean 唯一返回true，否则抛出异常
     * @throws InvalidArgumentException
     */
    protected function _checkUnique($formEntity, array $fmAttr)
    {
        if(empty($fmAttr['isonly'])) return true;
        if(self::isVertical()) return true;//纵表不检查
    
        $attr = !empty($fmAttr['attr']) ? $this->_modelAttr($fmAttr['attr']) : array();
        $dataIsonlys = !empty($attr['data-isonlys']) ? explode(',', $attr['data-isonlys']) :array();
        $_name = $fmAttr['name'];
    
        //查询条件
        $query = $fmAttr['type'] != 'entity' ? $this->get('cc')->getQueryParam($fmAttr['query_builder']) : array();
        $query[$_name] = $formEntity->{"get" . self::ucWords($_name)}();
    
        foreach($dataIsonlys as $isKey) {
            if(isset($query[$isKey])) continue;
    
            if($formEntity->{"get" . self::ucWords($isKey)}()) {
                $query[$isKey] = $formEntity->{"get" . self::ucWords($isKey)}();
            } else {
                $query[$isKey] = $this->get('request')->get($isKey,'');
            }
        }
    
        if(self::getOperateType() == 'update') {//??如果是子表单呢?，它的id并不是主表的id
            $query['id']['neq'] = $formEntity->getId();
        }
    
        unset($query['order']);
        unset($query['orderBy']);
    
        $count = self::getService()->count($query);
    
        if($count>0) {
            throw new \InvalidArgumentException(sprintf('%s [%s] 已存在!!', $fmAttr['label'], $formEntity->{"get".self::ucWords($_name)}()));
        }
        return TRUE;
    }

    /**
     * 获取校验参数
     * @param array $formAttr 表单中某字段的配置
     * @return Ambigous
     */
    private function _getValidaOptions(array $formAttr, $entity)
    {
        $thisValue = $entity->{"get".self::ucWords($formAttr['name'])}();
    
        $type = isset($formAttr['type'])&&$formAttr['type'] ? $formAttr['type'] : 'text';
    
        $constraint = array();
    
        $attr = isset($formAttr['attr'])&&$formAttr['attr']?$this->_modelAttr($formAttr['attr']):array();
    
        $options = array();
    
        //校验必须输入整数
        if(isset($attr['digits'])&&(bool)$attr['digits']) $type = 'integer';
        //校验必须输入数字(包括负数、小数点)
        if(isset($attr['number'])&&(bool)$attr['number']) $type = 'number';
        //输入正确格式的电子邮件
        if(isset($attr['email'])&&(bool)$attr['email']) $type = 'email';
        if(isset($attr['date'])&&(bool)$attr['date']) $type = 'date';
        if(isset($attr['url'])&&(bool)$attr['url']) $type = 'url';
    
        switch($type)
        {
            case 'choice':
                $choices = !empty($formAttr['choices']) ? $this->_modelAttr($formAttr['choices']) : array();
                return $choices ? new Assert\Choice($choices) : $choices;
                break;
            case 'email':
                if(!filter_var($thisValue, FILTER_VALIDATE_EMAIL))
                    throw new \InvalidArgumentException(sprintf('%s 必须输入正确格式的电子邮件!', $formAttr['label']));
                    //return new Assert\Email();
                    break;
            case 'url':
                if(!filter_var($thisValue, FILTER_VALIDATE_URL))
                    throw new \InvalidArgumentException(sprintf('%s 必须输入正确格式的网址!', $formAttr['label']));
                    break;
            case 'file':
                return new Assert\File();
                break;
            case 'number':
                if(!is_numeric($thisValue))
                    throw new \InvalidArgumentException(sprintf('%s 必须输入正确格式的数字!', $formAttr['label']));
                    break;
            case 'image':
                $options['notFoundMessage'] = '图片源未选择';
                $options['minWidth'] = isset($options['minWidth'])?$options['minWidth']:1;
                $options['maxWidth'] = isset($options['maxWidth'])?$options['maxWidth']:2000;
                $options['minHeight'] = isset($options['minHeight'])?$options['minHeight']:1;
                $options['maxHeight'] = isset($options['maxHeight'])?$options['maxHeight']:2000;
                //return new Assert\Image($options);
                break;
            case 'date':
                return new Assert\Date();
                break;
            case 'datetime':
                return new Assert\DateTime();
                break;
            case 'Time':
                return new Assert\Time();
                break;
            case 'integer':
                $regex = array();
                $regex['pattern'] = '/^[0-9]\d*$/';
                $regex['message'] = $formAttr['label'].'只允许为数字.';
                $constraint[] = new Assert\Regex($regex);
            default:
                $options['message'] = $formAttr['error_info'] ? $formAttr['error_info'] : $formAttr['label'].'必须填写';
                $constraint[] = new Assert\NotBlank($options);
                break;
        }
    
        if(isset($attr['min'])||isset($attr['max']))
        {
            if(!is_numeric($thisValue))
                throw new \InvalidArgumentException(sprintf('%s 只允许为数字!!', $formAttr['label']));
    
            if(isset($attr['min'])&&$thisValue<(int)$attr['min'])
                throw new \InvalidArgumentException(sprintf('%s 输入值不能小于 [%s]!', $formAttr['label'], (int)$attr['min']));
    
            if(isset($attr['max'])&&$thisValue>(int)$attr['max'])
                throw new \InvalidArgumentException(sprintf('%s 输入值不能大于 [%s]!', $formAttr['label'], (int)$attr['max']));
        }
    
        if(isset($attr['minlength'])||isset($attr['maxlength']))
        {
            $length = array();
            $length['min'] = isset($attr['min'])?$attr['min']:0;
            $length['max'] = isset($attr['max'])?$attr['max']:1000;
            $length['minMessage'] = $formAttr['label']."长度不可少于  {{ limit }} 个字符";
            $length['maxMessage'] = $formAttr['label']."长度不可大于  {{ limit }} 个字符";
            $constraint[] = new Assert\Length($length);
        }
    
        //if(isset($attr['rule'])&&$attr['rule']&&method_exists($this->get('cc'),$attr['rule']))
        //    $this->get('cc')->{$attr['rule']}($formAttr, $entity);
        unset($length);
        unset($options);
    
        return $constraint;
    }    
    /**
     * 处理数据格式
     */
    private function _haldeDataForamt($entity, $fieldMapps)
    {
        //default
        //去掉属性字段(关联表用)
        if(self::method_exists($entity, "setAttributes")) {
            $entity->setAttributes('');
        }
    
        foreach($fieldMapps as $key => $item)
        {
            if(!self::method_exists($entity, "set".self::ucWords($key))) continue;
    
            $type = isset($item['type']) ? $item['type'] : 'string';
            $typeVal = $entity->{"get" . self::ucWords($key)}();
            $typeVal = $this->get('cc')->normalizeFieldValue($typeVal, $type);
    
            $entity->{"set".self::ucWords($key)}($typeVal);
        }
    }    
    private function _modelAttr($str)
    {
        return $this->get('cc')->getQueryParam($str);
    }    
    private function ucWords($name)
    {
        return $this->get('cc')->ucWords($name);
    }
    // 检查entity是否有某方法
    protected function method_exists($entity, $method)
    {
        if($entity instanceof FormEntity) {
            return $entity->method_exists($method);
        }
        return method_exists($entity, $method);
    }    
}