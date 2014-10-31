<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
/**
 * 统计代码
 *
 * @package Statistics
 * @author Liudon
 * @version 1.0.0
 * @link http://www.liudon.org
 */
class Statistics_Plugin implements Typecho_Plugin_Interface
{
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     *
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate()
    {
        Typecho_Plugin::factory('Widget_Archive')->header = array('Statistics_Plugin', 'header');
    }

    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     *
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate(){}

    /**
     * 获取插件配置面板
     *
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form)
    {
        /** 统计代码 */
        $name = new Typecho_Widget_Helper_Form_Element_Text('code', NULL, '', _t('请添加异步统计代码'));
        $form->addInput($name);
    }

    /**
     * 个人用户的配置面板
     *
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}

    /**
     * 插件实现方法
     *
     * @access public
     * @return void
     */
    public static function header($header)
    {
        $header .= Helper::options()->plugin('Statistics')->code;
    }
}
