<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">

<div class="page-title">
	<div class="title_left">
		<h3>Plain Page</h3>
		<ol class="breadcrumb">
			<li><a href="#"> Master</a></li>
			<li class="active">User</li>
		</ol>
	</div>
</div>

<div class="clearfix"></div>
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel">
				<div class="x_title">
					<h2>Plain Page</h2>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">
				    <table>
				    	<thead>
				    		<tr>
				    			<th>Username</th>
				    			<th>Password</th>
				    			<th>Action</th>
				    		</tr>
				    	</thead>
				    	<tbody></tbody>
				    </table>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		var oTable = $('#tbl-user').DataTable({
			'processing': true,
			'serverSide': true,
			'ajax': {
				url: "<?=base_url('user/dt_user')?>",
				method: 'post',
			},
			'columns': [
				{'data':'username', 'sName':'username'},
				{'data':'password', 'sName':'password'},
				{'data':'action'},	
			]

		});
	});
</script>