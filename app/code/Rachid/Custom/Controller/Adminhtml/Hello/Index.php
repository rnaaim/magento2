<?php
/**
 * Created by PhpStorm.
 * User: rnaaim
 * Date: 20/07/2018
 * Time: 15:31
 */

namespace Rachid\Custom\Controller\Adminhtml\Hello;
use \Magento\Framework\Registry;
use \Magento\Framework\App\Bootstrap;
include('app/bootstrap.php');

class Index extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * Constructor
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $users = $this->getCustomers();

        foreach ($users as $key => $user){

            $bootstrap = Bootstrap::create(BP, $_SERVER);
            $objectManager = $bootstrap->getObjectManager();
            $url = \Magento\Framework\App\ObjectManager::getInstance();
            $storeManager = $url->get('\Magento\Store\Model\StoreManagerInterface');
            $mediaurl= $storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
            $state = $objectManager->get('\Magento\Framework\App\State');
            $state->setAreaCode('frontend');

            $customerFactory = $objectManager->get('\Magento\Customer\Model\CustomerFactory');
            $websiteId = $storeManager->getWebsite()->getWebsiteId();
            $store = $storeManager->getStore();
            $storeId = $store->getStoreId();
            
            $customer = $customerFactory->create();
            $customer->setWebsiteId($websiteId);

            $customer->setId($user->id);
            $customer->setEmail($user->first_name."@degetel.com");
            $customer->setFirstname($user->first_name);
            $customer->setLastname($user->last_name);
            $customer->setAvatar($user->avatar);
            $customer->setPassword($user->first_name."123");

                $customer->save();
        }
        return $resultPage = $this->resultPageFactory->create();
    }


    public function getCustomers(){

            $users_json = file_get_contents('https://reqres.in/api/users');

            $users = json_decode($users_json);

            return $users->data;
    }
}

