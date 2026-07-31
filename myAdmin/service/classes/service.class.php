<?php
require_once(__DIR__ . "/../../global.php"); //connection setting db

class service extends object_class
{
    public $productF;
    public function __construct()
    {
        parent::__construct('3');

        /**
         * MultiLanguage keys Use where echo;
         * define this class words and where this class will call
         * and define words of file where this class will called
         **/
        global $_e;
        global $adminPanelLanguage;
        $_w = array();
        //index page
        $_w['Services Management'] = '';
        //service.php
        $_w['Manage Service'] = '';
        $_w['Active Service'] = '';
        $_w['Pending Service'] = '';
        $_w['Draft Service'] = '';
        $_w['Add New Service'] = '';
        $_w['Delete Fail Please Try Again.'] = '';

        //This Class
        $_w['SNO'] = '';
        $_w['USER'] = '';
        $_w['TITLE'] = '';
        $_w['BLOG DATE'] = '';
        $_w['PUBLISH DATE'] = '';
        $_w['UPDATE'] = '';
        $_w['ACTION'] = '';
        $_w['Service'] = '';
        $_w['Service Save Successfully'] = '';
        $_w['Added'] = '';
        $_w['Service Save Failed'] = '';
        $_w['Service Update Successfully'] = '';
        $_w['Service Update Failed'] = ''; // <-- added

        $_w['Image File Error'] = '';
        $_w['Service Title'] = '';

        $_w['Category'] = '';
        $_w['Other'] = '';
        $_w['Enter Category Name'] = '';
        $_w['Short Description'] = '';
        $_w['Enter Short Description'] = '';
        $_w['Detail'] = '';
        $_w['Enter Full Detail'] = '';
        $_w['Allow Comment'] = '';
        $_w['Publish'] = '';
        $_w['Draft'] = '';

        $_w['Leave Blank to publish now'] = '';
        $_w['Service Image'] = '';
        $_w['Service Icon'] = '';
        $_w['Old Service Image'] = '';
        $_w['Old Service Icon'] = '';
        $_w['SAVE'] = '';
        $_w['Slug'] = '';
        $_w['SLUG'] = '';
        $_w['Service Middle Image'] = '';
        $_w['Old Service Middle Image'] = '';
        $_w['Service Top Image'] = '';
        $_w['Old Service Top Image'] = '';
        $_w['Service Main Image'] = '';
        $_w['Service Banner Video'] = '';
        $_w['Old Service Banner Video'] = '';

        $_e = $this->dbF->hardWordsMulti($_w, $adminPanelLanguage, 'Admin Service');
    }

    public function get_admin_user($user_id)
    {
        if (!$user_id) {
            return false;
        }

        # get admin user
        return $this->dbF->getRow(' SELECT * FROM `accounts` WHERE `acc_id` = ? ', array($user_id));
    }

    public function serviceView()
    {
        global $_e;
        echo '<div class="table-responsive">
                <table class="table table-hover dTable tableIBMS">
                    <thead>
                        <th>' . _u($_e['SNO']) . '</th>
                        <th>' . _u($_e['TITLE']) . '</th>
                        <th>' . _u($_e['SLUG']) . '</th>
                        <th>' . _u($_e['UPDATE']) . '</th>
                        <th>' . _u($_e['ACTION']) . '</th>
                    </thead>
                <tbody>';
        $sql = "SELECT `id`, `slug`, `heading`, `dateTime` FROM `service` WHERE `publish` = '1'";
        $data = $this->dbF->getRows($sql);
        $i = 0;

        $admin_lang = $this->functions->AdminDefaultLanguage();

        foreach ($data as $val) {
            $i++;
            $id = $val['id'];

            $heading = @unserialize($val['heading']);
            $heading = $heading[$admin_lang];

            $slug = $this->db->servicePage . $val['slug'];
            $seoLink = '';
            if ($this->functions->developer_setting('seo') == '1') {
                $this->functions->getAdminFile("seo/classes/seo.class.php");
                $seoC = new seo();
                $seoLink = $seoC->seoQuickLink($id, urlencode("/" . $this->db->servicePage . "$val[slug]"));
            }
            echo "<tr>
                    <td>$i</td>
                    <td>{$heading}</td>
                    <td>{$slug}</td>
                    <td>{$val['dateTime']}</td>
                    <td>
                        <div class='btn-group btn-group-sm'>
                            $seoLink
                            <a data-id='$id' href='-service?page=edit&serviceId=$id' class='btn'>
                                <i class='glyphicon glyphicon-edit'></i>
                            </a>
                            <a data-id='$id' onclick='deleteService(this);' class='btn'>
                                <i class='glyphicon glyphicon-trash trash'></i>
                                <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
                            </a>
                        </div>
                    </td>
                  </tr>";
        }

        echo '</tbody>
             </table>
            </div> <!-- .table-responsive End -->';
    }

    public function servicePending()
    {
        global $_e;
        echo '<div class="table-responsive">
                <table class="table table-hover dTable tableIBMS">
                    <thead>
                        <th>' . _u($_e['SNO']) . '</th>
                        <th>' . _u($_e['TITLE']) . '</th>
                        <th>' . _u($_e['SLUG']) . '</th>
                        <th>' . _u($_e['UPDATE']) . '</th>
                        <th>' . _u($_e['ACTION']) . '</th>
                    </thead>
                <tbody>';
        $today = date('Y-m-d');
        // NOTE: logic for "pending" can be adjusted as needed
        $sql = "SELECT `id`, `slug`, `heading`, `dateTime` FROM `service` WHERE `publish` = '1'";
        $data = $this->dbF->getRows($sql);
        $i = 0;

        $admin_lang = $this->functions->AdminDefaultLanguage();

        foreach ($data as $val) {
            $i++;
            $id = $val['id'];

            $heading = @unserialize($val['heading']);
            $heading = $heading[$admin_lang];
            $slug = $this->db->servicePage . $val['slug'];

            echo "<tr>
                    <td>$i</td>
                    <td>{$heading}</td>
                    <td>{$slug}</td>
                    <td>{$val['dateTime']}</td>
                    <td>
                        <div class='btn-group btn-group-sm'>
                            <a data-id='$id' href='-service?page=edit&serviceId=$id' class='btn'>
                                <i class='glyphicon glyphicon-edit'></i>
                            </a>
                            <a data-id='$id' onclick='deleteService(this);' class='btn'>
                                <i class='glyphicon glyphicon-trash trash'></i>
                                <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
                            </a>
                        </div>
                    </td>
                  </tr>";
        }

        echo '</tbody>
             </table>
            </div> <!-- .table-responsive End -->';
    }

    public function serviceDraft()
    {
        global $_e;
        echo '<div class="table-responsive">
                <table class="table table-hover dTable tableIBMS">
                    <thead>
                        <th>' . _u($_e['SNO']) . '</th>
                        <th>' . _u($_e['TITLE']) . '</th>
                        <th>' . _u($_e['SLUG']) . '</th>
                        <th>' . _u($_e['UPDATE']) . '</th>
                        <th>' . _u($_e['ACTION']) . '</th>
                    </thead>
                <tbody>';
        $sql = "SELECT `id`, `slug`, `heading`, `dateTime` FROM `service` WHERE `publish` = '0'";
        $data = $this->dbF->getRows($sql);
        $i = 0;

        $admin_lang = $this->functions->AdminDefaultLanguage();

        foreach ($data as $val) {
            $i++;
            $id = $val['id'];

            $heading = @unserialize($val['heading']);
            $heading = $heading[$admin_lang];

            $slug = $this->db->servicePage . $val['slug'];

            echo "<tr>
                    <td>$i</td>
                    <td>{$heading}</td>
                    <td>{$slug}</td>
                    <td>{$val['dateTime']}</td>
                    <td>
                        <div class='btn-group btn-group-sm'>
                            <a data-id='$id' href='-service?page=edit&serviceId=$id' class='btn'>
                                <i class='glyphicon glyphicon-edit'></i>
                            </a>
                            <a data-id='$id' onclick='deleteService(this);' class='btn'>
                                <i class='glyphicon glyphicon-trash trash'></i>
                                <i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
                            </a>
                        </div>
                    </td>
                  </tr>";
        }

        echo '</tbody>
             </table>
            </div> <!-- .table-responsive End -->';
    }

    public function newServiceAdd()
    {
        global $_e;
        if (isset($_POST['submit'])) {
            if (!$this->functions->getFormToken('newService')) {
                return false;
            }

            $heading    = empty($_POST['heading'])    ? '' : serialize($_POST['heading']);
            $short_desc = empty($_POST['shortDesc'])  ? '' : serialize($_POST['shortDesc']);
            $dsc        = empty($_POST['dsc'])        ? '' : serialize($_POST['dsc']);
            $publish    = empty($_POST['publish'])    ? "0" : $_POST['publish'];
            $slug       = empty($_POST['slug'])       ? ""  : $_POST['slug'];

            $file   = !empty($_FILES['image']['name']);
            $icon   = !empty($_FILES['icon']['name']);
            $image2 = !empty($_FILES['image2']['name']);
            $video  = !empty($_FILES['video']['name']);

            $imgName  = "";
            $imgName2 = "";
            $imgName3 = "";
            $imgName4 = "";

            try {
                $this->db->beginTransaction();

                if ($file) {
                    $imgName = $this->functions->uploadSingleImage($_FILES['image'], 'service/');
                    if ($imgName == false) {
                        throw new Exception(_uc($_e["Image File Error"]));
                    }
                }

                if ($icon) {
                    $imgName2 = $this->functions->uploadSingleImage($_FILES['icon'], 'serviceicon/');
                    if ($imgName2 == false) {
                        throw new Exception(_uc($_e["Image File Error"]));
                    }
                }

                if ($image2) {
                    $imgName3 = $this->functions->uploadSingleImage($_FILES['image2'], 'serviceimage/');
                    if ($imgName3 == false) {
                        throw new Exception(_uc($_e["Image File Error"]));
                    }
                }

                if ($video) {
                    $imgName4 = $this->functions->uploadSingleImage($_FILES['video'], 'servicevideo/');
                    if ($imgName4 == false) {
                        throw new Exception(_uc($_e["Image File Error"]));
                    }
                }

                $sql = "INSERT INTO `service` (`heading`, `shortDesc`, `dsc`, `image`, `image2`, `video`, `icon`, `publish`) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

                $array = array($heading, $short_desc, $dsc, $imgName, $imgName3, $imgName4, $imgName2, $publish);
                $this->dbF->setRow($sql, $array, false);
                $lastId = $this->dbF->rowLastId;

                // slug handling
                $sql_slug = "SELECT * FROM `service` WHERE `slug` = ?";
                $this->dbF->getRow($sql_slug, array($slug));

                if ($this->dbF->rowCount != 0) {
                    $slug = $slug . "-" . rand(1, 15);
                }

                if ($slug == "") {
                    $slug = $this->db->servicePage . $lastId;
                }

                $sql = "UPDATE `service` SET `slug` = ? WHERE `id` = ?";
                $this->dbF->setRow($sql, array($slug, $lastId), false);

                // SEO entry
                $pageLink = '/' . $this->db->servicePage . $slug;
                $ref_id   = $this->db->servicePage . $lastId;

                $sql1 = "INSERT INTO `seo` (`pageLink`, `ref_id`, `slug`, `title`) VALUES (?, ?, ?, ?)";
                $this->dbF->setRow($sql1, array($pageLink, $ref_id, $slug, $heading), false);

                $this->db->commit();

                if ($this->dbF->rowCount > 0) {
                    $this->functions->notificationError(_uc($_e['Service']), ($_e['Service Save Successfully']), 'btn-success');
                    $this->functions->setlog(_uc($_e['Added']), _uc($_e['Service']), $this->dbF->rowLastId, ($_e['Service Save Successfully']));
                } else {
                    $this->functions->notificationError(_uc($_e['Service']), ($_e['Service Save Failed']), 'btn-danger');
                }
            } catch (Exception $e) {
                if ($imgName && $file) {
                    $this->functions->deleteOldSingleImage($imgName);
                }
                if ($imgName2 && $icon) {
                    $this->functions->deleteOldSingleImage($imgName2);
                }
                if ($imgName3 && $image2) {
                    $this->functions->deleteOldSingleImage($imgName3);
                }
                if ($imgName4 && $video) {
                    $this->functions->deleteOldSingleImage($imgName4);
                }

                $this->db->rollBack();
                $this->dbF->error_submit($e);
                $this->functions->notificationError(_uc($_e['Service']), ($_e['Service Save Failed']), 'btn-danger');
            }
        } // If end
    }

    public function serviceEditSubmit()
    {
        global $_e;
        if (isset($_POST['submit']) && isset($_POST['editId'])) {
            if (!$this->functions->getFormToken('editService')) {
                return false;
            }

            $heading    = empty($_POST['heading'])    ? "" : serialize($_POST['heading']);
            $short_desc = empty($_POST['shortDesc'])  ? "" : serialize($_POST['shortDesc']);
            $dsc        = empty($_POST['dsc'])        ? "" : serialize($_POST['dsc']);
            $publish    = empty($_POST['publish'])    ? "0" : $_POST['publish'];
            $slug       = empty($_POST['slug'])       ? ""  : $_POST['slug'];

            $file    = !empty($_FILES['image']['name']);
            $icon    = !empty($_FILES['icon']['name']);
            $image2  = !empty($_FILES['image2']['name']);
            $video   = !empty($_FILES['video']['name']);

            $oldImg     = empty($_POST['oldImg'])     ? "" : $_POST['oldImg'];
            $oldIcon    = empty($_POST['oldIcon'])    ? "" : $_POST['oldIcon'];
            $oldImage2  = empty($_POST['oldImage2'])  ? "" : $_POST['oldImage2'];
            $oldVideo   = empty($_POST['oldVideo'])   ? "" : $_POST['oldVideo'];

            $imgName  = $oldImg;
            $imgName2 = $oldIcon;
            $imgName3 = $oldImage2;
            $imgName4 = $oldVideo;

            try {
                $this->db->beginTransaction();
                $lastId = $_POST['editId'];

                if ($file) {
                    if ($oldImg != '') {
                        $this->functions->deleteOldSingleImage($oldImg);
                    }
                    $imgName = $this->functions->uploadSingleImage($_FILES['image'], 'service/');
                    if ($imgName == false) {
                        throw new Exception(_uc($_e["Image File Error"]));
                    }
                }

                if ($icon) {
                    if ($oldIcon != '') {
                        $this->functions->deleteOldSingleImage($oldIcon);
                    }
                    $imgName2 = $this->functions->uploadSingleImage($_FILES['icon'], 'serviceicon/');
                    if ($imgName2 == false) {
                        throw new Exception(_uc($_e["Image File Error"]));
                    }
                }

                if ($image2) {
                    if ($oldImage2 != '') {
                        $this->functions->deleteOldSingleImage($oldImage2);
                    }
                    $imgName3 = $this->functions->uploadSingleImage($_FILES['image2'], 'serviceimage/');
                    if ($imgName3 == false) {
                        throw new Exception(_uc($_e["Image File Error"]));
                    }
                }

                if ($video) {
                    if ($oldVideo != '') {
                        $this->functions->deleteOldSingleImage($oldVideo);
                    }
                    $imgName4 = $this->functions->uploadSingleImage($_FILES['video'], 'servicevideo/');
                    if ($imgName4 == false) {
                        throw new Exception(_uc($_e["Image File Error"]));
                    }
                }

                // slug duplicate check
                $ref_id = $this->db->servicePage . $lastId;
                $check  = $this->functions->check_slug_duplicate($slug, $ref_id);

                if (!$check) {
                    $slug = $slug . "-" . rand(1, 15);
                }

                $sql = "UPDATE `service` 
                        SET `heading` = ?, 
                            `shortDesc` = ?, 
                            `dsc` = ?, 
                            `image` = ?, 
                            `image2` = ?, 
                            `video` = ?, 
                            `publish` = ?, 
                            `slug` = ?, 
                            `icon` = ? 
                        WHERE `id` = ?";

                // order must match the placeholders above
                $array = array(
                    $heading,
                    $short_desc,
                    $dsc,
                    $imgName,
                    $imgName3,
                    $imgName4,
                    $publish,
                    $slug,
                    $imgName2,
                    $lastId
                );

                $this->dbF->setRow($sql, $array, false);

                // SEO update
                // $pageLink = '/' . $this->db->servicePage . $slug;

                // $sql1 = "UPDATE `seo` SET `pageLink` = ?, `slug` = ?, `title` = ? WHERE `ref_id` = ?";
                // $this->dbF->setRow($sql1, array($pageLink, $slug, $heading, $ref_id), false);

                $this->db->commit();

                if ($this->dbF->rowCount > 0) {
                    $this->functions->notificationError(_uc($_e['Service']), ($_e['Service Update Successfully']), 'btn-success');
                    $this->functions->setlog(_uc($_e['Added']), _uc($_e['Service']), $this->dbF->rowLastId, ($_e['Service Update Successfully']));
                } else {
                    $this->functions->notificationError(_uc($_e['Service']), ($_e['Service Save Failed']), 'btn-danger');
                }
            } catch (Exception $e) {
                if ($imgName && $file) {
                    $this->functions->deleteOldSingleImage($imgName);
                }
                if ($imgName2 && $icon) {
                    $this->functions->deleteOldSingleImage($imgName2);
                }
                if ($imgName3 && $image2) {
                    $this->functions->deleteOldSingleImage($imgName3);
                }
                if ($imgName4 && $video) {
                    $this->functions->deleteOldSingleImage($imgName4);
                }

                $this->db->rollBack();
                $this->dbF->error_submit($e);
                $this->functions->notificationError(_uc($_e['Service']), ($_e['Service Update Failed']), 'btn-danger');
            }
        }
    }

    public function serviceNew()
    {
        global $_e;
        $token = $this->functions->setFormToken('newService', false);

        echo '<form method="post" class="form-horizontal" role="form" enctype="multipart/form-data">' .
            $token .
            '<div class="form-horizontal">

            <div class="panel-group" id="accordion">';

        $lang = $this->functions->IbmsLanguages();
        if ($lang != false) {
            $lang_size = sizeof($lang);
            for ($i = 0; $i < $lang_size; $i++) {
                $collapseIn = ($i == 0) ? ' in ' : '';

                echo '<div class="panel panel-default">
                        <div class="panel-heading">
                             <a data-toggle="collapse" data-parent="#accordion" href="#' . $lang[$i] . '">
                                <h4 class="panel-title">
                                    ' . $lang[$i] . '
                                </h4>
                             </a>
                        </div>
                        <div id="' . $lang[$i] . '" class="panel-collapse collapse ' . $collapseIn . '">
                            <div class="panel-body">';

                //Title
                echo '<div class="form-group">
                            <label class="col-sm-2 col-md-3  control-label">' . _uc($_e['Service Title']) . '</label>
                            <div class="col-sm-10  col-md-9">
                                <input type="text" name="heading[' . $lang[$i] . ']" class="form-control" placeholder="' . _uc($_e['Service Title']) . '">
                            </div>
                      </div>';

                //Short Desc
                if ($this->functions->developer_setting('blog_shrtDesc') == '1') {
                    echo '<div class="form-group">
                        <label class="col-sm-2 col-md-3  control-label">' . _uc($_e['Short Description']) . '</label>
                        <div class="col-sm-10  col-md-9">
                            <textarea name="shortDesc[' . $lang[$i] . ']" id="shortDesc_' . $lang[$i] . '" class="form-control" placeholder="' . _uc($_e['Enter Short Description']) . '"></textarea>
                            <script>
                               $(function() {
                                 CKEDITOR.replace("shortDesc_' . $lang[$i] . '");
                               });
                            </script>
                        </div>
                   </div>';
                } else {
                    echo '<input type="hidden" name="shortDesc" value="" class="form-control">';
                }

                //Desc
                echo '<div class="form-group">
                        <label class="col-sm-2 col-md-3  control-label">' . _uc($_e['Detail']) . '</label>
                        <div class="col-sm-10  col-md-9">
                            <textarea name="dsc[' . $lang[$i] . ']" id="dsc_' . $lang[$i] . '" placeholder="' . _uc($_e['Enter Full Detail']) . '"></textarea>
                            <script>
                               $(function() {
                                 CKEDITOR.replace("dsc_' . $lang[$i] . '");
                               });
                            </script>
                        </div>
                   </div>';

                echo '</div> <!-- panel-body-->
                        </div> <!-- #'.$lang[$i].' -->

                    </div> <!-- .panel-default -->';
            }

            echo '</div> <!-- .panel-group -->';
        }

        //Publish
        echo '<div class="form-group">
                    <label  class="col-sm-2 col-md-3  control-label">' . _uc($_e['Publish']) . '</label>
                    <div class="col-sm-10  col-md-9">
                        <div class="make-switch" data-off="danger" data-on="success" data-on-label="' . _uc($_e['Publish']) . '" data-off-label="' . _uc($_e['Draft']) . '">
                            <input type="checkbox" name="publish" value="1">
                        </div>
                    </div>
               </div>';

        // Slug
        echo '<div class="form-group">
                    <label class="col-sm-2 col-md-3  control-label">' . _uc($_e['Slug']) . '</label>
                    <div class="col-sm-10  col-md-9">
                        <input type="text" value="" name="slug" class="form-control" placeholder="' . _uc($_e['Slug']) . '">
                    </div>
                </div>';

        echo '<div class="form-group">
                <label  class="col-sm-2 col-md-3  control-label">' . _uc($_e['Service Top Image']) . '</label>
                <div class="col-sm-10  col-md-9">
                    <input type="file" name="icon" class="btn-file btn btn-primary">
                </div>
            </div>';

        // echo '<div class="form-group">
        //         <label  class="col-sm-2 col-md-3  control-label">' . _uc($_e['Service Middle Image']) . '</label>
        //         <div class="col-sm-10  col-md-9">
        //             <input type="file" name="image2" class="btn-file btn btn-primary">
        //         </div>
        //     </div>';

        echo '<div class="form-group">
                <label  class="col-sm-2 col-md-3  control-label">' . _uc($_e['Service Banner Video']) . '</label>
                <div class="col-sm-10  col-md-9">
                    <input type="file" name="video" class="btn-file btn btn-primary">
                </div>
            </div>';

        if ($this->functions->developer_setting('blog_image') == '1') {
            echo '<div class="form-group">
                    <label  class="col-sm-2 col-md-3  control-label">' . _uc($_e['Service Main Image']) . '</label>
                    <div class="col-sm-10  col-md-9">
                        <input type="file" name="image" class="btn-file btn btn-primary">
                    </div>
               </div>';
        } else {
            echo '<input type="hidden" name="news_image" value="" class="form-control">';
        }

        echo '<button type="submit" name="submit" value="SAVE" class="btn btn-lg btn-primary">' . _u($_e['SAVE']) . '</button>';

        echo "</div>
             </form>";
    }

    public function serviceEdit()
    {
        global $_e;
        $token = $this->functions->setFormToken('editService', false);
        $id    = $_GET['serviceId'];
        $sql   = "SELECT * FROM `service` WHERE `id` = ?";
        $data  = $this->dbF->getRow($sql, array($id));

        echo '<form method="post" action="-service?page=service" class="form-horizontal" role="form" enctype="multipart/form-data">' .
            $token .
            '<input type="hidden" name="editId" value="' . $id . '"/>
            <div class="form-horizontal">

            <div class="panel-group" id="accordion">';

        $lang = $this->functions->IbmsLanguages();
        if ($lang != false) {
            $lang_size = sizeof($lang);
            for ($i = 0; $i < $lang_size; $i++) {
                $collapseIn = ($i == 0) ? ' in ' : '';

                $heading   = unserialize($data['heading']);
                $short_dsc = unserialize($data['shortDesc']);
                $dsc       = unserialize($data['dsc']);

                echo '<div class="panel panel-default">
                        <div class="panel-heading">
                             <a data-toggle="collapse" data-parent="#accordion" href="#' . $lang[$i] . '">
                                <h4 class="panel-title">
                                    ' . $lang[$i] . '
                                </h4>
                             </a>
                        </div>
                        <div id="' . $lang[$i] . '" class="panel-collapse collapse ' . $collapseIn . '">
                            <div class="panel-body">';

                //Title
                echo '<div class="form-group">
                            <label class="col-sm-2 col-md-3  control-label">' . _uc($_e['Service Title']) . '</label>
                            <div class="col-sm-10  col-md-9">
                                <input type="text" value="' . htmlspecialchars($heading[$lang[$i]]) . '" name="heading[' . $lang[$i] . ']" class="form-control" placeholder="' . _uc($_e['Service Title']) . '">
                            </div>
                        </div>';

                //Short Desc
                if ($this->functions->developer_setting('blog_shrtDesc') == '1') {
                    echo '<div class="form-group">
                        <label class="col-sm-2 col-md-3  control-label">' . _uc($_e['Short Description']) . '</label>
                        <div class="col-sm-10  col-md-9">
                            <textarea name="shortDesc[' . $lang[$i] . ']" id="shortDesc_' . $lang[$i] . '" class="form-control" placeholder="' . _uc($_e['Enter Short Description']) . '">' . htmlspecialchars($short_dsc[$lang[$i]]) . '</textarea>
                            <script>
                               $(function() {
                                 CKEDITOR.replace("shortDesc_' . $lang[$i] . '");
                               });
                            </script>
                        </div>
                   </div>';
                } else {
                    echo '<input type="hidden" name="shortDesc[' . $lang[$i] . ']" value="" class="form-control">';
                }

                //Desc
                echo '<div class="form-group">
                        <label class="col-sm-2 col-md-3  control-label">' . _uc($_e['Detail']) . '</label>
                        <div class="col-sm-10  col-md-9">
                            <textarea name="dsc[' . $lang[$i] . ']" id="dsc_' . $lang[$i] . '" placeholder="' . _uc($_e['Enter Full Detail']) . '">' . htmlspecialchars($dsc[$lang[$i]]) . '</textarea>
                            <script>
                               $(function() {
                                 CKEDITOR.replace("dsc_' . $lang[$i] . '");
                               });
                            </script>
                        </div>
                   </div>';

                echo '</div> <!-- panel-body-->
                        </div> <!-- #'.$lang[$i].' -->

                    </div> <!-- .panel-default -->';
            }

            echo '</div> <!-- .panel-group -->';
        }

        //Publish
        $checked = ($data['publish'] == '1') ? 'checked' : '';
        echo '<div class="form-group">
                    <label  class="col-sm-2 col-md-3  control-label">' . _uc($_e['Publish']) . '</label>
                    <div class="col-sm-10  col-md-9">
                        <div class="make-switch" data-off="danger" data-on="success" data-on-label="' . _uc($_e['Publish']) . '" data-off-label="' . _uc($_e['Draft']) . '">
                            <input type="checkbox" name="publish" value="1" ' . $checked . '>
                        </div>
                    </div>
               </div>';

        // Slug
        echo '<div class="form-group">
                    <label class="col-sm-2 col-md-3  control-label">' . _uc($_e['Slug']) . '</label>
                    <div class="col-sm-10  col-md-9">
                        <input type="text" value="' . htmlspecialchars($data['slug']) . '" name="slug" class="form-control" placeholder="' . _uc($_e['Slug']) . '">
                    </div>
                </div>';

        // Top Image (icon)
        if ($data['icon'] != '') {
            $icon = $data['icon'];
            echo "<input type='hidden' name='oldIcon' value='" . htmlspecialchars($icon) . "' />";
            echo '<div class="form-group">
                <label  class="col-sm-2 col-md-3  control-label">' . _uc($_e['Old Service Top Image']) . '</label>
                <div class="col-sm-10  col-md-9">
                    <img src="../images/' . htmlspecialchars($icon) . '" style="max-height:250px;" >
                </div>
           </div>';
        }

        echo '<div class="form-group">
                <label  class="col-sm-2 col-md-3  control-label">' . _uc($_e['Service Top Image']) . '</label>
                <div class="col-sm-10  col-md-9">
                    <input type="file" name="icon" class="btn-file btn btn-primary">
                </div>
           </div>';

        // // Middle Image (image2)
        // if ($data['image2'] != '') {
        //     $image2 = $data['image2'];
        //     echo "<input type='hidden' name='oldImage2' value='" . htmlspecialchars($image2) . "' />";
        //     echo '<div class="form-group">
        //         <label  class="col-sm-2 col-md-3  control-label">' . _uc($_e['Old Service Middle Image']) . '</label>
        //         <div class="col-sm-10  col-md-9">
        //             <img src="../images/' . htmlspecialchars($image2) . '" style="max-height:250px;" >
        //         </div>
        //   </div>';
        // }

        // echo '<div class="form-group">
        //         <label  class="col-sm-2 col-md-3  control-label">' . _uc($_e['Service Middle Image']) . '</label>
        //         <div class="col-sm-10  col-md-9">
        //             <input type="file" name="image2" class="btn-file btn btn-primary">
        //         </div>
        //   </div>';

        // Banner Video
        if ($data['video'] != '') {
            $video = $data['video'];
            echo "<input type='hidden' name='oldVideo' value='" . htmlspecialchars($video) . "' />";
            echo '<div class="form-group">
                <label  class="col-sm-2 col-md-3  control-label">' . _uc($_e['Old Service Banner Video']) . '</label>
                <div class="col-sm-10  col-md-9">
                    <video src="../images/' . htmlspecialchars($video) . '" style="max-height:250px;" controls></video>
                </div>
           </div>';
        }

        echo '<div class="form-group">
                <label  class="col-sm-2 col-md-3  control-label">' . _uc($_e['Service Banner Video']) . '</label>
                <div class="col-sm-10  col-md-9">
                    <input type="file" name="video" class="btn-file btn btn-primary">
                </div>
           </div>';

        // Main Image
        if ($this->functions->developer_setting('blog_image') == '1') {
            if ($data['image'] != '') {
                $img = $data['image'];
                echo "<input type='hidden' name='oldImg' value='" . htmlspecialchars($img) . "' />";
                echo '<div class="form-group">
                    <label  class="col-sm-2 col-md-3  control-label">' . _uc($_e['Old Service Image']) . '</label>
                    <div class="col-sm-10  col-md-9">
                        <img src="../images/' . htmlspecialchars($img) . '" style="max-height:250px;" >
                    </div>
               </div>';
            }

            echo '<div class="form-group">
                    <label  class="col-sm-2 col-md-3  control-label">' . _uc($_e['Service Image']) . '</label>
                    <div class="col-sm-10  col-md-9">
                        <input type="file" name="image" class="btn-file btn btn-primary">
                    </div>
               </div>';
        } else {
            echo '<input type="hidden" name="image" value="" class="form-control">';
        }

        echo '<button type="submit" name="submit" value="SAVE" class="btn btn-lg btn-primary">' . _u($_e['SAVE']) . '</button>';

        echo "</div>
             </form>";
    }
}
