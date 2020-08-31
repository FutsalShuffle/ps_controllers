<?php
class PricesTestModuleFrontController extends ModuleFrontController
{
    public function __construct()
    {
        parent::__construct();
        $this->bootstrap = true;
    }
    public function initContent()
    {
        include(_PS_MODULE_DIR_.'prices'.DIRECTORY_SEPARATOR.'/classes/Students.php');
        $students = new Students;
        $names = $students->getAllNames();
        $this->context->smarty->assign(array('names2' => $names,));
        $this->setTemplate('test.tpl');
    }
}
