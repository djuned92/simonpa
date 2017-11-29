<!-- bootstrap switch -->
<link href="<?=base_url('assets/vendors/bootstrap-switch/bootstrap-switch.css')?>" rel="stylesheet">
<!-- datatables -->
<link href="<?=base_url('assets/vendors/datatables/css/dataTables.bootstrap.css')?>" rel="stylesheet">
        
<div class="page-title">
    <div class="title_left">
        <h3>News</h3>
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
                <h2>News</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="#">Settings 1</a>
                            </li>
                            <li><a href="#">Settings 2</a>
                            </li>
                        </ul>
                    </li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <table class="table table-bordered table-striped" id="tbl-news">
                    <thead>
                        <tr>
                            <th width="3%">#</th>
                            <th width="15%">Image</th>
                            <th width="25%">Title</th>
                            <th width="42%">Content</th>
                            <th width="10%">Is Published</th>
                            <th width="5%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; foreach($news as $key => $value): ?>
                        <tr>
                            <td><?=$i++?></td>
                            <td>
                                <img class="img img-rounded" src="<?=base_url('assets/images/news/')?><?=$value['encrypt_image']?>"
                                style="width: 120px; height: 120px;">
                            </td>
                            <td><?=$value['title']?></td>
                            <td><?=$value['content']?></td>
                            <td><input name="is_published" type="checkbox" <?= ($value['is_published'] == 1) ? 'checked':''; ?> data-size="small" data-id="<?=$value['id']?>"></td>
                            <td>
                                <ul style="list-style: none;padding-left: 0px;padding-right: 0px; text-align: center;">
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                            <i class="fa fa-bars" style="font-size: large;"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-right" style="right: 0; left: auto;">
                                            <li>
                                                <a href="<?=base_url('news/update/'.$value['id'])?>">
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

<!-- bootstrap switch -->
<script src="<?=base_url('assets/vendors/bootstrap-switch/bootstrap-switch.js')?>"></script>
<!-- datatables -->
<script src="<?=base_url('assets/vendors/datatables/js/jquery.dataTables.js')?>"></script>
<script src="<?=base_url('assets/vendors/datatables/js/dataTables.bootstrap.js')?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#tbl-news').DataTable();

        $("[type='checkbox']").bootstrapSwitch(); // init bootstrap switch
        $('input[name="is_published"]').on('switchChange.bootstrapSwitch', function(event, state) {
            // console.log(state); // true | false
            var id = $(this).data('id');
            if(state == true) {
                var is_published = 1;
            } else {
                var is_published = 0;
            }
            $.post("<?=base_url('api/api_news/update_is_published')?>", { id:id, is_published:is_published });
        });
        
        $('#tbl-news').delegate('a.btn-delete', 'click', function(e){
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
                            url: "<?=base_url('news/delete')?>",
                            data: {id: id},
                            beforeSend: function() {},
                            success: function(r) {
                                if(r.error == false) {
                                    swal(r.message, "", r.type);
                                    setTimeout(function() {
                                        window.location.href = "<?=base_url('news')?>";  
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
    })

</script>