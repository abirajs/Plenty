<?php
/**
 * This file is used for creating the configuration for the plugin
 *
 * @author       Novalnet AG
 * @copyright(C) Novalnet
 * @license      https://www.novalnet.de/payment-plugins/kostenlos/lizenz
 */

namespace Novalnet1\Assistants;


use Plenty\Modules\Wizard\Services\WizardProvider;
use Plenty\Modules\System\Contracts\WebstoreRepositoryContract;
use Plenty\Plugin\Application;


/**
 * Class NovalnetAssistant
 *
 * @package Novalnet\Assistants
 */
class NovalnetAssistant extends WizardProvider
{
  

    /**
     * @var WebstoreRepositoryContract
     */
    private $webstoreRepository;

    /**
     * @var $mainWebstore
     */
    private $mainWebstore;

    /**
     * @var $webstoreValues
     */
    private $webstoreValues;


    /**
    * Constructor.
    *
    * @param WebstoreRepositoryContract $webstoreRepository
    * @param PaymentHelper $paymentHelper
    */
    public function __construct(WebstoreRepositoryContract $webstoreRepository)
    {
        $this->webstoreRepository   = $webstoreRepository;
    }

    protected function structure()
    {
        $config =
        [
            "title" => 'Novalnet1',
            "shortDescription" => 'Secure and Trust',
            "iconPath" => $this->getIcon(),
            "translationNamespace" => 'Novalnet1',
            "key" => 'payment-novalnet-assistant',
            "topics" => ['payment'],
            "priority" => 999,
            "options" =>
            [
                'clientId' =>
                [
                    'type'          => 'select',
                    'defaultValue'  => $this->getMainWebstore(),
                    'options'       => [
                                        'name'          => 'clientId',
                                        'required'      => true,
                                        'listBoxValues' => $this->getWebstoreListForm(),
                                       ],
                ],
            ],
            "steps" => []
        ];
	
        $config = $this->createGlobalConfiguration($config);
	$config = $this->createWebhookConfiguration($config);
        return $config;
    }
          
   /**
     * Load Novalnet Icon
     *
     * @return string
     */
    protected function getIcon()
    {
        $app = pluginApp(Application::class);
        $icon = $app->getUrlPath('Novalnet1').'/images/novalnet_icon.png';
        return $icon;
    }

    /**
     * Load main web store configuration
     *
     * @return string
     */
    private function getMainWebstore()
    {
        if($this->mainWebstore === null) {
            $this->mainWebstore = $this->webstoreRepository->findById(0)->storeIdentifier;
        }
        return $this->mainWebstore;
    }

    /**
     * Get the shop list
     *
     * @return array
     */
    private function getWebstoreListForm()
    {
        if($this->webstoreValues === null) {
            $webstores = $this->webstoreRepository->loadAll();
            $this->webstoreValues = [];
            /** @var Webstore $webstore */
            foreach($webstores as $webstore) {
                $this->webstoreValues[] = [
                    "caption" => $webstore->name,
                    "value" => $webstore->storeIdentifier,
                ];
            }
        }
        return $this->webstoreValues;
    }
	
    /**
    * Create the global configurations
    *
    * @param array $config
    *
    * @return array
    */
    public function createGlobalConfiguration($config)
    {
        $config['steps']['novalnetGlobalConf'] =
        [
            "title" => 'Novalnet API Configurations',
            "sections" => [
                [
                    "title"         => 'Novalnet API Configurations',
                    "description"   => ' ',
                    "form"          =>
                    [
                        'novalnetPublicKey' =>
                        [
                            'type'      => 'text',
                            'options'   => [
                                            'name'      => 'Product Activation Key',
                                            'required'  =>  true
                                           ]
                        ],
									'novalnetAccessKey' =>
                        [
                            'type'      => 'text',
                            'options'   => [
                                            'name'      => 'Public Access Key',
                                            'required'  => true
                                           ]
                        ],
                        
                        'novalnetTariffId' =>
                        [
                            'type'      => 'text',
                            'options'   => [
                                            'name'      => 'Traiff Id',
                                            'required'  => true,
                                            'pattern'   => '^[1-9]\d*$'
                                           ]
                        ],
                        
                        'novalnetClientKey' =>
                        [
                            'type'      => 'text',
                            'options'   => [
                                            'name'      => 'Client Key',
                                            'required'  => true
                                           ]
                        ],
                        
                        'novalnetOrderCreation' =>
                        [
                            'type'         => 'checkbox',
                            'defaultValue' => true,
                            'options'   => [
                                            'name'  => 'Order Creation'
                                           ]
                        ],
                        
                    ]
                ]
            ]
        ];
        return $config; 
    }
	
 /**
    * Create the webhook configuration
    *
    * @param array $config
    *
    * @return array
    */
    public function createWebhookConfiguration($config)
    {
        $config['steps']['novalnetWebhookConf'] =
        [
                "title"     => 'Notification / Webhook URL Setup',
                "sections"  =>
                [
                    [
                        "title"         => 'Notification / Webhook URL Setup',
                        "description"   => '',
                        "form" =>
                        [
                            'novalnetWebhookTestMode' =>
                            [
                                'type'      => 'checkbox',
                                'options'   => [
                                                'name'      => 'Allow manual testing of the Notification / Webhook URL'
                                               ]
                            ],
                            'novalnetWebhookEmailTo' =>
                            [
                                'type'      => 'text',
                                'options'   => [
                                                'name'      => 'Send e-mail to',
                                              
                                               ]
                            ]
                        ]
                    ]
                ]
        ];
        return $config;
    }
}
 
