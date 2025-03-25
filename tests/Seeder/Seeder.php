<?php

namespace Tests\Seeder;


class Seeder
{
    public static function intDatabase()
    {
        PermissionFactory::intPermissionFactory();
        UserFactory::intUserFactory();
        UserPermissionFactory::intUserPermissionFactory();
        UnitFactory::intUnitFactory();
        CategoryFactory::intCategoryFactory();
        ProductFactory::intProductFactory();
        ProductRelationFactory::intProductRelationFactory();
        SupplierFactory::intSupplierFactory();
        WarehouseFactory::intWarehouseFactory();
        ShelvesFactory::intShelvesFactory();
        ProductSupplierFactory::intProductSupplierFactory();
        PurchaseOrderFactory::intPurchaseOrderFactory();
        ProductPurchaseOrderFactory::intProductPurchaseOrderFactory();
        PurchaseProductShelveFactory::intPurchaseProductShelveFactory();
        ProductLocationFactory::intProductLocationFactory();
        ReturnOrderFactory::intReturnOrderFactory();
        ReturnOrderDetailFactory::intReturnOrderDetailFactory();
        CustomerFactory::intCustomerFactory();
        SaleOrderFactory::intSaleOrderFactory();
        SaleOrderDetailFactory::intSaleOrderDetailFactory();
        RegisterPaymentFactory::intRegisterPaymentFactory();
        SaleOrderLocationFactory::intSaleOrderLocationFactory();
    }
}
