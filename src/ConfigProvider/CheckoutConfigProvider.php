<?php
/**
 * Copyright © 2018 Rubic. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Rubic\CleanCheckoutSocial\ConfigProvider;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\ScopeInterface;
use Rubic\CleanCheckoutSocial\Service\SocialLoginService;

class CheckoutConfigProvider implements ConfigProviderInterface
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var UrlInterface
     */
    private $url;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param UrlInterface $url
     */
    public function __construct(ScopeConfigInterface $scopeConfig, UrlInterface $url)
    {
        $this->scopeConfig = $scopeConfig;
        $this->url = $url;
    }

    /**
     * Checks if social login provider is enabled in config.
     *
     * @param string $provider
     * @return bool
     */
    private function isProviderEnabled($provider)
    {
        return (bool)$this->scopeConfig->getValue(
            sprintf(SocialLoginService::CONFIG_PATH_SOCIAL_LOGIN_PROVIDER_ENABLED, $provider),
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return [
            'socialLogin' => [
                'enabled' => (bool)$this->scopeConfig->getValue(
                    SocialLoginService::CONFIG_PATH_SOCIAL_LOGIN_ENABLED,
                    ScopeInterface::SCOPE_STORE
                ),
                'url'      => $this->url->getUrl('clean_checkout/social/authenticate'),
                'twitter'  => $this->isProviderEnabled('twitter'),
                'facebook' => $this->isProviderEnabled('facebook'),
                'google'   => $this->isProviderEnabled('google')
            ]
        ];
    }
}
