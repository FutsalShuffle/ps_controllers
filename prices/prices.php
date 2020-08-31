<?php
/**
* 2007-2020 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2020 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class Prices extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'prices';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'andrele82';
        $this->need_instance = 0;

        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('prices');
        $this->description = $this->l('Вывод кол-ва товаров по мин. и макс. цене');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        include(dirname(__FILE__).'/sql/install.php');

        Configuration::updateValue('MAX_PRICE', 0);
        Configuration::updateValue('MIN_PRICE', 0);

        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = 'AdminTest';
        $tab->position = 3;
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = 'Test Page';
        }
        $tab->id_parent = (int) Tab::getIdFromClassName('SELL');
        $tab->module = $this->name;
        $tab->add();
        $tab->save();

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader') &&
            $this->registerHook('displayFooter');
    }

    public function uninstall()
    {
        include(dirname(__FILE__).'/sql/uninstall.php');

        Configuration::deleteByName('MAX_PRICE');
        Configuration::deleteByName('MIN_PRICE');

        return parent::uninstall();
    }

    public function getContent()
    {
        if (((bool)Tools::isSubmit('submitPricesModule')) == true) {
            $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        return $this->renderForm();
    }

    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitPricesModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Settings'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'desc' => $this->l(''),
                        'name' => 'MIN_PRICE',
                        'label' => $this->l('MIN_PRICE'),
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'name' => 'MAX_PRICE',
                        'label' => $this->l('MAX_PRICE'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    protected function getConfigFormValues()
    {
        return array(
            'MIN_PRICE' => Configuration::get('MIN_PRICE', 0),
            'MAX_PRICE' => Configuration::get('MAX_PRICE', 0),
        );
    }

    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }
   
    public function hookDisplayFooter()
    {
        $minPrice = Configuration::get('MIN_PRICE');
        $maxPrice = Configuration::get('MAX_PRICE');
        $db = new DbQuery();
        $db->select('*');
        $db->from('product', 'item');
        $db->where("item.price <= {$maxPrice} AND item.price >= {$minPrice}");
        $counter = count(Db::getInstance()->executeS($db));
        return "{$counter} товаров на сайте от {$minPrice}р. до {$maxPrice}р.";
    }
}
