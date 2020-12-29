/* globals jQuery:true, ajaxurl:true, cp_ddp:true  */
function cp_ddp_freemius_opt_in( element ) {
	var nonce  = jQuery( '#cp-ddp-freemius-opt-nonce' ).val(); // Nonce.
	var choice = jQuery( element ).data( 'opt' ); // Choice.

	jQuery.ajax( {
		type: 'POST',
		url: ajaxurl,
		async: true,
		data: {
			action: 'cp_ddp_freemius_opt_in',
			opt_nonce: nonce,
			choice: choice
		},
		success: function( ) {
			location.reload();
		},
		error: function( xhr, textStatus, error ) {
			console.log( xhr.statusText );
			console.log( textStatus );
			console.log( error );
		}
	} );
}


jQuery(document).ready(function(){

	let senddata = [];
	senddata['stepid'] = 0;
	ddp_get_duplicates(1,senddata);

	


// DELETE DUPES
jQuery(document).on('click', '#deleteduplicateposts_deleteall', function(e) {

	e.preventDefault();

	if ( !confirm( cp_ddp.text_areyousure ) ) {
		return;
	}

	jQuery.ajax({
		type: 'POST',
		url: ajaxurl,
		data: {
			'_ajax_nonce': cp_ddp.deletedupes_nonce,
			'action': 'ddp_delete_duplicates'
		},
		dataType: "json",
		success: function( ) {
			ddp_get_duplicates(1,senddata);
			ddp_refresh_log();
			location.reload(true);
		}
	}).fail(function (response) {
		jQuery('#log .spinner').removeClass('is-active');
		if ( window.console && window.console.log ) {
			window.console.log( response.statusCode+' '+response.statusText );
			ddp_get_duplicates(1,senddata);
			ddp_refresh_log();
			location.reload(true);
		}
	});


});


	// REFRESH LIST
	jQuery(document).on('click', '#deleteduplicateposts_resetview', function(e) {
		e.preventDefault();
		jQuery('#ddp_container .dupelist .duplicatetable tbody').empty();
		ddp_get_duplicates(1,senddata);
		ddp_refresh_log();
	});



	function ddp_refresh_log() {
		// REFRESH LOG
		jQuery('#ddp_log').empty();
		jQuery.ajax({
			type: 'POST',
			url: ajaxurl,
			data: {
				'_ajax_nonce': cp_ddp.loglines_nonce,
				'action': 'ddp_get_loglines'
			},
			dataType: "json",
			success: function( response ) {

				let loglines = response.data.results;

				if (loglines) {

					jQuery.each( loglines, function( key, value ) {
						jQuery('#ddp_log').append('<li><code>'+value.datime+'</code> '+value.note+'</li>');
					});
				}
				jQuery('#log .spinner').removeClass('is-active');
			}
		}).fail(function (response) {
			jQuery('#log .spinner').removeClass('is-active');
			if ( window.console && window.console.log ) {
				window.console.log( response.statusCode+' '+response.statusText );
			}
		});
	}



// stepid = integer
// data = array of tests
function ddp_get_duplicates( stepid, data, self ) {

	jQuery( "#ddp_container #ddp_buttons input" ).each(function() {
		jQuery( this ).prop("disabled", true);
	});

	jQuery('#ddp_container #dashboard .spinner').addClass('is-active');

	//let testid = data[stepid];

	jQuery.ajax({
		type: 'POST',
		url: ajaxurl,
		data: {
			'_ajax_nonce': cp_ddp.nonce,
			'action': 'ddp_get_duplicates',
			'stepid': stepid
		},
		dataType: "json",
		success: function( response ) {

			let dupes = response.data.dupes;

			if (dupes) {

				jQuery('#ddp_container #dashboard .statusdiv .statusmessage').html(response.data.msg).show();			
				jQuery('#ddp_container #dashboard .statusdiv .dupelist .duplicatetable').show();

				jQuery.each( dupes, function( key, value ) {
					jQuery('#ddp_container #dashboard .statusdiv .dupelist .duplicatetable tbody').append('<tr><td><a href="'+value.permalink+'" target="_blank">'+value.title+'</a> (ID #'+value.ID+' type:'+value.type+' status:'+value.status+')</td><td><a href="'+value.orgpermalink+'" target="_blank">'+value.orgtitle+'</a> (ID #'+value.orgID+')'+value.why+'</td></tr>');

				});

				jQuery( "#ddp_container #ddp_buttons input" ).each(function() {
					jQuery( this ).prop("disabled", false);
				});

			}
			else {
				jQuery('#ddp_container #dashboard .statusdiv .statusmessage').html(response.data.msg).show();			
			}
			jQuery('#ddp_container #dashboard .spinner').removeClass('is-active');
			if( '-1' == response.data.nextstep ) {
				// Something went wrong.
			}
			else {
				if ( parseInt(response.data.nextstep) > 0 ) {
					ddp_get_duplicates( parseInt( response.data.nextstep ), data, self );
				}


			}
			ddp_refresh_log();
		}
	}).fail(function (response) {
		ddp_refresh_log();
		jQuery('#ddp_container #dashboard .spinner').removeClass('is-active');

		if ( window.console && window.console.log ) {
			window.console.log( response.statusCode+' '+response.statusText );
		}
	});



}



		// Show / hide input fields in admin based on selected compare method.
		jQuery(document).on('click', '.ddpcomparemethod li', function() {

			jQuery(".ddpcomparemethod input:radio").each(function () {

				if (this.checked) {
					jQuery(this).closest( 'li').find('.ddp-compare-details').show();
				}
				else {
					jQuery(this).closest( 'li').find('.ddp-compare-details').hide();
				}
			});
		});

		// Pretend click
		jQuery('.ddpcomparemethod li').trigger('click');
	});