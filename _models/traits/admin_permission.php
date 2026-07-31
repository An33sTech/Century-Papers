<?php

trait admin_permission
{
    public function adminPermissions(){
        $id     = $_SESSION['_roleGrp'];
        if($id==='0' || $id === 0){
            return true;
        }
        $sql    = "SELECT * FROM accounts_prm_grp WHERE id = '$id'";
        $userData   =   $this->dbF->getRow($sql);

        return unserialize($userData['permission']);
    }

    public function adminMenuPermissions($pageLink,$menuType,$parent=false){
        global $adminPermissions;
        //var_dump($adminPermissions);
        switch($menuType){
            case 'subMenu':

                if($adminPermissions===true){
                    //if owner
                    return true;
                }
                else if(@$adminPermissions[$parent][$pageLink]==='0'){
                    //if Not allow
                    return false;
                }elseif(@$adminPermissions[$parent][$pageLink]==''){
                    //if not in list
                    return false;
                }else{
                    //else
                    return true;
                }
                break;

            case 'mainMenu':
                if($adminPermissions===true){
                    return true;
                }
                else if(!in_array($pageLink,$adminPermissions['menu'])){
                    return false;
                }else{
                    //else
                    return true;
                }
                break;
        }
    }


    public function pagePermission(){
        //How permission working?
        /**
         * First check menu link in permission, to view menus
         * for page: check link from url and from permission array
         * for edits page when url change, check active menu url if has in permission array then show page.
         *
         * for dashboard check is main menu in menu ? then allow page to show.
         *
         */
        global $adminPermissions;
        $pageLinkAdmin  =   $_SERVER['REQUEST_URI'];
        $pageLinkAdmin  =   str_ireplace($this->db->request_uri_Web_admin,'',$pageLinkAdmin);

        if($adminPermissions===true){
            return true;
        }
        else if(in_array($pageLinkAdmin,$adminPermissions['subMenu'])){
            if(@$adminPermissions['subMenuP'][$pageLinkAdmin]==='0'){
                //if Not allow
                return false;
            }
            return true;
        }else{
            //else
            return false;
        }
    }

    public function pagePermissionStatus(){
        //return page permission value
        //How permission working?
        /**
         * First check menu link in permission, to view menus
         * for page: check link from url and from permission array
         * for edits page when url change, check active menu url if has in permission array then show page.
         *
         * for dashboard check is main menu in menu ? then allow page to show.
         *
         */
        global $subMenu;

        global $adminPermissions;
        global $ActivePagePerm;

        $pageLinkAdmin  =   $_SERVER['REQUEST_URI'];
        $pageLinkAdmin  =   str_ireplace($this->db->request_uri_Web_admin,'',$pageLinkAdmin);
        $pageLinkAdmin1  =   explode("?page=",$pageLinkAdmin,2);
        $pageLinkAdmin1  =   $pageLinkAdmin1[0]."?page=".$subMenu;

        if($adminPermissions===true){
            return true;
        }
        else if(in_array($pageLinkAdmin1,$adminPermissions['subMenu'])){
            if(isset($adminPermissions['subMenuP'][$pageLinkAdmin1])){
                return $adminPermissions['subMenuP'][$pageLinkAdmin1];
            }else{
                return 0;
            }
        }else{
            //else
            return 0;
        }
    }

    public function pageInnerPermission($menuC=false){
        //Function call after menu load or actual page load,, it check active menu status
        //and active menu status permissions
        global $subMenu;
        global $menu;
        global $adminPermissions;
        global $ActivePagePerm;

        //Visible menu In project
        $visible = $menuC->AutoVisibleMenu;

        $pageLinkAdmin  =   $_SERVER['REQUEST_URI'];
        $pageLinkAdmin  =   str_ireplace($this->db->request_uri_Web_admin,'',$pageLinkAdmin); //Main link
        $pageLinkAdmin1  =   explode("?page=",$pageLinkAdmin,2);
        $pageLinkAdmin1  =   $pageLinkAdmin1[0]."?page=".$subMenu; //sub menu link


        //Check Is menu Visible from Developer
        if(!in_array($menu,$visible['menu'])){
            //If main menu not in menu array, its mean not allow from developer, from menu class,
            $ActivePagePerm = false;
            return false;
        }elseif(in_array($menu,$visible['menu']) && isset($visible['hasSubMenu'][$menu]) && $visible['hasSubMenu'][$menu] == false){
            //If menu in menu array and not in key array its mean it is just main menu, it has no sub menu,
            //OK ALlOW
        }elseif(isset($visible[$menu]) && !isset($visible[$menu][$subMenu])){
            //Mean has key array but not sub menu,its mean not allow from developer, from menu class,
            $ActivePagePerm = false;
            return false;
        }else if(in_array($menu,$visible['menu']) && $visible['hasSubMenu'][$menu] == true && !isset($visible[$menu][$subMenu])){
            //has in menu array, and has submenu, but not submenu array found, mean blank array, not allow
            $ActivePagePerm = false;
            return false;
        }
        //Else continue and check admin permissions
        //Check Is menu Visible from Developer ENd

        if($adminPermissions===true){
            //owner
            $ActivePagePerm = true;
            return true;
        }
        else if(in_array($pageLinkAdmin,$adminPermissions['subMenu'])){
            //use default if page is normal then no need to check menu page
            return true;
        }


        if(in_array($pageLinkAdmin1,$adminPermissions['subMenu'])){
            if(@$adminPermissions['subMenuP'][$pageLinkAdmin1]==='0'){
                //if Not allow use default permissions from global
            }else{
                $ActivePagePerm = true;
            }
        }else if($subMenu === '' || !isset($subMenu)){
            //if no submenu irect link, like dashboard
            global $menu;
            if(in_array($menu,$adminPermissions['menu'])){
                //use default if page is normal then no need to check menu page
                $ActivePagePerm = true;
            }else{
                $ActivePagePerm = false;
            }
        }else{
            //else if not found submenu, find in menu generate link by meun name
            $pageLinkAdmin1 = $menuC->AutoVisibleMenuLink[$subMenu];
            if(in_array($pageLinkAdmin1,$adminPermissions['subMenu'])){
                if(@$adminPermissions['subMenuP'][$pageLinkAdmin1]==='0'){
                    //if Not allow use default permissions
                }else{
                    $ActivePagePerm = true;
                }
            }else{
                //Still not found, so this is new link default permissions
                echo "allow here in pageInnerPermission functions";
                //$ActivePagePerm = true;

                //this contidiont work when link not found in array, or not found in avaiable array
            }
        }
    }


    public function pageEditPermission($queryCheckForDelete=false){
        //Function call after menu load,, it check active menu status
        //and active menu status permissions
        //Only Post edit restriction manage
        $msg = '';
        if(isset($_POST) && !empty($_POST)){
            global $menuClassGlobal;
            $menuC  = $menuClassGlobal;
            global $subMenu;
            global $adminPermissions;
            global $ActivePagePerm;

            //get page link
            $pageLink       =   $_SERVER['REQUEST_URI'];
            $pageLinkAdmin  =   str_ireplace($this->db->request_uri_Web_admin,'',$pageLink); //remove extra link
            $pageLinkAdmin1  =   explode("?page=",$pageLinkAdmin,2);
            $pageLinkAdmin1  =   $pageLinkAdmin1[0]."?page=".$subMenu; //add page name in link

            //check is admin link or websuer link,,

            $adminUri = $this->db->request_uri_Web_admin;
            if(!preg_match("@$adminUri@",$pageLink)){
                //Request send from website
                return true;
            }

            //If owner
            if($adminPermissions===true){
                $ActivePagePerm = true;
                return true;
            }

            //If sub admin
            if(@in_array($pageLinkAdmin1,$adminPermissions['subMenu'])){
                @$status = $adminPermissions['subMenuP'][$pageLinkAdmin1];
                if(@$status==='0' || $status === '1' ){
                    //if Not allow use default permissions
                    $msg = $this->notificationError('Edit Error',addslashes("You don't have rights to modify any changes"),'btn-danger',false);
                    $_POST = '';
                }
            }else{
                //else if not found submenu, find in menu generate link by meun name
                //var_dump($subMenu);
                @$pageLinkAdmin1 = $menuC->AutoVisibleMenuLink[$subMenu];
                if(@in_array($pageLinkAdmin1,$adminPermissions['subMenu'])){

                    //if Not allow use default permissions
                    @$status = $adminPermissions['subMenuP'][$pageLinkAdmin1];
                    if(@$status==='0' || $status === '1' ){
                        //if Not allow use default permissions
                        $msg = $this->notificationError('Edit Error',addslashes("You don't have rights to modify any changes"),'btn-danger',false);
                        $_POST = '';
                    }
                }else{
                    //Still not found, so this is new link default permissions
                    //may be it is ajax edit
                    $pageLinkAdmin  =   $_SERVER['HTTP_REFERER'];
                    $pageLinkAdmin  =   str_ireplace(WEB_ADMIN_URL.'/','',$pageLinkAdmin);
                    //var_dump($adminPermissions);
                    if(@in_array($pageLinkAdmin,$adminPermissions['subMenu'])){
                        @$status = $adminPermissions['subMenuP'][$pageLinkAdmin];
                        if(@$status==='0' || $status === '1' ){
                            //if Not allow use default permissions
                            $msg = $this->notificationError('Edit Error',addslashes("You don't have rights to modify any changes"),'btn-danger',false);
                            $_POST = '';
                            echo '0';
                            exit;
                        }else if(@$status==='2'){
                            $query  =   $queryCheckForDelete;
                            $firstLetter = strtolower(strtok($query, " "));
                            //if query start with delete on ajax call then stop
                            if($firstLetter=='delete'){
                                echo '0';
                                exit;
                            }
                        }
                    }
                }
            }
        }
        return $msg;
    }

}

?>