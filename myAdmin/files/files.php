<?php
ob_start();

require_once "classes/files.class.php";
global $dbF;

$files = new files();

$files->filesEditSubmit();
$files->newFilesAdd();
?>
<h2 class="sub_heading"><?php echo _uc('Manage Reports'); ?></h2>

<!-- Nav tabs -->
<ul class="nav nav-tabs tabs_arrow" role="tablist">
	<li class="active"><a href="#home" role="tab" data-toggle="tab"><?php echo _uc('Active Reports'); ?></a></li>

	<li><a href="#draft" role="tab" data-toggle="tab"><?php echo _uc($_e['Draft']); ?></a></li>
	<li><a href="#sort" role="tab" data-toggle="tab"><?php echo _uc($_e['Sort Reports']); ?></a></li>
	<li><a href="#newPage" role="tab" data-toggle="tab"><?php echo _uc('Add New Reports'); ?></a></li>
</ul>


<!-- Tab panes -->
<div class="tab-content">
	<div class="tab-pane fade in active container-fluid" id="home">
		<h2 class="tab_heading"><?php echo _uc('Active Reports'); ?></h2>
		<?php $files->filesView(); ?>
	</div>

	<div class="tab-pane fade in container-fluid" id="pending">
		<h2 class="tab_heading"><?php echo _uc($_e['Pending']); ?></h2>
		<?php $files->filesPending(); ?>
	</div>

	<div class="tab-pane fade in container-fluid" id="draft">
		<h2 class="tab_heading"><?php echo _uc($_e['Draft']); ?></h2>
		<?php $files->filesDraft(); ?>
	</div>
	<div class="tab-pane fade in container-fluid" id="sort">
		<h2 class="tab_heading"><?php echo _uc($_e['Sort Reports']); ?></h2>
		<?php $files->filesSort(); ?>
	</div>
	<div class="tab-pane fade in container-fluid" id="newPage">
		<h2 class="tab_heading"><?php echo _uc('Add New Reports'); ?></h2>
		<?php $files->filesNew(); ?>
	</div>
</div>

<script>
	$(function() {
		tableHoverClasses();
		dateJqueryUi();
	});

	function deleteFiles(ths) {
		btn = $(ths);
		if (secure_delete()) {
			btn.addClass('disabled');
			btn.children('.trash').hide();
			btn.children('.waiting').show();

			id = btn.attr('data-id');
			$.ajax({
				type: 'POST',
				url: 'files/files_ajax.php?page=deleteFiles',
				data: {
					id: id
				}
			}).done(function(data) {
				ift = true;
				if (data == '1') {
					ift = false;
					btn.closest('tr').hide(1000, function() {
						$(this).remove()
					});
				} else if (data == '0') {
					jAlertifyAlert('<?php echo ($_e['Delete Fail Please Try Again.']); ?>');
				} else {
					btn.append(data);
				}
				if (ift) {
					btn.removeClass('disabled');
					btn.children('.trash').show();
					btn.children('.waiting').hide();
				}

			});
		}
	}


	$(document).ready(function() {

		$(".sortDiv .activeSort").sortable({
			handle: '.albumSortTop',
			containment: "parent",
			update: function() {
				serial = $(this).sortable('serialize');
				$.ajax({
					url: 'files/files_ajax.php?page=filesSort',
					type: "post",
					data: serial,
					error: function() {
						jAlertifyAlert("<?php echo ('There is an error, Please Refresh Page and Try Again'); ?>");
					}
				});
			}
		});
	});
</script>

<?php return ob_get_clean(); ?>