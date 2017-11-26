<!DOCTYPE html>
<html>
<head>
	<title>Save JSON</title>
</head>
<body>
	<button type="button" id="save">Save JSON</button>
</body>
<script src="<?=base_url('assets/vendors/jquery/dist/jquery.min.js')?>"></script>
<script>
	$(document).ready(function() {
	    $('#save').on('click', function() {
			alert('click');
			var obj = <?=$data_json?>; 
	        var	myJSON = JSON.stringify(obj);
        	$.ajax({
        		url: "<?=base_url("pintu_air/save_json")?>",
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
</html>