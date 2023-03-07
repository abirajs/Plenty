<?php
/**
 * This file is used for registering the Novalnet payment methods
 * and Event procedures
 *
 * @author       Novalnet AG
 * @copyright(C) Novalnet
 * @license      https://www.novalnet.de/payment-plugins/kostenlos/lizenz
 */
namespace Novalnet\Providers;

use Novalnet\Assistants\NovalnetAssistant;
use Plenty\Plugin\ServiceProvider;
use Plenty\Modules\Wizard\Contracts\WizardContainerContract;

/** 
 * Class NovalnetServiceProvider
 *
 * @package Novalnet\Providers
 */
class NovalnetServiceProvider extends ServiceProvider
{
   
    /**
     * Boot additional services for the payment method
	 *
     */
    public function boot()
    {
     
        // Set the Novalnet Assistent
        pluginApp(WizardContainerContract::class)->register('payment-novalnet-assistant', NovalnetAssistant::class);
    }


}
