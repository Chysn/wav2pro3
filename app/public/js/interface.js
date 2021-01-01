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
	$('.wavoption').removeAttr('disabled');
	$('#uploaded').show();
	$('#wavetable_name').prop('disabled', true);
	$('#frame_size').prop('disabled', true);
	$('#code_override').val('');
	refreshMyWavetables();
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
	$('.wavoption').attr('disabled');
	var m = $('#upload_file_name').val().match(/([A-Za-z0-9 _-]+)\.wav/i);
	if (m !== null) {
		$('#wavetable_name').val(m[1].substring(0,8));
	}
}

function badFileError() {$('#invalid_file').show();}

function wavetableTooBig() {$('#too_big').show();}

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
	$('.wavoption-share').attr('disabled', 1);
	
	$.get('services/share.php?desc=' + d + '&sig=' + s, function(d, s) {$('#thanks').show();refreshSharedWavetables();});
}

function refreshSharedWavetables()
{	
	$.get('services/library.php', function(d, s) {$('#shared').empty().append(d);});
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

