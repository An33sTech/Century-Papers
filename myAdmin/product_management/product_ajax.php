<?php

if (isset($_GET['page'])) {
    require_once __DIR__ . "/classes/ajax.php";

    $page = $_GET['page'];
    $ajax = new ajax();

    switch ($page) {
        case 'productNameEdit':
            $ajax->productNameEdit();
            break;

        case 'productUpdate':
            $ajax->productUpdate();
            break;

        case 'colorAjax_edit':
            $ajax->processEdit('color');
            break;

        case 'AjaxUpdate_color':
            $ajax->AjaxUpdate_color();
            break;

        case 'colorAjax_del':
            $ajax->AjaxDelScript_color();
            break;

        case 'AjaxAfterUpdateScript_color':
            $ajax->AjaxAfterUpdateScript_color();
            break;

        case 'scaleAjax_edit':
            $ajax->processEdit('scale');
            break;

        case 'AjaxUpdate_scale':
            $ajax->AjaxUpdate_scale();
            break;

        case 'AjaxAfterUpdateScript_scale':
            $ajax->AjaxAfterUpdateScript_scale();
            break;

        case 'scaleAjax_del':
            $ajax->AjaxDelScript_scale();
            break;

        case 'AjaxUpdate_currency':
            $ajax->AjaxUpdate_currency();
            break;

        case 'AjaxAfterUpdateScript_currency':
            $ajax->AjaxAfterUpdateScript_currency();
            break;

        case 'currencyAjax_del':
            $ajax->AjaxDelScript_currency();
            break;

        case 'singleProductDel':
            $ajax->AjaxDelScript_product();
            break;

        case 'selectedProductDel':
            $ajax->AjaxDelScript_productSelected();
            break;

        case 'productEditImageDel':
            $ajax->AjaxDelScript_productImageDel();
            break;

        case 'productEditDetailImageDel':
            $ajax->productEditDetailImageDel();
            break;

        case 'storeAjax_del':
            $ajax->AjaxDelScript_storeDel();
            break;

        case 'storeEdit':
            $ajax->AjaxEditStore();
            break;

        case 'storeEditRequest':
            $ajax->AjaxEditRequestStore();
            break;

        case 'AjaxAfterUpdateScript_store':
            $ajax->AjaxAfterUpdateScript_store();
            break;

        case 'receiptAjax_del':
            $ajax->AjaxDelScript_receiptDel();
            break;

        case 'discountProductDel':
            $ajax->AjaxDelScript_discountDel();
            break;

        case 'holeSaleDel':
            $ajax->AjaxDelScript_holeSaleDel();
            break;

        case 'couponDel':
            $ajax->AjaxDelScript_couponDel();
            break;

        case 'sortProductImage':
            $ajax->sortProductImage();
            break;

        case 'sortProductDetailImage':
            $ajax->sortProductDetailImage();
            break;

        case 'sortProductSize':
            $ajax->sortProductSize();
            break;

        case 'pImageAltUpdate':
            $ajax->pImageAltUpdate();
            break;

        case 'pDetailImageAltUpdate':
            $ajax->pDetailImageAltUpdate();
            break;

        case 'sortProducts':
            $ajax->sortProducts();
            break;

        case 'featureItem':
            $ajax->featureItem();
            break;

        case 'addProToCat':
            $ajax->addProToCat();
            break;

        case 'removeProFromCat':
            $ajax->removeProFromCat();
            break;

        case 'copyMissingProducts':
            $ajax->copyMissingProducts();
            break;

        case 'printSelectedInvoice':
            $ajax->printSelectedInvoice();
            break;

        case 'check_slug_duplicate':
            $ajax->check_slug_duplicate();
            break;
    }
}
