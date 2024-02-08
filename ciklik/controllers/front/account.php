<?php

use PrestaShop\Module\Ciklik\Api\Subscription;
use PrestaShop\Module\Ciklik\Managers\CiklikCustomer;

/**
 * @author    Metrogeek SAS <support@ciklik.co>
 * @copyright Since 2017 Metrogeek SAS
 * @license   https://opensource.org/license/afl-3-0-php/ Academic Free License (AFL 3.0)
 */

class CiklikAccountModuleFrontController extends ModuleFrontController
{
    /**
     * {@inheritdoc}
     */
    public $auth = true;

    /**
     * {@inheritdoc}
     */
    public $authRedirection = 'my-account';

    /**
     * {@inheritdoc}
     */
    public function initContent()
    {
        parent::initContent();

        $ciklik_customer = CiklikCustomer::getByIdCustomer((int) $this->context->customer->id);

        if (array_key_exists('ciklik_uuid', $ciklik_customer)
            && ! is_null($ciklik_customer['ciklik_uuid']))
        {
            $subscriptionsData = (new Subscription($this->context->link))
                ->getAll(['query' => ['filter' => ['customer_id' => $ciklik_customer['ciklik_uuid']]]]);
        }

        $this->context->smarty->assign([
            'subscriptions' => $subscriptionsData ?? [],
            'subcription_base_link' => Tools::getShopDomainSsl(true) . '/ciklik/subscription',
        ]);

        $this->setTemplate('module:ciklik/views/templates/front/account.tpl');
    }
}
