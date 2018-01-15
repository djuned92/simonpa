<div class="page-title">
    <div class="title_left">
        <h3>Push Notif</h3>
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
                <h2>Push Notif</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
            	<h6>Button Untuk Send Push Notification</h6>
				<button type="button" id="save" class="btn btn-success btn-large"><i class="fa fa-send"></i> Push Notif</button>
            </div>
        </div>
    </div>
</div>

<script>
	$(document).ready(function() {
	    $('#save').on('click', function() {
			alert('click');
			var obj = <?=$data_json?>; 
	        var	myJSON = JSON.stringify(obj);
        	$.ajax({
        		url: "<?=base_url("push_notif/save_json")?>",
                        type: 'post',
                        dataType: 'json',
                        data: {myJSON:myJSON},
                        beforeSend: function(r) {}, 
                        success: function(r) {
                        	alert(r.message);
                                console.log(r);
                        }
        	});
            });
	})
</script>