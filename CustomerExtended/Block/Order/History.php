<?php

namespace Wheelpros\CustomerExtended\Block\Order;

use Magento\Framework\Phrase;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Sales\Model\Order;
use Wheelpros\CustomerExtended\Model\SalesforceApi;

class History extends Template
{
    /**
     * @var SalesforceApi
     */
    private SalesforceApi $salesforceApi;

    /**
     * @param Context       $context
     * @param SalesforceApi $salesforceApi
     * @param array         $data
     */
    public function __construct(
        Template\Context $context,
        SalesforceApi    $salesforceApi,
        array            $data = []
    ) {
        parent::__construct($context, $data);
        $this->salesforceApi = $salesforceApi;
    }

    /**
     * Returns customer orders
     *
     * @return array
     */
    public function getCustomerOrders(): array
    {
        $ordersData = $this->salesforceApi->fetchOrders();

        if ($ordersData['done'] && $ordersData['totalSize'] > 0) {
            return $ordersData['records'];
        }

        return [];
    }

    /**
     * Get message for no orders.
     *
     * @return Phrase
     */
    public function getEmptyOrdersMessage()
    {
        return __('You have placed no orders.');
    }

    /**
     * Get reorder URL
     *
     * @param  Order $order
     * @return string
     */
    public function getReorderUrl($order): string
    {
        return $this->getUrl('sales/order/reorder');
//        return $this->getUrl('sales/order/reorder', ['order_id' => $order->getId()]);
    }
}
