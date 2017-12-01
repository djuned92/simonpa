<!-- datatables -->
<link href="<?=base_url('assets/vendors/datatables/css/dataTables.bootstrap.css')?>" rel="stylesheet">

<div class="page-title">
    <div class="title_left">
        <h3>Users</h3>
    </div>
    <div class="title_right">
        <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search for...">
                <span class="input-group-btn">
                <button class="btn btn-default" type="button">Go!</button>
                </span>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Users</h2>
                <div class="navbar-right">
                    <a href="<?=base_url('users/add')?>">
                        <button type="button" class="btn btn-sm btn-primary">
                            <i class="fa fa-plus"></i> Add
                        </button>
                    </a>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <table class="table table-bordered table-striped" id="tbl-users">
                    <thead>
                        <tr>
                            <th width="3%">#</th>
                            <th width="15%">Username</th>
                            <th width="20%">Fullname</th>
                            <th width="34%">Address</th>
                            <th width="10%">Gender</th>
                            <th width="13%">Phone</th>
                            <th width="5%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; foreach($users as $key => $value): ?>
                        <tr>
                            <td><?=$i++?></td>
                            <td><?=$value['username']?></td>
                            <td><?=$value['fullname']?></td>
                            <td><?=$value['address']?></td>
                            <td align="center"><?=($value['gender'] == 1) ? '<i class="fa fa-male"></i>':'<i class="fa fa-female"></i>';?></td>
                            <td>+62 <?=$value['phone']?></td>
                            <td>
                                <ul style="list-style: none;padding-left: 0px;padding-right: 0px; text-align: center;">
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                            <i class="fa fa-bars" style="font-size: large;"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-right" style="right: 0; left: auto;">
                                            <li>
                                                <a href="<?=base_url('users/update/'.$value['id'])?>">
                                                    <i class="fa fa-pencil"></i> Edit
                                                </a>
                                            </li>
                                            <li class="divider"></li>
                                            <li>
                                                <a href="#" class="btn-delete" data-id="<?=$value['id']?>">
                                                    <i class="fa fa-trash"></i> Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- datatables -->
<script src="<?=base_url('assets/vendors/datatables/js/jquery.dataTables.js')?>"></script>
<script src="<?=base_url('assets/vendors/datatables/js/dataTables.bootstrap.js')?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#tbl-users').DataTable();
    });

    $('#tbl-users').delegate('a.btn-delete', 'click', function(e){
            e.preventDefault();
            var id = $(this).data('id');
            swal({
                title: "Confirm Delete Data",
                text: "Are you sure delete this data?",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: 'btn-danger',
                confirmButtonText: 'Delete',
                cancelButtonText: "Cancel",
                closeOnConfirm: false,
                closeOnCancel: false
            },
            function(isConfirm){
                if (isConfirm){
                    $.ajax({
                        type: "post",
                        dataType: "json",
                        url: "<?=base_url('users/delete')?>",
                        data: {id: id},
                        beforeSend: function() {},
                        success: function(r) {
                            if(r.error == false) {
                                swal(r.message, "", r.type);
                                setTimeout(function() {
                                    window.location.href = "<?=base_url('users')?>";  
                                }, 2000);
                            }
                        },
                        error: function(e) {}
                    });
                } else {
                    swal("Failure", "Delete Cancel", "error");
                }
            });
        });
</script>