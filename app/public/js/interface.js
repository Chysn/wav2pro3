var sysex = [];

function updateCurrentWavetable(samples)
{
	var data = 'samples=';
	for (var i = 0; i < samples.length; i++)
	{
		data += (samples[i] + ',');
	}
	$.post('services/data.php?job=update_wave', data, function(d, s) {wavetableHasUpdated(d, s);});
}

function wavetableHasUpdated(data)
{
	sysex = data;
	$('.pro3option').removeAttr('disabled');
	$('#uploaded').show();
	$('#upload_button').attr('disabled', true)
	$('#wavetable_name').attr('disabled', true);
	$('#frame_size').attr('disabled', true);
	$('#collapseShared').removeClass('collapse');
	refreshMyWavetables();
	$('#loading').fadeOut('slow');
}

function overrideDownload(code)
{
	$('#code_override').val(code);
}

function fillFileName(el)
{
	var fn = $(el).val();
	if (fn != '') {
		$('#upload_button').removeAttr('disabled');
	}
	var elid = $(el).attr('id') + '_name';
	$('#' + elid).val(fn);
	$('#invalid_file').hide();
	$('#wavetable_name').prop('disabled', false);
	$('#frame_size').prop('disabled', false);
	$('.alert').hide();
	$('.pro3option').attr('disabled');
	var m = $('#upload_file_name').val().match(/([A-Za-z0-9 _-]+)\.wav/i);
	if (m !== null) {
		$('#wavetable_name').val(m[1].substring(0,8));
		$('.wavoption').show();
		if ($('#split').prop('checked')) {
			$('#high_file').show()
		} else {$('#high_file').hide()}		
	} else {
		$('.wavoption').hide();
		$('#high_file').hide();
	}
}

function fillHighFileName(el)
{
	var fn = $(el).val();
	var elid = $(el).attr('id') + '_name';
	$('#' + elid).val(fn);
}

function badFileError() 
{
	$('#invalid_file').show();
	$('#loading').fadeOut('slow');
}

function wavetableTooBig() 
{
	$('#too_big').show();
	$('#loading').fadeOut('slow');
}

function shareSuccess() {$('#thanks').show();}

function refreshMyWavetables()
{
	$('#invalid_file').hide();
	$.get('services/my.php', function(d, s) {$('#session').empty().append(d);});
}

function shareWavetable()
{
	// Get an encode field values
	var d = $('#share_desc').val();
	var s = $('#share_sig').val();
	d = encodeURI(d);
	s = encodeURI(s);
	
	// Reset for the next share
	$('#share_name').val('');
	$('#share_desc').val('');
	
	$('#share_wave').modal('hide');
	$('#pro3option-share').attr('disabled', 1);
	
	$.get('services/share.php?desc=' + d + '&sig=' + s, function(d, s) {$('#thanks').show();refreshSharedWavetables();});
}

function refreshSharedWavetables(code)
{	
	$.get('services/library.php', function(d, s) {
		$('#shared').empty().append(d);
		if (code) {
			if ($('#n' + code).html() !== undefined) {
				$('#wt' + code).show();
				$('#download_name').html('Download SysEx: ' + $('#n' + code).html());
			}
		}
	});
}

function showAllWavetables()
{
	$('#shared_query').val('');
	$('#shared_wavetable_table TR').show();
}

function searchWavetables()
{
	var q = new RegExp($('#shared_query').val(), 'i');
	$('#shared_wavetable_table TR').hide();
	$('.open_description').remove();
	$.each($('#shared_wavetable_table TR'), function() 
			{
				var n = $(this).find('.wavetable_name').html();
				if (n.match(q)) {$(this).show();}
			}
	);
}

