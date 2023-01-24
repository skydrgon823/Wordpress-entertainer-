<?php
/**
 * City Autocomplete field for Buddypress
 * @package WordPress
 * @subpackage Sweetdate
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since Sweetdate 2.3
 */

if ( sq_option( 'bp_city_username' ) && kleo_bp_get_auto_country() != '0' ) {
	//in search form
	add_action( 'kleo_bp_search_add_data', 'kleo_load_city_autocomplete', 9 );
	//in registration page
	add_action( 'bp_before_register_page', 'kleo_load_city_autocomplete', 9 );
	//in profile edit page
	add_action( 'bp_before_profile_edit_content', 'kleo_load_city_autocomplete', 9 );
}

function kleo_load_city_autocomplete() {
	wp_enqueue_script( 'jquery-ui-autocomplete' );
	add_action( 'wp_footer', 'bp_autocomplete_script' );
}

//get the selected country for autocomplete
function kleo_bp_get_auto_country() {
	$autocomplete_country = '0';
	if ( sq_option( 'bp_country_field', '0' ) != '0' ) {
		$autocomplete_country = '(kleo_countries[ucwords(jQuery( "#field_' . sq_option( 'bp_country_field', 0 ) . '" ).val())] || "").toString()';
	} elseif ( sq_option( 'bp_country_code', '' ) != '' ) {
		$autocomplete_country = "'" . sq_option( 'bp_country_code', '0' ) . "'";
	}

	return $autocomplete_country;
}

function bp_autocomplete_script() {
	$autocomplete_city    = sq_option( 'bp_city_field', 0 );
	$autocomplete_country = kleo_bp_get_auto_country();

	$extra_params  = apply_filters( 'kleo_bp_auto_params', '' );
	$feature_class = apply_filters( 'kleo_bp_auto_feature', 'P' );
	if ( is_ssl() ) {
		$url = 'https://secure.geonames.org/searchJSON';
	} else {
		$url = 'http://api.geonames.org/searchJSON';
	}
	?>
	<script>
		jQuery(function () {
			jQuery("#field_<?php echo esc_attr( $autocomplete_city ); ?>, #field_<?php echo esc_attr( $autocomplete_city ); ?>_contains").autocomplete({
				source: function (request, response) {
					jQuery.ajax({
						url: "<?php echo esc_url( $url ); ?>",
						dataType: "jsonp",
						data: {
							username: "<?php echo sq_option( 'bp_city_username' );?>",
							featureClass: "<?php echo strip_tags( $feature_class );?>",
							style: "short",
							maxRows: 12,
							name_startsWith: request.term,
							country: <?php echo strip_tags( $autocomplete_country ); echo esc_attr( $extra_params ); ?>
						},

						success: function (data) {
							var elem = {};
							/* remove duplicates */
							jQuery.each(data.geonames, function (i, v) {
								elem[v.name] = v.name;
							});
							/* prepare structured data */
							var elem = jQuery.map(elem, function (item) {
								return {
									label: item,
									value: item
								}
							});

							response(elem);
						}
					});
				},
				messages: {
					noResults: '',
					results: function () {
					}
				},
				minLength: <?php echo apply_filters( 'kleo_bp_auto_minlength', 2 );?>
			});
			jQuery('.ui-autocomplete').addClass('f-dropdown');
		});

		var kleo_countries = [];
		kleo_countries['Andorra'] = ['AD'], kleo_countries['Afghanistan'] = ['AF'], kleo_countries['Åland'] = ['AX'], kleo_countries['Albania'] = ['AL'], kleo_countries['Algeria'] = ['DZ'], kleo_countries['American Samoa'] = ['AS'], kleo_countries['Angola'] = ['AO'], kleo_countries['Anguilla'] = ['AI'], kleo_countries['Antarctica'] = ['AQ'], kleo_countries['Antigua and Barbuda'] = ['AG'], kleo_countries['Argentina'] = ['AR'], kleo_countries['Armenia'] = ['AM'], kleo_countries['Aruba'] = ['AW'], kleo_countries['Australia'] = ['AU'], kleo_countries['Austria'] = ['AT'], kleo_countries['Azerbaijan'] = ['AZ'], kleo_countries['Bahamas'] = ['BS'], kleo_countries['Bahrain'] = ['BH'], kleo_countries['Bangladesh'] = ['BD'], kleo_countries['Barbados'] = ['BB'], kleo_countries['Belarus'] = ['BY'], kleo_countries['Belgium'] = ['BE'], kleo_countries['Belize'] = ['BZ'], kleo_countries['Benin'] = ['BJ'], kleo_countries['Bermuda'] = ['BM'], kleo_countries['Bhutan'] = ['BT'], kleo_countries['Bolivia'] = ['BO'], kleo_countries['Bonaire'] = ['BQ'], kleo_countries['Bosnia and Herzegovina'] = ['BA'], kleo_countries['Botswana'] = ['BW'], kleo_countries['Bouvet Island'] = ['BV'], kleo_countries['Brazil'] = ['BR'], kleo_countries['British Indian Ocean Territory'] = ['IO'], kleo_countries['British Virgin Islands'] = ['VG'], kleo_countries['Brunei'] = ['BN'], kleo_countries['Bulgaria'] = ['BG'], kleo_countries['Burkina Faso'] = ['BF'], kleo_countries['Burundi'] = ['BI'], kleo_countries['Cambodia'] = ['KH'], kleo_countries['Cameroon'] = ['CM'], kleo_countries['Canada'] = ['CA'], kleo_countries['Cape Verde'] = ['CV'], kleo_countries['Cayman Islands'] = ['KY'], kleo_countries['Central African Republic'] = ['CF'], kleo_countries['Chad'] = ['TD'], kleo_countries['Chile'] = ['CL'], kleo_countries['China'] = ['CN'], kleo_countries['Christmas Island'] = ['CX'], kleo_countries['Cocos [Keeling] Islands'] = ['CC'], kleo_countries['Colombia'] = ['CO'], kleo_countries['Comoros'] = ['KM'], kleo_countries['Cook Islands'] = ['CK'], kleo_countries['Costa Rica'] = ['CR'], kleo_countries['Croatia'] = ['HR'], kleo_countries['Cuba'] = ['CU'], kleo_countries['Curacao'] = ['CW'], kleo_countries['Cyprus'] = ['CY'], kleo_countries['Czech Republic'] = ['CZ'], kleo_countries['Democratic Republic of the Congo'] = ['CD'], kleo_countries['Denmark'] = ['DK'], kleo_countries['Djibouti'] = ['DJ'], kleo_countries['Dominica'] = ['DM'], kleo_countries['Dominican Republic'] = ['DO'], kleo_countries['East Timor'] = ['TL'], kleo_countries['Ecuador'] = ['EC'], kleo_countries['Egypt'] = ['EG'], kleo_countries['El Salvador'] = ['SV'], kleo_countries['Equatorial Guinea'] = ['GQ'], kleo_countries['Eritrea'] = ['ER'], kleo_countries['Estonia'] = ['EE'], kleo_countries['Ethiopia'] = ['ET'], kleo_countries['Falkland Islands'] = ['FK'], kleo_countries['Faroe Islands'] = ['FO'], kleo_countries['Fiji'] = ['FJ'], kleo_countries['Finland'] = ['FI'], kleo_countries['France'] = ['FR'], kleo_countries['French Guiana'] = ['GF'], kleo_countries['French Polynesia'] = ['PF'], kleo_countries['French Southern Territories'] = ['TF'], kleo_countries['Gabon'] = ['GA'], kleo_countries['Gambia'] = ['GM'], kleo_countries['Georgia'] = ['GE'], kleo_countries['Germany'] = ['DE'], kleo_countries['Ghana'] = ['GH'], kleo_countries['Gibraltar'] = ['GI'], kleo_countries['Greece'] = ['GR'], kleo_countries['Greenland'] = ['GL'], kleo_countries['Grenada'] = ['GD'], kleo_countries['Guadeloupe'] = ['GP'], kleo_countries['Guam'] = ['GU'], kleo_countries['Guatemala'] = ['GT'], kleo_countries['Guernsey'] = ['GG'], kleo_countries['Guinea'] = ['GN'], kleo_countries['Guinea-Bissau'] = ['GW'], kleo_countries['Guyana'] = ['GY'], kleo_countries['Haiti'] = ['HT'], kleo_countries['Heard Island and McDonald Islands'] = ['HM'], kleo_countries['Honduras'] = ['HN'], kleo_countries['Hong Kong'] = ['HK'], kleo_countries['Hungary'] = ['HU'], kleo_countries['Iceland'] = ['IS'], kleo_countries['India'] = ['IN'], kleo_countries['Indonesia'] = ['ID'], kleo_countries['Iran'] = ['IR'], kleo_countries['Iraq'] = ['IQ'], kleo_countries['Ireland'] = ['IE'], kleo_countries['Isle of Man'] = ['IM'], kleo_countries['Israel'] = ['IL'], kleo_countries['Italy'] = ['IT'], kleo_countries['Ivory Coast'] = ['CI'], kleo_countries['Jamaica'] = ['JM'], kleo_countries['Japan'] = ['JP'], kleo_countries['Jersey'] = ['JE'], kleo_countries['Jordan'] = ['JO'], kleo_countries['Kazakhstan'] = ['KZ'], kleo_countries['Kenya'] = ['KE'], kleo_countries['Kiribati'] = ['KI'], kleo_countries['Kosovo'] = ['XK'], kleo_countries['Kuwait'] = ['KW'], kleo_countries['Kyrgyzstan'] = ['KG'], kleo_countries['Laos'] = ['LA'], kleo_countries['Latvia'] = ['LV'], kleo_countries['Lebanon'] = ['LB'], kleo_countries['Lesotho'] = ['LS'], kleo_countries['Liberia'] = ['LR'], kleo_countries['Libya'] = ['LY'], kleo_countries['Liechtenstein'] = ['LI'], kleo_countries['Lithuania'] = ['LT'],kleo_countries['Luxembourg'] = ['LU'],kleo_countries['Macao'] = ['MO'],kleo_countries['Macedonia'] = ['MK'],kleo_countries['Madagascar'] = ['MG'],kleo_countries['Malawi'] = ['MW'],kleo_countries['Malaysia'] = ['MY'],kleo_countries['Maldives'] = ['MV'],kleo_countries['Mali'] = ['ML'],kleo_countries['Malta'] = ['MT'],kleo_countries['Marshall Islands'] = ['MH'],kleo_countries['Martinique'] = ['MQ'],kleo_countries['Mauritania'] = ['MR'],kleo_countries['Mauritius'] = ['MU'],kleo_countries['Mayotte'] = ['YT'],kleo_countries['Mexico'] = ['MX'],kleo_countries['Micronesia'] = ['FM'],kleo_countries['Moldova'] = ['MD'],kleo_countries['Monaco'] = ['MC'],kleo_countries['Mongolia'] = ['MN'],kleo_countries['Montenegro'] = ['ME'],kleo_countries['Montserrat'] = ['MS'],kleo_countries['Morocco'] = ['MA'],kleo_countries['Mozambique'] = ['MZ'],kleo_countries['Myanmar [Burma]'] = ['MM'],kleo_countries['Namibia'] = ['NA'],kleo_countries['Nauru'] = ['NR'],kleo_countries['Nepal'] = ['NP'],kleo_countries['Netherlands'] = ['NL'],kleo_countries['New Caledonia'] = ['NC'],kleo_countries['New Zealand'] = ['NZ'],kleo_countries['Nicaragua'] = ['NI'],kleo_countries['Niger'] = ['NE'],kleo_countries['Nigeria'] = ['NG'],kleo_countries['Niue'] = ['NU'],kleo_countries['Norfolk Island'] = ['NF'],kleo_countries['North Korea'] = ['KP'],kleo_countries['Northern Mariana Islands'] = ['MP'],kleo_countries['Norway'] = ['NO'],kleo_countries['Oman'] = ['OM'],kleo_countries['Pakistan'] = ['PK'],kleo_countries['Palau'] = ['PW'],kleo_countries['Palestine'] = ['PS'],kleo_countries['Panama'] = ['PA'],kleo_countries['Papua New Guinea'] = ['PG'],kleo_countries['Paraguay'] = ['PY'],kleo_countries['Peru'] = ['PE'],kleo_countries['Philippines'] = ['PH'],kleo_countries['Pitcairn Islands'] = ['PN'],kleo_countries['Poland'] = ['PL'],kleo_countries['Portugal'] = ['PT'],kleo_countries['Puerto Rico'] = ['PR'],kleo_countries['Qatar'] = ['QA'],kleo_countries['Republic of the Congo'] = ['CG'],kleo_countries['Réunion'] = ['RE'],kleo_countries['Romania'] = ['RO'],kleo_countries['Russia'] = ['RU'],kleo_countries['Rwanda'] = ['RW'],kleo_countries['Saint Barthélemy'] = ['BL'],kleo_countries['Saint Helena'] = ['SH'],kleo_countries['Saint Kitts and Nevis'] = ['KN'],kleo_countries['Saint Lucia'] = ['LC'],kleo_countries['Saint Martin'] = ['MF'],kleo_countries['Saint Pierre and Miquelon'] = ['PM'],kleo_countries['Saint Vincent and the Grenadines'] = ['VC'],kleo_countries['Samoa'] = ['WS'],kleo_countries['San Marino'] = ['SM'],kleo_countries['São Tomé and Príncipe'] = ['ST'],kleo_countries['Saudi Arabia'] = ['SA'],kleo_countries['Senegal'] = ['SN'],kleo_countries['Serbia'] = ['RS'],kleo_countries['Seychelles'] = ['SC'],kleo_countries['Sierra Leone'] = ['SL'],kleo_countries['Singapore'] = ['SG'],kleo_countries['Sint Maarten'] = ['SX'],kleo_countries['Slovakia'] = ['SK'],kleo_countries['Slovenia'] = ['SI'],kleo_countries['Solomon Islands'] = ['SB'],kleo_countries['Somalia'] = ['SO'],kleo_countries['South Africa'] = ['ZA'],kleo_countries['South Georgia and the South Sandwich Islands'] = ['GS'],kleo_countries['South Korea'] = ['KR'],kleo_countries['South Sudan'] = ['SS'],kleo_countries['Spain'] = ['ES'],kleo_countries['Sri Lanka'] = ['LK'],kleo_countries['Sudan'] = ['SD'],kleo_countries['Suriname'] = ['SR'],kleo_countries['Svalbard and Jan Mayen'] = ['SJ'],kleo_countries['Swaziland'] = ['SZ'],kleo_countries['Sweden'] = ['SE'],kleo_countries['Switzerland'] = ['CH'],kleo_countries['Syria'] = ['SY'],kleo_countries['Taiwan'] = ['TW'],kleo_countries['Tajikistan'] = ['TJ'],kleo_countries['Tanzania'] = ['TZ'],kleo_countries['Thailand'] = ['TH'],kleo_countries['Togo'] = ['TG'],kleo_countries['Tokelau'] = ['TK'],kleo_countries['Tonga'] = ['TO'],kleo_countries['Trinidad and Tobago'] = ['TT'],kleo_countries['Tunisia'] = ['TN'],kleo_countries['Turkey'] = ['TR'],kleo_countries['Turkmenistan'] = ['TM'],kleo_countries['Turks and Caicos Islands'] = ['TC'],kleo_countries['Tuvalu'] = ['TV'],kleo_countries['U.S. Minor Outlying Islands'] = ['UM'],kleo_countries['U.S. Virgin Islands'] = ['VI'],kleo_countries['Uganda'] = ['UG'],kleo_countries['Ukraine'] = ['UA'],kleo_countries['United Arab Emirates'] = ['AE'],kleo_countries['United Kingdom'] = ['GB'],kleo_countries['United States'] = ['US'],kleo_countries['Uruguay'] = ['UY'],kleo_countries['Uzbekistan'] = ['UZ'],kleo_countries['Vanuatu'] = ['VU'],kleo_countries['Vatican City'] = ['VA'],kleo_countries['Venezuela'] = ['VE'],kleo_countries['Vietnam'] = ['VN'],kleo_countries['Wallis and Futuna'] = ['WF'],kleo_countries['Western Sahara'] = ['EH'],kleo_countries['Yemen'] = ['YE'],kleo_countries['Zambia'] = ['ZM'],kleo_countries['Zimbabwe'] = ['ZW']
	</script>
<?php } // bp_autocomplete_script()  ?>
