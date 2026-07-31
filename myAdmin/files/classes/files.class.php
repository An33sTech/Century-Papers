<?php
require_once __DIR__ . "/../../global.php"; //connection setting db

################### NEW MODULE NOTE ##########################
//If you want to make new module like files, just copy paste files. and change page_type to your type
//and only change label  and hide or show any fields.

class files extends object_class
{
	public $productF;
	public $fileName;
	private $page_type = "files";
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
		$_w = [];
		//Index
		$_w['Reports Management'] = '';
		//files.php

		$_w['Manage Reports'] = '';
		$_w['Active Reports'] = '';
		$_w['Pending'] = '';
		$_w['Draft'] = '';
		$_w['Add New Reports'] = '';
		$_w['Delete Fail Please Try Again.'] = '';
		$_w['Sort Reports'] = '';

		//FilesNew.php
		$_w['Add Reports'] = '';

		//This Class
		$_w['SNO'] = '';
		$_w['TITLE'] = '';
		$_w['PUBLISH DATE'] = '';
		$_w['UPDATE'] = '';
		$_w['ACTION'] = '';
		$_w['Reports Save Successfully'] = '';
		$_w['Added'] = '';
		$_w['Reports Save Failed'] = '';

		$_w['SAVE'] = '';
		$_w['Files Image (278x278 px)'] = '';
		$_w['Leave Blank to publish now'] = '';
		$_w['Publish'] = '';

		$_w['Allow Comment'] = '';
		$_w['Files Date'] = '';
		$_w['Date'] = '';
		$_w['Files Setting'] = '';
		$_w['Enter Full Detail'] = '';
		$_w['Detail'] = '';
		$_w['Enter Short Description'] = '';
		$_w['Short Description'] = '';
		$_w['Reports Title'] = '';
		$_w['Old File'] = '';
		$_w['Reports Detail'] = '';
		$_w['Old Files Image'] = '';
		$_w['Files'] = '';
		$_w['Subcategory'] = '';
		$_w['Files or Training file (278x278 px)'] = '';
		$_w['Sub_Category'] = '';

		$_e = $this->dbF->hardWordsMulti($_w, $adminPanelLanguage, 'Admin Files Management');
	}

	public function filesSort()
	{

		$sql = "SELECT type FROM `files` WHERE publish = '1' GROUP BY `type`  ASC";
		$data = $this->dbF->getRows($sql);

		$defaultLang = $this->functions->AdminDefaultLanguage();
		foreach ($data as $val) {
			echo '<div class="table-responsive sortDiv">
                <div class="container-fluid activeSort">';
			$id = $val['id'];
			$type = $val['type'];
			if ($type == 0) {
				echo '<h2  class="tab_heading">Financial Report</h2>';
			} elseif ($type == 1) {
				echo '<h2  class="tab_heading">Economic Review</h2>';
			} elseif ($type == 2) {
				echo '<h2  class="tab_heading">Economic Review Chinese</h2>';
			}
			$sql2 = "SELECT * FROM `files` WHERE publish = '1' AND type = '$type'  ORDER BY sort ASC";
			$data2 = $this->dbF->getRows($sql2);

			foreach ($data2 as $val) {
				$id = $val['id'];
				@$heading = translateFromSerialize($val['heading']);

				@$title = $val['sub_category'];

				echo '  <div class="singleAlbum " id="album_' . $id . '">
                         <div class="col-sm-12 albumSortTop"> ::: </div>
                         
                        <div class=" col-sm-12">
                            <div class="col-sm-12 btn-default" style="">' . $heading . '</div>
                            <div class="albumImage"><img src="../webImages/pdfImage.png"  class="img-responsive"/></div>
                            <div class="col-sm-12 btn-default" style="">' . $title . '</div>
                        </div>
                    </div>';
			}
			echo '</div>
        </div>';
		}
	}


	public function filesView()
	{
		$today = date('Y-m-d');
		$page_type = $this->page_type;
		$sql = "SELECT id, heading,sub_category FROM files WHERE publish = '1' ORDER BY id DESC";
		$data = $this->dbF->getRows($sql);
		$this->print_files_table($data);
	}

	public function filesPending()
	{
		$today = date('Y-m-d');
		$page_type = $this->page_type;
		$sql = "SELECT id, heading,sub_category FROM files WHERE publish = '1' ORDER BY id DESC";
		$data = $this->dbF->getRows($sql);
		$this->print_files_table($data);
	}


	public function filesDraft()
	{
		$page_type = $this->page_type;
		$sql = "SELECT id, heading,sub_category FROM files WHERE publish = '0' ORDER BY id DESC";
		$data = $this->dbF->getRows($sql);
		$this->print_files_table($data);
	}

	private function print_files_table($data)
	{
		$data = empty($data) ? [] : $data;
		global $_e;
		echo '<div class="table-responsive">
		<table class="table table-hover dTable tableIBMS">
		<thead>
		<th>' . _u($_e['SNO']) . '</th>
		<th>' . _u($_e['TITLE']) . '</th>
		<th>' . _u($_e['Sub_Category']) . '</th>
		<th>' . _u($_e['ACTION']) . '</th>
		</thead>
		<tbody>';

		$i = 0;
		$defaultLang = $this->functions->AdminDefaultLanguage();
		foreach ($data as $val) {
			$i++;
			$id = $val['id'];
			$heading = unserialize($val['heading']);
			$subcat = ($val['sub_category']);
			$heading = $heading[$defaultLang];
			echo "<tr>
		<td>$i</td>
		<td>$heading</td>
		<td>$subcat</td>
		<td>
		<div class='btn-group btn-group-sm'>
		<a data-id='$id' href='-files?page=edit&pageId=$id' class='btn'>
		<i class='glyphicon glyphicon-edit'></i>
		</a>
		<a data-id='$id' onclick='deleteFiles(this);' class='btn'>
		<i class='glyphicon glyphicon-trash trash'></i>
		<i class='fa fa-refresh waiting fa-spin' style='display: none'></i>
		</a>
		</div>
		</td>
		</tr>";
		}

		echo '         </tbody>
		</table>
		</div> <!-- .table-responsive End -->';
	}

	public function newFilesAdd()
	{
		global $_e;
		if (isset($_POST['submit'])) {
			if (!$this->functions->getFormToken('newFiles')) {
				return false;
			}

			$heading = empty($_POST['heading']) ? "" : serialize($_POST['heading']);
			$subcategory = empty($_POST['subcategory']) ? "" : ($_POST['subcategory']);


			$type = empty($_POST['type']) ? "0" : ($_POST['type']);



			$publish = empty($_POST['publish']) ? "0" : $_POST['publish'];
			$file = empty($_POST['file']) ? "" : $_POST['file'];
			$file = $this->functions->removeWebUrlFromLink($file);
			try {
				$this->db->beginTransaction();

				$sql = "INSERT INTO `files` (`heading`, `sub_category`, `type`, `files`, `publish`) VALUES (?, ?, ?, ?, ?)";
				$array = [$heading, $subcategory, $type, $file, $publish];
				$this->dbF->setRow($sql, $array, false);

				$this->db->commit();
				if ($this->dbF->rowCount > 0) {
					$this->functions->notificationError(_uc($_e['Files']), ($_e['Reports Save Successfully']), 'btn-success');
					$this->functions->setlog(_uc($_e['Added']), _uc($_e['Files']), $this->dbF->rowLastId, ($_e['Reports Save Successfully']));
				} else {
					$this->functions->notificationError(_uc($_e['Files']), ($_e['Reports Save Failed']), 'btn-danger');
				}
			} catch (Exception $e) {
				$this->db->rollBack();
				$this->dbF->error_submit($e);
				$this->functions->notificationError(_uc($_e['Files']), ($_e['Reports Save Failed']), 'btn-danger');
			}
		} // If end
	}

	public function filesEditSubmit()
	{
		global $_e;
		if (isset($_POST['submit']) && isset($_POST['editId'])) {
			if (!$this->functions->getFormToken('editFiles')) {
				return false;
			}

			$heading = empty($_POST['heading']) ? "" : serialize($_POST['heading']);
			$subcategory = empty($_POST['subcategory']) ? "" : ($_POST['subcategory']);
			$publish = empty($_POST['publish']) ? "0" : $_POST['publish'];
			$file = empty($_POST['file']) ? "" : $_POST['file'];
			$file = $this->functions->removeWebUrlFromLink($file);
			$type = empty($_POST['type']) ? "" : ($_POST['type']);
			try {
				$this->db->beginTransaction();
				$lastId = $_POST['editId'];

				$sql = "UPDATE `files` SET `heading` = ?, `sub_category` = ?, `type` = ?, `files` = ?, `publish` = ? WHERE id = ?";

				$array = [$heading, $subcategory, $type, $file, $publish, $lastId];

				$this->dbF->setRow($sql, $array, false);

				$this->db->commit();

				if ($this->dbF->rowCount > 0) {
					$this->functions->notificationError(_uc($_e['Files']), _uc($_e['Reports Save Successfully']), 'btn-success');
					$this->functions->setlog(_uc($_e['UPDATE']), _uc($_e['Files']), $this->dbF->rowLastId, _uc($_e['Reports Save Successfully']));
				} else {
					$this->functions->notificationError(_uc($_e['Files']), _uc($_e['Reports Save Failed']), 'btn-danger');
				}
			} catch (Exception $e) {
				$this->db->rollBack();
				$this->dbF->error_submit($e);
				$this->functions->notificationError(_uc($_e['Files']), _uc($_e['Reports Save Failed']), 'btn-danger');
			}
		}
	}


	public function filesNew()
	{
		global $_e;
		$token = $this->functions->setFormToken('newFiles', false);
		//No need to remove any thing,, go in developer setting table and set 0
		echo '<form method="post" class="form-horizontal" role="form" enctype="multipart/form-data">' . $token . '
		<div class="form-horizontal">

		<!-- Nav tabs -->
		<ul class="nav nav-tabs tabs_arrow" role="tablist">
		<li class="active"><a href="#homeP" role="tab" data-toggle="tab">' . _uc($_e['Detail']) . '</a></li>
		</ul>
		<!-- Tab panes -->
		<div class="tab-content">
		<div class="tab-pane fade in active container-fluid" id="homeP">
		<h2  class="tab_heading">' . _uc('Reports Detail') . '</h2>';



		echo '<div class="form-group">
			<label class="col-sm-2 col-md-3  control-label">

			Select Report Type

			</label>
			<div class="col-sm-10  col-md-9">
			<select name="type" required placeholder="select" class="form-control">


			<option  value="0">Financial Report</option>
			<option value="1">Economic Review</option>
			<option value="2">Economic Review Chinese</option>
			';


		echo '</select>
		</div>
		</div>';


		$lang = $this->functions->IbmsLanguages();
		if ($lang != false) {
			$lang_nonArray = implode(',', $lang);
		}
		echo '  <input type="hidden" name="lang" value="' . $lang_nonArray . '" />';

		echo '<div class="panel-group" id="accordion">';
		for ($i = 0; $i < sizeof($lang); $i++) {
			if ($i == 0) {
				$collapseIn = ' in ';
			} else {
				$collapseIn = '';
			}
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
			echo '  <div class="form-group">
			<label class="col-sm-2 col-md-3  control-label">' . _uc($_e['TITLE']) . '</label>
			<div class="col-sm-10  col-md-9">
			<input type="text" name="heading[' . $lang[$i] . ']"  maxlength="150"  class="form-control" placeholder="' . _uc($_e['Reports Title']) . '">
			</div>
			</div>';


			echo '      </div> <!-- panel-body end -->
			</div> <!-- collapse end-->
			</div><!-- panel end-->';
		}
        echo '   </div> <!-- .accordian end -->';

		//file
		echo '  <div class="form-group">
			<label class="col-sm-2 col-md-3  control-label">File Upload</label>
			<div class="col-sm-10  col-md-9">
			<div class="input-group">
			<input type="url"  name="file" class="layer1 form-control" placeholder="">
			<div class="input-group-addon pointer " onclick="' . "openKCFinderFile($('.layer1'))" . '"><i class="glyphicon glyphicon-file"></i></div>
			</div>
			</div>
			</div>';

		echo '<div class="form-group">
			<label class="col-sm-2 col-md-3  control-label">Category Under</label>
			<div class="col-sm-10  col-md-9">
			<select name="subcategory" class="form-control">

			';


		$applied_for_fields_array = explode(',', $this->functions->ibms_setting('financial_positions'));
		foreach ($applied_for_fields_array as $field): ?>
			<option value="<?php echo $field; ?>">
				<?php echo $field; ?>
			</option>
		<?php
		endforeach;


		echo '</select>
		</div>
		</div>';


		//Publish
		echo '  <br><div class="form-group">
			<label  class="col-sm-2 col-md-3  control-label">' . _uc($_e['Publish']) . '</label>
			<div class="col-sm-10  col-md-9">
			<div class="make-switch" data-off="danger" data-on="success" data-on-label="' . _uc($_e['Publish']) . '" data-off-label="' . _uc($_e['Draft']) . '">
			<input type="checkbox" name="publish" value="1">
			</div>
			</div>
			</div>';

		echo '  <button type="submit" name="submit" value="SAVE" class="btn btn-lg btn-primary">' . _u($_e['SAVE']) . '</button>';
		
		echo '</div> <!-- homeP Tab End -->';
		echo "</div> <!-- tab-content end -->
		</div> <!-- container end -->
		</form>";
	}

	public function filesEdit()
	{
		global $_e;
		$token = $this->functions->setFormToken('editFiles', false);
		$id = $_GET['pageId'];
		$sql = "SELECT * FROM files where id = '$id' ";
		$data = $this->dbF->getRow($sql);
		if ($this->dbF->rowCount == 0) {
			echo "files Not Found For Update";
			return false;
		}

		//No need to remove any thing,, go in developer setting table and set 0
		echo '<form method="post" action="-files?page=files" class="form-horizontal" role="form" enctype="multipart/form-data">' .
			$token .
			'<input type="hidden" name="editId" value="' . $id . '"/>
				<div class="form-horizontal">
				<!-- Nav tabs -->
				<ul class="nav nav-tabs tabs_arrow" role="tablist">
				<li class="active"><a href="#homeP" role="tab" data-toggle="tab">' . _uc($_e['Detail']) . '</a></li>
				</ul>
				<!-- Tab panes -->
				<div class="tab-content">
				<div class="tab-pane fade in active container-fluid" id="homeP">
				<h2  class="tab_heading">' . _uc($_e['Reports Detail']) . '</h2>';

		$type = ($data['type']);
		echo '<div class="form-group">
		<label class="col-sm-2 col-md-3  control-label">

		Select Report Type

		</label>
		<div class="col-sm-10  col-md-9">
		<select name="type" class="form-control">


		<option ';
		if ($type == "0") {
			echo "selected";
		}
		echo ' value="0">Financial Report</option><option ';
		if ($type == "1") {
			echo "selected";
		}
		echo ' value="1">Economic Review</option><option ';
		if ($type == "2") {
			echo "selected";
		}
		echo ' value="2">Economic Review Chinese</option>';

		echo '</select>
			</div>
			</div>';

		$lang = $this->functions->IbmsLanguages();
		if ($lang != false) {
			$lang_nonArray = implode(',', $lang);
		}
		echo '<input type="hidden" name="lang" value="' . $lang_nonArray . '" />';

		echo '<div class="panel-group" id="accordion">';

		$heading = unserialize($data['heading']);
		$sub_category = ($data['sub_category']);

		$files = $data['files'];

		for ($i = 0; $i < sizeof($lang); $i++) {
			if ($i == 0) {
				$collapseIn = ' in ';
			} else {
				$collapseIn = '';
			}
			echo '  <div class="panel panel-default">
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
			echo '  <div class="form-group">
			<label class="col-sm-2 col-md-3  control-label">' . _uc($_e['TITLE']) . '</label>
			<div class="col-sm-10  col-md-9">
			<input type="text" name="heading[' . $lang[$i] . ']" value="' . $heading[$lang[$i]] . '" maxlength="150"  class="form-control" placeholder="' . _uc($_e['Reports Title']) . '">
			</div>
			</div>';

			echo '        </div> <!-- panel-body end -->
				</div> <!-- collapse end-->
				</div><!-- panel end-->';
		}




		//file
		$file = empty($files) ? "" : $this->functions->addWebUrlInLink(@$files);
		echo '  <div class="form-group">
			<label class="col-sm-2 col-md-3  control-label">File Upload</label>
			<div class="col-sm-10  col-md-9">
			<div class="input-group">
			<input type="url"  name="file" value="' . $file . '" class="layer1 form-control" placeholder="">
			<div class="input-group-addon pointer " onclick="' . "openKCFinderFile($('.layer1'))" . '"><i class="glyphicon glyphicon-file"></i></div>
			</div>
			</div>
			</div>';




		echo '<div class="form-group">
			<label class="col-sm-2 col-md-3  control-label">Category Under</label>
			<div class="col-sm-10  col-md-9">
			<select name="subcategory" required="required" class="categoryType form-control">
			<option>' . $sub_category . '</option>
			';


		$applied_for_fields_array = explode(',', $this->functions->ibms_setting('financial_positions'));
		foreach ($applied_for_fields_array as $field): ?>
			<option value="<?php echo $field; ?>">
				<?php echo $field; ?>
			</option>
<?php
		endforeach;


		echo '</select>
		</div>
		</div>';



		//Publish
		$checked = "";
		if ($data['publish'] == '1') {
			$checked = 'checked';
		}
		echo '<br><div class="form-group">
		<label  class="col-sm-2 col-md-3  control-label">' . _uc($_e['Publish']) . '</label>
		<div class="col-sm-10  col-md-9">
		<div class="make-switch" data-off="danger" data-on="success" data-on-label="' . _uc($_e['Publish']) . '" data-off-label="' . _uc($_e['Draft']) . '">
		<input type="checkbox" name="publish" value="1" ' . $checked . '>
		</div>
		</div>
		</div>';

		echo '<button type="submit" name="submit" value="SAVE" class="btn btn-lg btn-primary">' . _u($_e['SAVE']) . '</button>';
		echo '</div> <!-- .accordian end -->';
		echo '</div> <!-- homeP Tab End -->';
		echo "</div> <!-- tab-content end -->
		</div> <!-- container end -->
		</form>";
	}
}
?>