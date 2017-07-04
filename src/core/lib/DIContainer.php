<?php
/**
 * Windwork
 * 
 * 一个用于快速开发高并发Web应用的轻量级PHP框架
 * 
 * @copyright Copyright (c) 2008-2017 Windwork Team. (http://www.windwork.org)
 * @license   http://opensource.org/licenses/MIT
 */
namespace wf\core;

/**
 * 依赖注入容器
 *
 * @package     wf.core
 * @author      cm <cmpan@qq.com>
 * @link        http://docs.windwork.org/manual/wf.core.di.html
 * @since       0.1.0
 */
final class DIContainer
{    
    /**
     * 对象实例
     * @var array
     */
    protected $objs = [];
        
    /**
     * 获取属性
     *
     * @param string $objKey
     * @return mixed
     */
    public function __get($objKey)
    {        
        $objKey = strtolower($objKey);
        
        if(isset($this->objs[$objKey])) {
            return $this->objs[$objKey];
        } else {
            return null;
        }
    }
    
    /**
     * 设置属性
     *
     * @param string $objKey
     * @param mixed $obj
     * @return \wf\core\DIContainer
     */
    public function __set($objKey, $obj)
    {        
        $objKey = strtolower($objKey);
        $this->objs[$objKey] = $obj;
        
        return $this;
    }
    
    /**
     * 该属性是否已经设置
     *
     * @param string $objKey
     * @return bool
     */
    public function __isset($objKey)
    {
        $objKey = strtolower($objKey);
        
        return array_key_exists($objKey, $this->objs);
    }
    
    /**
     * 释放属性
     *
     * @param string $objKey 属性名
     */
    public function __unset($objKey)
    {
        $objKey = strtolower($objKey);        
        unset($this->objs[$objKey]);
        
        return $this;
    }
}
