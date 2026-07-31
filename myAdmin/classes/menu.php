<?php

class menu extends object_class
{

	//If you want to hide menu for this Website, use

	//$this->checkActive( 'Dashboard', 'name', false ) false in 2nd parameter in functions

	//same as subMenu functions

	public function __construct()
	{

		parent::__construct('3');

		global $_e;

		global $adminPanelLanguage;

		//Main Menu List

		$_w = [];
		$_w['Product QTY Statistics'] = '';
		$_w['Product Inventory Statistics'] = '';
		$_w['Dashboard'] = '';

		$_w['Products'] = '';
		$_w['Visiting Customers'] = '';

		$_w['Stock Management'] = '';

		$_w['Order Management'] = '';

		$_w['Statics'] = '';

		$_w['Shipping Management'] = '';
		$_w['Filter Management'] = '';
		$_w['Manage Filters'] = '';
		$_w['Order Tracking Number'] = '';

		$_w['Menu'] = '';
		$_w['Add New Orders'] = '';

		$_w['Sort Products Image'] = '';

		$_w['Recommended Products'] = '';

		$_w['Logs Management'] = '';

		$_w['Pages'] = '';

		$_w['News & Events'] = '';

		$_w['Banners'] = '';

		$_w['Brands'] = '';

		$_w['Media'] = '';

		$_w['SEO Management'] = '';

		$_w['Setting'] = '';

		$_w['Email Management'] = '';

		$_w['Users'] = '';

		$_w['Blog'] = '';
		
		$_w['Service'] = '';
		$_w['Industries'] = '';
		
		$_w['Add Service'] = '';

		$_w['Collapse Menu'] = '';

		$_w['Best Seller'] = '';

		$_w['New Statistics'] = '';

		//Main Menu List End

		//SubMenu List

		$_w['Product View'] = '';

		$_w['Product Sort'] = '';

		$_w['Add New Product'] = '';

		$_w['Product Discount'] = '';

		$_w['Product Whole Sale'] = '';

		$_w['Product Coupon'] = '';

		$_w['Manage Currency'] = '';

		$_w['Manage Scales'] = '';

		$_w['Manage Color'] = '';

		$_w['Manage Category'] = '';

		$_w['View Stock Product'] = '';

		$_w['Purchase Receipt'] = '';

		$_w['Store Location'] = '';

		$_w['Import/Export'] = '';

		$_w['Inprocess / All Orders'] = '';

		$_w[''] = '';

		$_w['Orders'] = '';

		$_w['Shipping By Weight'] = '';

		$_w['Shipping By Class'] = '';

		$_w['Main Menu'] = '';

		$_w['Footer Menu'] = '';

		$_w['Defect Archive'] = '';

		$_w['Defect Register'] = '';

		$_w['Return Archive'] = '';

		$_w['Product Return Form'] = '';

		$_w['Product Defect Form'] = '';

		$_w['Pages'] = '';

		$_w['New Page'] = '';

		$_w['Home Page'] = '';

		$_w['News & Events'] = '';

		$_w['News'] = '';

		$_w['Add News'] = '';

		$_w['Banners'] = '';

		$_w['Brands'] = '';

		$_w['Media'] = '';

		$_w['Gallery'] = '';

		$_w['Images'] = '';

		$_w['Files'] = '';

		$_w['SEO'] = '';

		$_w['IBMS Setting'] = '';

		$_w['Form Data'] = '';

		$_w['All Forms'] = '';

		$_w['History'] = '';
		
		$_w['Reports'] = '';
		
		$_w['View Reports'] = '';
		
		$_w['Add Reports'] = '';

		$_w['Account'] = '';

		$_w['Translate Language'] = '';

		$_w['Subscribe Emails'] = '';

		$_w['News Letter'] = '';

		$_w['Products List'] = '';

		$_w['Emails Content'] = '';

		$_w['Users'] = '';

		$_w['Admin Users'] = '';

		$_w['Admin Group'] = '';

		$_w['Web Users'] = '';

		$_w['Blog'] = '';

		$_w['Collapse Menu'] = '';

		$_w['Reviews'] = '';
		
		$_w['Shop Selling'] = '';

		$_w['Questions'] = '';

		$_w['File Manager'] = '';

		$_w['Testimonial'] = '';
		
		$_w['Industries'] = '';

		$_w['Measurement'] = '';

		$_w['Deal Product'] = '';

		$_w['Gift Card Management'] = '';

		$_w['Gift Card'] = '';

		$_w['All In One Product Returns'] = '';

		$_w['Emails in Waiting'] = '';

		$_w['View Emails'] = '';

		$_w['Table View'] = '';

		$_w['Create Insertions'] = '';

		$_w['List View'] = '';

		$_w['Manage Categories'] = '';

		$_w['Product Statistics'] = '';
		
		$_w['Product Mass Update'] = '';
		
		$_w['FAQ'] = '';
		
        $_w['FAQ Add'] = '';
        
        $_w['Milestones Add'] = '';
        
        $_w['Milestones'] = '';

		//SubMenu List End

		$_e = $this->dbF->hardWordsMulti($_w, $adminPanelLanguage, 'AdminMenu');

		//Files Modification restriction here

		$md5PageStatus = $this->functions->checkCurrentFileMd5();

		if ($md5PageStatus == false) {

			$md5PageStatus = $this->functions->checkCurrentFileMd5(true);

			echo "Your Page is modified and cant Be show,

                        Please Undo your changing in files. If you want to modify any changes, please contact to Imedia. <br>";

			//find Actual File Where edit made

			exit;
		}

		//check developer Setting

		if (isset($_SESSION['admin']['setting'])) {

			//making secure session

			if ($_SESSION['admin']['setting'] != '1' && isset($_SESSION['admin']['settingStatus'])) {

				echo 'Developer Setting table value is change, please Undo Your Changes to continue.';

				exit;
			} elseif ($_SESSION['admin']['setting'] == '0') {

				$_SESSION['admin']['setting'] = '1';

				$_SESSION['admin']['settingStatus'] = 'ok';
			}
		} else {

			$developerSetting = $this->functions->encryptDeveloperSetting();

			if ($developerSetting) {

				$_SESSION['admin']['setting'] = uniqid();
			} else {

				echo 'Developer Setting table value is change, please Undo Your Changes to continue.';

				exit;
			}
		}

		//Files Modification restriction here End

	}

	public $AutoVisibleMenu;

	public $AutoVisibleMenuLink;

	public $AutoVisibleMenuName;

	public $parentMenu;

	public function autoVisibleMenuArray()
	{

		$this->menu();

		return $this->AutoVisibleMenu;
	}

	public function visibleForThisProject()
	{

		//Not in use

		global $_e;
	}

	/*

    * menu

    * dropDown icon <span class = 'fa fa-chevron-down drop_menu'></span>

    * after dropDown ul <span class = 'fa fa-caret-left collapse_icon'></span>

    *

    * */

	public function menu()
	{

		global $_e;

		$this->AutoVisibleMenu = null;

		$this->AutoVisibleMenuLink = null;

		$this->AutoVisibleMenuName = null;

		return '



        <div id="IBMS_Menu" class="">

        <ul>

            <li class="' . $this->checkActive('Dashboard', 'Dashboard') . '">

                <a href="index" data-page="index" title="IBMS - ' . $_e['Dashboard'] . '"><h3><span class="fa fa-tachometer icon"></span> <span class="menu_h3">' . $_e['Dashboard'] . '</span></h3></a>

            </li>

            <li class="' . $this->checkActive('product', 'Products',true) . '">

                <h3><span class="fa fa-cube icon"></span><span class="menu_h3">' . $_e['Products'] . '</span> <span class="fa fa-chevron-down drop_menu"></span> </h3>

                <ul>

                    <span class="fa fa-caret-left collapse_icon"></span>

                    <li ' . $this->checkSubMenu('Product View', '-product?page=list') . '><a href="-product?page=list" data-page="-product?page=list" title="IBMS - ' . $_e['Product View'] . '">' . $_e['Product View'] . '</a></li>

                    <li ' . $this->checkSubMenu('New Product', '-product?page=add') . '><a href="-product?page=add" data-page="-product?page=add" title="IBMS - ' . $_e['Add New Product'] . '">' . $_e['Add New Product'] . '</a></li>
                    
                    <li ' . $this->checkSubMenu('Sort Products Image', '-product?page=update') . '><a href="-product?page=update" data-page="-product?page=update" title="IBMS - ' . $_e['Sort Products Image'] . '">' . $_e['Sort Products Image'] . '</a></li>

                    <li ' . $this->checkSubMenu('Deal Product', '-productDeal?page=deal',false) . '><a href="-productDeal?page=deal" data-page="-productDeal?page=deal" title="IBMS - ' . $_e['Deal Product'] . '">' . $_e['Deal Product'] . '</a></li>

                    <li></li>

                    <li class="text-center disabled">' . $_e['Setting'] . '<a></a></li>

                    <li></li>

                    <li ' . $this->checkSubMenu('Product Sort', '-product?page=sort') . '><a href="-product?page=sort" data-page="-product?page=sort" title="IBMS - ' . $_e['Product Sort'] . '">' . $_e['Product Sort'] . '</a></li>

                    <li ' . $this->checkSubMenu('Manage Currency', '-product_management?page=currency',false) . '><a href="-product_management?page=currency" data-page="-product_management?page=currency" title="IBMS - ' . $_e['Manage Currency'] . '">' . $_e['Manage Currency'] . '</a></li>

                    <li ' . $this->checkSubMenu('Manage Scales', '-product_management?page=scale',false) . '><a href="-product_management?page=scale" data-page="-product_management?page=scale" title="IBMS - ' . $_e['Manage Scales'] . '">' . $_e['Manage Scales'] . '</a></li>

                    <li ' . $this->checkSubMenu('Manage Color', '-product_management?page=color',false) . '><a href="-product_management?page=color" data-page="-product_management?page=color" title="IBMS - ' . $_e['Manage Color'] . '">' . $_e['Manage Color'] . '</a></li>

                    <li ' . $this->checkSubMenu('managecat', '-categories?page=managecat',false) . '><a href="-categories?page=managecat" data-page="-categories?page=managecat" title="IBMS - ' . $_e['Manage Categories'] . '">' . $_e['Manage Categories'] . '</a></li>

                    <li ' . $this->checkSubMenu('Product Discount', '-product?page=pDiscount',false) . '><a href="-product?page=pDiscount" data-page="-product?page=pDiscount" title="IBMS - ' . $_e['Product Discount'] . '">' . $_e['Product Discount'] . '</a></li>

                    <li ' . $this->checkSubMenu('Product Sale', '-product?page=pSale',false) . '><a href="-product?page=pSale" data-page="-product?page=pSale" title="IBMS - ' . $_e['Product Whole Sale'] . '">' . $_e['Product Whole Sale'] . '</a></li>

                    <li ' . $this->checkSubMenu('Product Coupon', '-product?page=pCoupon',false) . '><a href="-product?page=pCoupon" data-page="-product?page=pCoupon" title="IBMS - ' . $_e['Product Coupon'] . '">' . $_e['Product Coupon'] . '</a></li>

                    <li ' . $this->checkSubMenu('Measurement', '-measurement?page=measurement',false) . '><a href="-measurement?page=measurement" data-page="-measurement?page=measurement" title="IBMS - ' . $_e['Measurement'] . '">' . $_e['Measurement'] . '</a></li>

                    <li ' . $this->checkSubMenu('bestsellers', '-bestseller?page=bestsellers',false) . '><a href="-bestseller?page=bestsellers" data-page="-bestseller?page=bestsellers" title="IBMS - ' . $_e['Best Seller'] . '">' . $_e['Best Seller'] . '</a></li>
                  
                    <li ' . $this->checkSubMenu('recommendss', '-recommends?page=recommendss',false) . '><a href="-recommends?page=recommendss" data-page="-recommends?page=recommendss" title="IBMS - ' . $_e['Recommended Products'] . '">' . $_e['Recommended Products'] . '</a></li>
                  
                    <li ' . $this->checkSubMenu('impExp', '-productPortation?page=csv',false) . '><a href="-productPortation?page=csv" data-page="-productPortation?page=csv" title="IBMS - ' . $_e['Import/Export'] . '">' . $_e['Import/Export'] . '</a></li>
                    
                    <li ' . $this->checkSubMenu('Product Mass Update', '-product?page=massUpdate',false) . '><a href="-product?page=massUpdate" data-page="-product?page=massUpdate" title="IBMS - ' . $_e['Product Mass Update'] . '">' . $_e['Product Mass Update'] . '</a></li>
                </ul>

            </li>

           <li class="' . $this->checkActive('stock', 'Stock Management',false) . '">

                <h3><span class="fa fa-cubes icon"></span><span class="menu_h3">' . $_e['Stock Management'] . '</span>

                    <span class="fa fa-chevron-down drop_menu"></span>

                </h3>

                <ul>

                    <span class="fa fa-caret-left collapse_icon"></span>

                    <li ' . $this->checkSubMenu('inventory', '-stock?page=inventory') . '><a href="-stock?page=inventory" data-page="-stock?page=inventory" title="IBMS - ' . $_e['View Stock Product'] . '">' . $_e['View Stock Product'] . '</a></li>

                    <li ' . $this->checkSubMenu('purchase receipt', '-stock?page=purchaseReceipt') . '><a href="-stock?page=purchaseReceipt" data-page="-stock?page=purchaseReceipt" title="IBMS - ' . $_e['Purchase Receipt'] . '">' . $_e['Purchase Receipt'] . '</a></li>

                    <li ' . $this->checkSubMenu('add store', '-stock?page=addStore') . '><a href="-stock?page=addStore" data-page="-stock?page=addStore" title="IBMS - ' . $_e['Store Location'] . '">' . $_e['Store Location'] . '</a></li>

                    <li ' . $this->checkSubMenu('Import/Export', '-stock?page=csv') . '><a href="-stock?page=csv" data-page="-stock?page=csv" title="IBMS - ' . $_e['Import/Export'] . '">' . $_e['Import/Export'] . '</a></li>

                </ul>

            </li>

            <li class="' . $this->checkActive('orderManagement', 'Order Management', false) . '">

                <h3><span class="fa fa-shopping-cart icon"></span><span class="menu_h3">' . $_e['Order Management'] . '</span>

                 <span class="fa fa-chevron-down drop_menu"></span></h3>

                <ul>

                    <span class="fa fa-caret-left collapse_icon"></span>

                    <li ' . $this->checkSubMenu('newOrder', '-order?page=newOrder') . '><a href="-order?page=newOrder" data-page="-order?page=newOrder" title="IBMS - ' . $_e['Inprocess / All Orders'] . '">' . $_e['Inprocess / All Orders'] . '</a></li>
                    <li ' . $this->checkSubMenu('otherOrder', '-order?page=otherOrder') . '><a href="-order?page=otherOrder" data-page="-order?page=otherOrder" title="IBMS - ' . $_e[''] . '">Other Orders</a></li>
                    <li ' . $this->checkSubMenu('readyOrders', '-order?page=readyOrders', true) . '><a href="-order?page=readyOrders" data-page="-order?page=readyOrders" title="IBMS - Other Orders">Ready Orders</a></li> 
                    <li ' . $this->checkSubMenu('semireadyOrders', '-order?page=semireadyOrders', true) . '><a href="-order?page=semireadyOrders" data-page="-order?page=semireadyOrders" title="IBMS - Semi Ready Orders">Semi Ready Orders</a></li> 
                    <li ' . $this->checkSubMenu('relativereadyOrders', '-order?page=relativereadyOrders', true) . '><a href="-order?page=relativereadyOrders" data-page="-order?page=relativereadyOrders" title="IBMS - Relative Ready Orders">Relative Ready Orders</a></li> 
                    <li ' . $this->checkSubMenu('Import/Export', '-order?page=csv') . '><a href="-order?page=csv" data-page="-order?page=csv" title="IBMS - ' . $_e[''] . '">' . $_e['Import/Export'] . '</a></li>
                    <li ' . $this->checkSubMenu('Denied Order', '-order?page=visiting') . '><a href="-order?page=visiting" data-page="-order?page=visiting" title="IBMS - ' . $_e['Visiting Customers'] . '">' . $_e['Visiting Customers'] . '</a></li>

                </ul>

            </li>
            

    <li class="' . $this->checkActive('adminorderManagement', 'Shop Selling', false) . '">

                <h3><span class="fa fa-shopping-cart icon"></span><span class="menu_h3">' . $_e['Shop Selling'] . '</span>

                 <span class="fa fa-chevron-down drop_menu"></span></h3>

                <ul>

                    <span class="fa fa-caret-left collapse_icon"></span>

                    <li ' . $this->checkSubMenu('newOrder', '-adminorder?page=newOrder') . '><a href="-adminorder?page=newOrder" data-page="-adminorder?page=newOrder" title="IBMS - ' . $_e['Add New Orders'] . '">' . $_e['Add New Orders'] . '</a></li>
                    
                    

                </ul>

            </li>


            <li class="' . $this->checkActive('statics', 'Statics', false) . '">

                <h3><span class="fa fa-bar-chart-o icon"></span><span class="menu_h3">' . $_e['Statics'] . '</span>

                 <span class="fa fa-chevron-down drop_menu"></span></h3>

                <ul>

                    <span class="fa fa-caret-left collapse_icon"></span>

                    <li ' . $this->checkSubMenu('statics', '-statics?page=statics') . '><a href="-statics?page=statics" data-page="-statics?page=statics" title="IBMS - ' . $_e['Statics'] . '">' . @$_e['Statics'] . '</a></li>

                </ul>

            </li>



            <li class="' . $this->checkActive('statisticM', 'New Statistics', false) . '">

                <h3><span class="fa fa-bar-chart-o icon"></span><span class="menu_h3">' . $_e['New Statistics'] . '</span>

                 <span class="fa fa-chevron-down drop_menu"></span></h3>

                <ul>

                    <span class="fa fa-caret-left collapse_icon"></span>

                    <li ' . $this->checkSubMenu('statistic', '-statistic?page=statistics') . '><a href="-statistic?page=statistics" data-page="-statistic?page=statistics" title="IBMS - ' . $_e['List View'] . '">' . $_e['List View'] . '</a></li>

                    <li ' . $this->checkSubMenu('statistics_table_view', '-statistic?page=statistics_table_view') . '><a href="-statistic?page=statistics_table_view" data-page="-statistic?page=statistics_table_view" title="IBMS - ' . $_e['Table View'] . '">' . $_e['Table View'] . '</a></li>

                    <li ' . $this->checkSubMenu('statistics_insertions_view', '-statistic?page=statistics_insertions', false) . '><a href="-statistic?page=statistics_insertions" data-page="-statistic?page=statistics_insertions" title="IBMS - ' . $_e['Create Insertions'] . '">' . $_e['Create Insertions'] . '</a></li>

                    <li ' . $this->checkSubMenu('produt_statistics', '-product_stats?page=statistics', true) . '><a href="-product_stats?page=statistics" data-page="-product_stats?page=statistics" title="IBMS - ' . $_e['Product Statistics'] . '">' . $_e['Product Statistics'] . '</a></li>
                    <li ' . $this->checkSubMenu('statistics_inv', '-product_stats?page=statistics_inv', true) . '><a href="-product_stats?page=statistics_inv" data-page="-product_stats?page=statistics_inv" title="IBMS - ' . $_e['Product Inventory Statistics'] . '">' . $_e['Product Inventory Statistics'] . '</a></li>
  <li ' . $this->checkSubMenu('produt_qty_statistics', '-product_qty_stats?page=statistics_qty', true) . '><a href="-product_qty_stats?page=statistics_qty" data-page="-product_qty_stats?page=statistics_qty" title="IBMS - ' . $_e['Product QTY Statistics'] . '">' . $_e['Product QTY Statistics'] . '</a></li>
                </ul>

            </li>





            <li class="' . $this->checkActive('shippingManagement', 'Shipping Management', false) . '">

                <h3><span class="fa fa-truck icon"></span><span class="menu_h3">' . $_e['Shipping Management'] . '</span>

                 <span class="fa fa-chevron-down drop_menu"></span></h3>

                <ul>

                    <span class="fa fa-caret-left collapse_icon"></span>

                    <li ' . $this->checkSubMenu('shipping by weight', '-shipping?page=shipping') . '><a href="-shipping?page=shipping" data-page="-shipping?page=shipping" title="IBMS - ' . $_e['Shipping By Weight'] . '">' . $_e['Shipping By Weight'] . '</a></li>

                    <li ' . $this->checkSubMenu('shipping by class', '-shipping?page=shippingByClass') . '><a href="-shipping?page=shippingByClass" data-page="-shipping?page=shippingByClass" title="IBMS - ' . $_e['Shipping By Class'] . '">' . $_e['Shipping By Class'] . '</a></li>
                    <li ' . $this->checkSubMenu('order tracking', '-shipping?page=orderTracking') . '><a href="-shipping?page=orderTracking" data-page="-shipping?page=orderTracking" title="IBMS - ' . $_e['Order Tracking Number'] . '">' . $_e['Order Tracking Number'] . '</a></li>

                </ul>

            </li>
            
            <li class="' . $this->checkActive('filterManagement', 'Filter Management', false) . '">

                <h3><span class="fa fa-filter icon"></span><span class="menu_h3">' . $_e['Filter Management'] . '</span>

                 <span class="fa fa-chevron-down drop_menu"></span></h3>

                <ul>
                    <span class="fa fa-caret-left collapse_icon"></span>

                    <li ' . $this->checkSubMenu('manage filter', '-filter?page=filter') . '><a href="-filter?page=filter" data-page="-filter?page=filter" title="IBMS - ' . $_e['Manage Filters'] . '">' . $_e['Manage Filters'] . '</a></li>
                </ul>

            </li>



            <li class="' . $this->checkActive('webMenuM', 'Menu') . '">

                <h3><span class="fa fa-navicon icon"></span><span class="menu_h3">' . $_e['Menu'] . '</span>

                 <span class="fa fa-chevron-down drop_menu"></span></h3>

                <ul>

                    <span class="fa fa-caret-left collapse_icon"></span>

                    <li ' . $this->checkSubMenu('menu', '-menu?page=menu') . '><a href="-menu?page=menu" data-page="-menu?page=menu" title="IBMS - ' . $_e['Main Menu'] . '">' . $_e['Main Menu'] . '</a></li>

                    <li ' . $this->checkSubMenu('footerMenu', '-menu?page=footerMenu') . '><a href="-menu?page=footerMenu" data-page="-menu?page=footerMenu" title="IBMS - ' . $_e['Footer Menu'] . '">' . $_e['Footer Menu'] . '</a></li>

                </ul>

            </li>



            <li class="' . $this->checkActive('logs', 'Logs Management', false) . '">

                <h3><span class="fa fa-bug icon"></span><span class="menu_h3">' . $_e['Logs Management'] . '</span>

                 <span class="fa fa-chevron-down drop_menu"></span></h3>

                <ul>

                    <span class="fa fa-caret-left collapse_icon"></span>

                    <li ' . $this->checkSubMenu('defectArchive', '-logs?page=defectArchive') . '><a href="-logs?page=defectArchive" data-page="-logs?page=defectArchive" title="IBMS - ' . $_e['Defect Archive'] . '">' . $_e['Defect Archive'] . '</a></li>

                    <li ' . $this->checkSubMenu('defectReg', '-logs?page=defectReg') . '><a href="-logs?page=defectReg" data-page="-logs?page=defectReg" title="IBMS - ' . $_e['Defect Register'] . '">' . $_e['Defect Register'] . '</a></li>

                    <li ' . $this->checkSubMenu('returnReg', '-logs?page=returnReg') . '><a href="-logs?page=returnReg" data-page="-logs?page=returnReg" title="IBMS - ' . $_e['Return Archive'] . '">' . $_e['Return Archive'] . '</a></li>

                    <li ' . $this->checkSubMenu('productReturn', '-logs?page=productReturn') . '><a href="-logs?page=productReturn" data-page="-logs?page=productReturn" title="IBMS - ' . $_e['Product Return Form'] . '">' . $_e['Product Return Form'] . '</a></li>

                    <li ' . $this->checkSubMenu('productDefect', '-logs?page=productDefect') . '><a href="-logs?page=productDefect" data-page="-logs?page=productDefect" title="IBMS - ' . $_e['Product Defect Form'] . '">' . $_e['Product Defect Form'] . '</a></li>

                    <li ' . $this->checkSubMenu('all_returns', '-logs?page=all_returns') . '><a href="-logs?page=all_returns" data-page="-logs?page=all_returns" title="IBMS - ' . $_e['All In One Product Returns'] . '">' . $_e['All In One Product Returns'] . '</a></li>

                </ul>

            </li>

            <li class="' . $this->checkActive('filesM', 'Reports', false) . '">
                <h3><span class="fa fa-file icon"></span><span class="menu_h3">' . $_e['Reports'] .'</span>
                <span class="fa fa-chevron-down drop_menu"></span></h3>
                <ul>
                    <span class="fa fa-caret-left collapse_icon"></span>
                    <li ' . $this->checkSubMenu('files', "-files?page=files") . '><a href="-files?page=files" data-page="-files?page=files" title="IBMS - ' . $_e['View Reports'] . '">' . $_e['View Reports'] .'</a></li>
                    <li ' . $this->checkSubMenu('addFiles', "-files?page=addFiles") . '><a href="-files?page=addFiles" data-page="-files?page=addFiles" title="IBMS - ' . $_e['Add Reports'] . '">' . $_e['Add Reports'] .'</a></li>
                </ul>
            </li>

            <li class="' . $this->checkActive('pages', 'Pages') . '">

                <h3><span class="fa fa-file-text icon"></span><span class="menu_h3">' . $_e['Pages'] . '</span>

                 <span class="fa fa-chevron-down drop_menu"></span></h3>

                <ul>

                    <span class="fa fa-caret-left collapse_icon"></span>

                    <li ' . $this->checkSubMenu('page', '-pages?page=page') . '><a href="-pages?page=page" data-page="-pages?page=page" title="IBMS - ' . $_e['Pages'] . '">' . $_e['Pages'] . '</a></li>

                    <li ' . $this->checkSubMenu('pageNew', '-pages?page=pageNew') . '><a href="-pages?page=pageNew" data-page="-pages?page=pageNew" title="IBMS - ' . $_e['New Page'] . '">' . $_e['New Page'] . '</a></li>

                    <li ' . $this->checkSubMenu('homePage', '-pages?page=homePage') . '><a href="-pages?page=homePage" data-page="-pages?page=homePage" title="IBMS - ' . $_e['Home Page'] . '">' . $_e['Home Page'] . '</a></li>

                    <li ' . $this->checkSubMenu('brands', '-brands?page=brands', true) . '><a href="-brands?page=brands" data-page="-brands?page=brands" title="IBMS - ' . $_e['Brands'] . '">' . $_e['Brands'] . '</a></li>

                    <li ' . $this->checkSubMenu('fileManager', '-fileManager?page=fileManager', false) . '><a href="-fileManager?page=fileManager" data-page="-fileManager?page=fileManager" title="IBMS - ' . $_e['File Manager'] . '">' . $_e['File Manager'] . '</a></li>

                    <li ' . $this->checkSubMenu('testimonial', '-testimonial?page=testimonial', true) . '><a href="-testimonial?page=testimonial" data-page="-testimonial?page=testimonial" title="IBMS - ' . $_e['Industries'] . '">' . $_e['Industries'] . '</a></li>

                 </ul>

            </li>
            
            <li class="' . $this->checkActive('faqM','FAQ', false) . '">
                <h3><span class="fa fa-image icon"></span><span class="menu_h3">' . 'FAQ' .'</span>
                 <span class="fa fa-chevron-down drop_menu"></span></h3>
                <ul>
                    <span class="fa fa-caret-left collapse_icon"></span>
             <li ' . $this->checkSubMenu('faq',"-faq?page=faq") . '><a href="-faq?page=faq" data-page="-faq?page=faq" title="IBMS - ' . $_e['FAQ'] . '">' . $_e['FAQ Add'] .'</a></li>
                </ul>
            </li>
            
            
            <li class="' . $this->checkActive('milestones','Milestones') . '">
                <h3><span class="fa fa-image icon"></span><span class="menu_h3">' . 'Milestones/Awards' .'</span>
                 <span class="fa fa-chevron-down drop_menu"></span></h3>
                <ul>
                    <span class="fa fa-caret-left collapse_icon"></span>
             <li ' . $this->checkSubMenu('milestones',"-milestones?page=milestones") . '><a href="-milestones?page=milestones" data-page="-milestones?page=milestones" title="IBMS - ' . $_e['Milestones'] . '">' . $_e['Milestones Add'] .'</a></li>
                </ul>
            </li>
            
            <li class="' . $this->checkActive('serviceM', 'Service', false) . '">

                <h3><span class="fa fa-rss icon"></span><span class="menu_h3">' . $_e['Service'] . '</span>

                 <span class="fa fa-chevron-down drop_menu"></span></h3>

                <ul>

                    <span class="fa fa-caret-left collapse_icon"></span>

                    <li ' . $this->checkSubMenu('service', '-service?page=service') . '><a href="-service?page=service" data-page="-service?page=service" title="IBMS - ' . $_e['Service'] . '">' . $_e['Service'] . '</a></li>

                </ul>

            </li>
            
            <li class="' . $this->checkActive('industriesM', 'Industries', true) . '">

                <h3><span class="fa fa-rss icon"></span><span class="menu_h3">News & Events</span>

                 <span class="fa fa-chevron-down drop_menu"></span></h3>

                <ul>

                    <span class="fa fa-caret-left collapse_icon"></span>

                    <li ' . $this->checkSubMenu('industries', '-industries?page=industries') . '><a href="-industries?page=industries" data-page="-industries?page=industries" title="IBMS - ' . $_e['Industries'] . '">' . $_e['Industries'] . '</a></li>

                </ul>

            </li>
            
             <li class="' . $this->checkActive('surveyFormM', 'Survey Form', true) . '">
                <h3><span class="fa fa-clipboard icon"></span><span class="menu_h3">' . $_e['Form Data'] . '</span>
                 <span class="fa fa-chevron-down drop_menu"></span></h3>
                <ul>
                    <span class="fa fa-caret-left collapse_icon"></span>
                    <li ' . $this->checkSubMenu('formM', "-surveyForm?page=surveyForm", true) . '><a href="-surveyForm?page=surveyForm" data-page="-surveyForm?page=surveyForm" title="IBMS - ' . $_e['All Forms'] . '">' . $_e['All Forms'] . '</a></li>
                </ul>
            </li>



            <li class="' . $this->checkActive('newsM', 'News & Events', false) . '">

                <h3><span class="fa fa-clipboard icon"></span><span class="menu_h3">' . $_e['News & Events'] . '</span>

                 <span class="fa fa-chevron-down drop_menu"></span></h3>

                <ul>

                    <span class="fa fa-caret-left collapse_icon"></span>

                    <li ' . $this->checkSubMenu('news', '-news?page=news') . '><a href="-news?page=news" data-page="-news?page=news" title="IBMS - ' . $_e['News'] . '">' . $_e['News'] . '</a></li>

                    <li ' . $this->checkSubMenu('addNews', '-news?page=addNews') . '><a href="-news?page=addNews" data-page="-news?page=addNews" title="IBMS - ' . $_e['Add News'] . '">' . $_e['Add News'] . '</a></li>

                </ul>

            </li>



            <li class="' . $this->checkActive('bannersM', 'Banners') . '">

                <h3><span class="fa fa-image icon"></span><span class="menu_h3">' . $_e['Banners'] . '</span>

                 <span class="fa fa-chevron-down drop_menu"></span></h3>

                <ul>

                    <span class="fa fa-caret-left collapse_icon"></span>

                    <li ' . $this->checkSubMenu('banners', '-banners?page=banners') . '><a href="-banners?page=banners" data-page="-banners?page=banners" title="IBMS - ' . $_e['Banners'] . '">' . $_e['Banners'] . '</a></li>

                </ul>

            </li>



            <li class="' . $this->checkActive('emailin_waitingM', 'emailin_waiting', false) . '">

                <h3><span class="fa fa-image icon"></span><span class="menu_h3">' . $_e['Emails in Waiting'] . '</span>

                 <span class="fa fa-chevron-down drop_menu"></span></h3>

                <ul>

                    <span class="fa fa-caret-left collapse_icon"></span>

                    <li ' . $this->checkSubMenu('emailin_waiting', '-emailin_waiting?page=emailin_waiting') . '><a href="-emailin_waiting?page=emailin_waiting" data-page="-emailin_waiting?page=emailin_waiting" title="IBMS - ' . $_e['View Emails'] . '">' . $_e['View Emails'] . '</a></li>

                </ul>

            </li>





            <li class="' . $this->checkActive('galleryM', 'Media') . '">

                <h3><span class="fa fa-image icon"></span><span class="menu_h3">' . $_e['Media'] . '</span>

                 <span class="fa fa-chevron-down drop_menu"></span></h3>

                <ul>

                    <span class="fa fa-caret-left collapse_icon"></span>

                     <li ' . $this->checkSubMenu('gallery', '-gallery?page=gallery', true) . '><a href="-gallery?page=gallery" data-page="-gallery?page=gallery" title="IBMS - ' . $_e['Gallery'] . '">' . $_e['Gallery'] . '</a></li>

                     <li ' . $this->checkSubMenu('Images', 'editor/kcfinder/browse.php?type=images') . '><a onclick="openWin(\'editor/kcfinder/browse.php?type = images\')">' . $_e['Images'] . '</a></li>

                     <li ' . $this->checkSubMenu('Files', 'editor/kcfinder/browse.php?type=files') . '><a onclick="openWin(\'editor/kcfinder/browse.php?type = files\')">' . $_e['Files'] . '</a></li>

                </ul>

            </li>



            <li class="' . $this->checkActive('seoM', 'SEO Management') . '">

                <h3><span class="fa fa-globe icon"></span><span class="menu_h3">' . $_e['SEO Management'] . '</span>

                 <span class="fa fa-chevron-down drop_menu"></span></h3>

                <ul>

                    <span class="fa fa-caret-left collapse_icon"></span>

                    <li ' . $this->checkSubMenu('seo', '-seo?page=seo') . '><a href="-seo?page=seo" data-page="-seo?page=seo" title="IBMS - ' . $_e['SEO'] . '">' . $_e['SEO'] . '</a></li>

                </ul>

            </li>



             <li class="' . $this->checkActive('giftCardM', 'Gift Card Management', false) . '">

                <h3><span class="fa fa-gift icon"></span><span class="menu_h3">' . $_e['Gift Card Management'] . '</span>

                 <span class="fa fa-chevron-down drop_menu"></span></h3>

                <ul>

                    <span class="fa fa-caret-left collapse_icon"></span>

                    <li ' . $this->checkSubMenu('giftCard', '-giftcard?page=giftCard') . '><a href="-giftcard?page=giftCard" data-page="-giftcard?page=giftCard" title="IBMS - ' . $_e['Gift Card'] . '">' . $_e['Gift Card'] . '</a></li>

                </ul>

            </li>



           <li class="' . $this->checkActive('adminSetting', 'Setting') . '">

                <h3><span class="fa fa-gears icon"></span><span class="menu_h3">' . $_e['Setting'] . '</span>

                 <span class="fa fa-chevron-down drop_menu"></span></h3>

                <ul>

                    <span class="fa fa-caret-left collapse_icon"></span>

                    <li ' . $this->checkSubMenu('IBMSSetting', '-setting?page=IBMSSetting') . '><a href="-setting?page=IBMSSetting" data-page="-setting?page=IBMSSetting" title="IBMS - ' . $_e['IBMS Setting'] . '">' . $_e['IBMS Setting'] . '</a></li>

                    <li ' . $this->checkSubMenu('history', '-setting?page=history') . '><a href="-setting?page=history" data-page="-setting?page=history" title="IBMS - ' . $_e['History'] . '">' . $_e['History'] . '</a></li>

                    <li ' . $this->checkSubMenu('account', '-setting?page=account') . '><a href="-setting?page=account" data-page="-setting?page=account" title="IBMS - ' . $_e['Account'] . '">' . $_e['Account'] . '</a></li>

                    <li ' . $this->checkSubMenu('hardWords', '-setting?page=hardWords') . '><a href="-setting?page=hardWords" data-page="-setting?page=hardWords" title="IBMS - ' . $_e['Translate Language'] . '">' . $_e['Translate Language'] . '</a></li>

                </ul>

            </li>



            <li class="' . $this->checkActive('emailM', 'Subscribe Email', false) . '">

                <h3><span class="fa fa-envelope icon"></span><span class="menu_h3">' . $_e['Email Management'] . '</span>

                 <span class="fa fa-chevron-down drop_menu"></span></h3>

                <ul>

                    <span class="fa fa-caret-left collapse_icon"></span>

                    <li ' . $this->checkSubMenu('email', '-email?page=email') . '><a href="-email?page=email" data-page="-email?page=email" title="IBMS - ' . $_e['Subscribe Emails'] . '">' . $_e['Subscribe Emails'] . '</a></li>



                    <!-- For Stop News Letter, make false on both, news letter and all product info -->

                    <li ' . $this->checkSubMenu('newsLetter', '-email?page=newsLetter', true) . '><a href="-email?page=newsLetter" data-page="-email?page=newsLetter" title="IBMS - ' . $_e['News Letter'] . '">' . $_e['News Letter'] . '</a></li>

                    <li ' . $this->checkSubMenu('allProductsInfo', '-product?page=allProductsInfo', true) . '><a href="-product?page=allProductsInfo" data-page="-product?page=allProductsInfo" title="IBMS - ' . $_e['Products List'] . '">' . $_e['Products List'] . '</a></li>

                    <li ' . $this->checkSubMenu('emailContent', '-email?page=emailMsg') . '><a href="-email?page=emailContent" data-page="-email?page=emailContent" title="IBMS - ' . $_e['Emails Content'] . '">' . $_e['Emails Content'] . '</a></li>

                </ul>

            </li>



            <li class="' . $this->checkActive('webUserM', 'Users', false) . '">

                <h3><span class="fa fa-users icon"></span><span class="menu_h3">' . $_e['Users'] . '</span>

                 <span class="fa fa-chevron-down drop_menu"></span></h3>

                <ul>

                    <span class="fa fa-caret-left collapse_icon"></span>

                    <li ' . $this->checkSubMenu('AdminUsers', '-webUsers?page=AdminUsers') . '><a href="-webUsers?page=AdminUsers" data-page="-webUsers?page=AdminUsers" title="IBMS - ' . $_e['Admin Users'] . '">' . $_e['Admin Users'] . '</a></li>

                    <li ' . $this->checkSubMenu('AdminGrp', '-webUsers?page=AdminGrp') . '><a href="-webUsers?page=AdminGrp" data-page="-webUsers?page=AdminGrp" title="IBMS - ' . $_e['Admin Group'] . '">' . $_e['Admin Group'] . '</a></li>

                    <li ' . $this->checkSubMenu('webUser', '-webUsers?page=view') . '><a href="-webUsers?page=view" data-page="-webUsers?page=view" title="IBMS - ' . $_e['Web Users'] . '">' . $_e['Web Users'] . '</a></li>

                    <li ' . $this->checkSubMenu('reviews', '-webUsers?page=reviews') . '><a href="-webUsers?page=reviews" data-page="-webUsers?page=reviews" title="IBMS - ' . $_e['Reviews'] . '">' . $_e['Reviews'] . '</a></li>

                    <li ' . $this->checkSubMenu('questions', '-webUsers?page=questions') . '><a href="-webUsers?page=questions" data-page="-webUsers?page=questions" title="IBMS - ' . $_e['Questions'] . '">' . $_e['Questions'] . '</a></li>

                </ul>

            </li>



            <li class="' . $this->checkActive('blogM', 'Blog', false) . '">

                <h3><span class="fa fa-rss icon"></span><span class="menu_h3">' . $_e['Blog'] . '</span>

                 <span class="fa fa-chevron-down drop_menu"></span></h3>

                <ul>

                    <span class="fa fa-caret-left collapse_icon"></span>

                    <li ' . $this->checkSubMenu('blog', '-blog?page=blog') . '><a href="-blog?page=blog" data-page="-blog?page=blog" title="IBMS - ' . $_e['Blog'] . '">' . $_e['Blog'] . '</a></li>

                </ul>

            </li>

        </ul>

    </div><!-- #IBMS_Menu -->
    
    
    <script>
        function openWin(url) {
    myWindow = window.open(url, "", "width=800,height=600");
    myWindow.focus();
}
    </script>

';
	}

	private function checkActive($page, $name, $visibleForThisUser = true)
	{

		global $functions;

		$temp = '';

		if ($visibleForThisUser === true) {

			$this->parentMenu = $page;

			$this->AutoVisibleMenu['menu'][] = $page;

			$this->AutoVisibleMenu['hasSubMenu'][$page] = false;

			$this->AutoVisibleMenuName[$page] = $name;

			$class = '';

			$menuReturnPer = $functions->adminMenuPermissions($page, 'mainMenu');

			if ($menuReturnPer === false) {

				$class = 'displaynone';
			}
		} else {

			$this->parentMenu = false;

			$class = 'displaynone';
		}

		global $menu;

		if ($menu == $page) {

			$temp = 'active';
		}

		return $temp . ' ' . $class;
	}

	private function checkSubMenu($page, $link, $visibleForThisUser = true)
	{

		//$page is use as sub menu for active or permissions

		$temp = '';

		$class = '';

		global $functions;

		$parentMenu = $this->parentMenu;

		if ($parentMenu !== false) {

			$this->AutoVisibleMenu['hasSubMenu'][$parentMenu] = true;

			// when this function call, its mean it has sub menu, menu is true or false, it  is else thing

			if ($visibleForThisUser === true) {

				//For take auto array and use where i want

				$this->AutoVisibleMenu[$parentMenu][$page] = $page;

				$this->AutoVisibleMenuLink[$page] = $link;

				$class = '';

				$menuReturnPer = $functions->adminMenuPermissions($link, 'subMenu', $parentMenu);

				if ($menuReturnPer === false) {

					$class = 'displaynone';
				}
			} else {

				$class = 'displaynone';
			}
		} else {

			$class = 'displaynone';
		}

		global $subMenu;

		if ($subMenu == $page) {

			$temp = "class='subMenu $class'";
		} else {

			$temp = "class='$class'";
		}

		return $temp;
	}
}
