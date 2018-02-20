<?php

namespace Veni\RegisteredCustomersReport\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class Email
 * @package Veni\RegisteredCustomersReport\Helper
 */
class Email extends \Magento\Framework\App\Helper\AbstractHelper
{

    const XML_PATH_EMAIL_TEMPLATE_FIELD  = 'registered_customers/general/email';

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var StateInterface
     */
    protected $inlineTranslation;

    /**
     * @var TransportBuilder
     */
    protected $_transportBuilder;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Email constructor.
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param StateInterface $inlineTranslation
     * @param TransportBuilder $transportBuilder
     */
    public function __construct (
        Context $context,
        StoreManagerInterface $storeManager,
        StateInterface $inlineTranslation,
        TransportBuilder $transportBuilder,
        LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->scopeConfig = $context->getScopeConfig();
        $this->_storeManager = $storeManager;
        $this->inlineTranslation = $inlineTranslation;
        $this->_transportBuilder = $transportBuilder;
        $this->logger = $logger;
    }

    /**
     * Return store configuration value of your template field that which id you set for template
     *
     * @param string $path
     * @param int $storeId
     * @return string
     */
    protected function getConfigValue($path, $storeId)
    {
        return $this->scopeConfig->getValue(
            $path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Return store
     *
     * @return Store
     */
    public function getStore()
    {
        return $this->_storeManager->getStore();
    }

    /**
     * Return template id according to store
     * @param $xmlPath
     * @return mixed
     */
    public function getTemplateId($xmlPath)
    {
        return $this->getConfigValue($xmlPath, $this->getStore()->getStoreId());
    }

    /**
     * @param $emailTemplateVariables
     * @param $sender
     * @param $recipient
     * @return $this
     */
    public function generateTemplate($emailTemplateVariables, $sender, $recipient)
    {
        $this->_transportBuilder
            ->setTemplateIdentifier($this->templateId)
            ->setTemplateOptions(
                [
                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                    'store' => 0,
                ]
            )
            ->setTemplateVars($emailTemplateVariables)
            ->setFrom($sender)
            ->addTo($recipient['email'],$recipient['name']);

        return $this;
    }

    /**
     * @param $emailTemplateVariables
     * @param $sender
     * @param $recipient
     */
    public function sendEmail($emailTemplateVariables, $sender, $recipient)
    {
        $this->logger->debug('in send email '.$this->_getTemplate());
        $this->templateId = $this->getTemplateId($this->_getTemplate());
        $this->inlineTranslation->suspend();
        $this->generateTemplate($emailTemplateVariables, $sender, $recipient);
        $transport = $this->_transportBuilder->getTransport();
        $transport->sendMessage();

        $this->inlineTranslation->resume();
    }

    /**
     * @return string
     */
    protected function _getTemplate()
    {
        return self::XML_PATH_EMAIL_TEMPLATE_FIELD;
    }

}