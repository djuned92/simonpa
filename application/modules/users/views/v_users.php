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
                <table class="table table-bordered table-striped" id="tbl-users">
                    <thead>
                        <tr>
                            <th width="3%">#</th>
                            <th width="15%">Username</th>
                            <th width="25%">Fullname</th>
                            <th width="37%">Address</th>
                            <th width="10%">Gender</th>
                            <th width="10%">Phone</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; foreach($users as $key => $value): ?>
                        <tr>
                            <td><?=$i++?></td>
                            <td><?=$value['username']?></td>
                            <td><?=$value['fullname']?></td>
                            <td><?=$value['address']?></td>
                            <td><?=($value['gender'] == 1) ? 'Male':'Female';?></td>
                            <td><?=$value['phone']?></td>
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
</script>