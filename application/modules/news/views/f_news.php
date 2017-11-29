<div class="page-title">
    <div class="title_left">
        <h3><?=($this->uri->segment(2) == 'add') ? 'Add ' : 'Edit '?>News</h3>
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
                <h2><?=($this->uri->segment(2) == 'add') ? 'Add ' : 'Edit '?>News</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <i class="fa fa-wrench"></i>
                        </a>
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
                <form class="form-horizontal form-label-left" id="form_news" method="post" enctype="multipart/form-data">
                    
                    <?php if($this->uri->segment(2) == 'update'): ?>
                    <input type="hidden" name="id" value="<?=$news['id']?>">
                    <?php endif ?>

                    <div class="form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-12">Title <span class="required">*</span></label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <input type="text" name="title" class="form-control" placeholder="Title ..." value="<?=isset($news['title'])?$news['title']:set_value('title');?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-12">Content <span class="required">*</span>
                        </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <textarea class="form-control" name="content" rows="3" placeholder='Content ...'><?=isset($news['content'])?$news['content']:set_value('content');?></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-12">Image <?=($this->uri->segment(2) == 'add') ? '<span class="required">*</span>':''?>
                        </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <input type="file" name="userfile" class="form-control" id="image">
                            <br/>
                            <img class="img img-rounded" id="preview-image" src="<?=base_url('assets/images/news/')?><?=isset($news['encrypt_image'])?$news['encrypt_image']:'no-image.png';?>" style="width: 120px;height: 120px;">    
                        </div>
                    </div>
                    
                    <div class="ln_solid"></div>
                    <div class="form-group">
                        <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-2">
                            <a href="<?=base_url('news')?>">
                                <button type="button" class="btn btn-primary">Back</button>
                            </a>
                            <button type="submit" class="btn btn-success" id="save">Save</button>
                        </div>
                    </div>

                </form>      
            </div>
        </div>
    </div>
</div>

<!-- validator -->
<script src="http://localhost/simonpa/assets/vendors/jquery-validation/jquery.validate.min.js"></script>   
<script type="text/javascript">
    function readURL(input) {

      if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
          $('#preview-image').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
      }
    }

    $("#image").change(function() {
      readURL(this);
    });

    $(document).ready(function() {
        // set validator
        $.validator.setDefaults({
            errorClass: 'help-block',
            highlight: function(element) {
                $(element)
                    .closest('.form-group')
                    .addClass('has-error');
            },
            unhighlight: function(element) {
                $(element)
                    .closest('.form-group')
                    .removeClass('has-error')
                    .addClass('has-success');
            }
        });

        $('#form_news').validate({
            rules: {
                title: {
                    required: true
                },
                content: {
                    required: true
                },
                
                <?php if($this->uri->segment(2) == 'add'): ?>
                    image: {
                        required: true
                    }
                <?php endif ?>
            },
            submitHandler: function(form) {
                var form = $('#form_news')[0],
                    data = new FormData(form);
                <?php if($this->uri->segment(2) == 'add') : ?>
                    var this_url = "<?=base_url('news/add')?>";
                <?php else : ?>
                    var this_url = "<?=base_url('news/update')?>";
                <?php endif ?> 
                $.ajax({
                    type: 'post',
                    enctype: 'multipart/form-data',
                    url: this_url,
                    dataType: "json",
                    data: data,
                    async: false,
                    processData: false,
                    contentType: false,
                    cache: false,
                    timeout: 600000,
                    beforeSend: function () {},
                    success: function(r) {
                        if(r.error == false) {
                            swal({
                              title: "<?=($this->uri->segment(2) == 'add') ? 'Add': 'Update';?>",
                              text: r.message,
                              type: "success",
                            });
                            // setTimeout(function() {
                            //     window.location.href = "<?=base_url('news')?>";  
                            // }, 2000);
                        }
                    }
                });
            }
        });
    })
</script>