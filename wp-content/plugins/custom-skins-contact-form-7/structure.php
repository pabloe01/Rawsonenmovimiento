<input type="hidden" value="<?php echo CF7CS_PLUGIN_URI;?>" id='path' >
<div class="wrap">
	<?php screen_icon('themes');
	$border_style_arr = array('none' => 'none','dotted' => 'dotted','dashed' => 'dashed','solid' => 'solid','double' => 'double','groove' => 'groove','ridge' => 'ridge','inset' => 'inset','outset' => 'outset' );
	$border_width_arr = array('0px'=>'0px','1px' => '1px','2px' => '2px','3px' => '3px','4px' => '4px','5px' => '5px','6px' => '6px','7px' => '7px','8px' => '8px','9px' => '9px','10px' => '10px');
	$space_arr = array('0px'=>'0px','1px' => '1px','2px' => '2px','3px' => '3px','4px' => '4px','5px' => '5px','6px' => '6px','7px' => '7px','8px' => '8px','9px' => '9px','10px' => '10px');
	$border_radius_arr= array('0px'=>'0px', '1px' => '1px','2px' => '2px','3px' => '3px','4px' => '4px','5px' => '5px','6px' => '6px','7px' => '7px','8px' => '8px','9px' => '9px','10px' => '10px','11px' => '11px','12px' => '12px','13px' => '13px','14px' => '14px','15px' => '15px','16px' => '16px','17px' => '17px','18px' => '18px','19px' => '19px','20px' => '20px','21px' => '21px','22px' => '22px','23px' => '23px','24px' => '24px','25px'=>'25px' );
	$font_size_arr = array('9px' => '9px','10px' => '10px','11px' => '11px','12px' => '12px','13px' => '13px','14px' => '14px','15px' => '15px','16px' => '16px','17px' => '17px','18px' => '18px','19px' => '19px','20px' => '20px','21px' => '21px','22px' => '22px','23px' => '23px','24px' => '24px' );
	$padding_arr = array('inherit'=>'inherit','0px'=>'0px', '1px' => '1px','2px' => '2px','3px' => '3px','4px' => '4px','5px' => '5px','6px' => '6px','7px' => '7px','8px' => '8px','9px' => '9px','10px' => '10px','11px' => '11px','12px' => '12px','13px' => '13px','14px' => '14px','15px' => '15px','16px' => '16px','17px' => '17px','18px' => '18px','19px' => '19px','20px' => '20px','21px' => '21px','22px' => '22px','23px' => '23px','24px' => '24px','25px'=>'25px' );
	?>
	<h2 ><?php	echo esc_html( __( 'Custom Skins Contact Form 7', 'cf7cs' ) );?></h2>
	<div class="top_div">
		<label for="wpcf7_id">Select Form:</label>
		<?php $postarr = array('post_type' => 'wpcf7_contact_form','orderby'=>'title','order'=>'ASC','posts_per_page'=>20,);
			$forms_data = get_posts($postarr);
			?>
		<select id="wpcf7_id" name="wpcf7_id"  onchange="return load_form(this.value);">
			<option  selected="selected" value="">---Select Form---</option>
			<?php
			foreach ($forms_data as $single_form ) {
				echo '<option value="'.$single_form->ID.'"  >'.$single_form->post_title.'</option>';
			}
			?>
		</select>
		<div id="shrtcd_dv" class="shrtcd_dv"> </div>
	</div>
	<div id="mn_cntinr">
		<div class="form_main"><?php echo '<link media="all" type="text/css" href="'.get_stylesheet_uri().'" rel="stylesheet">';?>
			<h2>Form Preview</h2>
			<div class="clear"></div>
			<div id="out_form">
				<div id="header_section"></div>
				<div id="form_display" >
					<!--From Load Here -->
					Please Select Form
				</div>
				<div id="footer_section"></div>
			</div>
		</div>
		<div class="stngcntinr">		
			<h2>Settings</h2>
			<div class="settings_fileds">
				<table class="setting_tbl" >
					<tr>
						<th>Skins:</th>
						<td>
							<li><input type="radio" title="Custom Skin" class="cls_skins" name="skins" id="custom" checked="checked"/><label for="custom">Custom</label></li>
							<li><input type="radio" class="cls_skins" name="skins" id="skin1" onclick="loadskin1();" /><label for="skin1">Bud Green</label></li>
							<li><input type="radio" class="cls_skins" name="skins" id="skin2" onclick="loadskin2();" /><label for="skin2">Carolina Blue</label></li>
							<li><input type="radio" class="cls_skins" name="skins" id="skin3" onclick="loadskin3();" /><label for="skin3">Dark Copper</label></li>
							<li><input type="radio" class="cls_skins" name="skins" id="skin4" onclick="loadskin4();" /><label for="skin4">FB Blue</label></li>
							<li><input type="radio" class="cls_skins" name="skins" id="skin5" onclick="loadskin5();" /><label for="skin5">Black Been</label></li>
						</td>
					</tr>
				</table>
			</div>
			<form id="setting_form">
				<div class="custom-holder">
					<div class="widgets-holder-wrap">
						<div class="sidebar-name" >
							<div class="sidebar-name-arrow"><br></div><h3>Form Style</h3>
						</div>
						<div class="widget-holder">
							<table class="outer_tbl">
								<tr>
									<th>Backgroud Color</th>
									<td>
										<input type="text" id="frm_bg_clr" name="frm_bg_clr" class="colorpicker" >
									</td>
								</tr>
								<tr>
									<th>Font Color</th>
									<td>
										<input type="text" id="frm_fnt_clr" name="frm_fnt_clr" class="colorpicker" >
									</td>
								</tr>											
								<tr>
									<th>Font Size</th>
									<td>
										<select id="frm_fnt_siz" name="frm_fnt_siz">
											<?php foreach ($font_size_arr as $key => $value) {
												echo'<option value="'.$key.'" >'.$value.'</option>';
											}		
											?>
										</select>
									</td>
								</tr><tr>
									<td colspan="2">
										<div class="widgets-holder-wrap">
											<div class="sidebar-name" ><div class="sidebar-name-arrow"><br></div>
											<h3>Box Shadow</h3></div>
											<div class="widget-holder">
												<table class="subset_tbl">
													<tr>
														<th>Vertical Position</th>
														<td>
															<select id="frm_bs_vp" name="frm_bs_vp">
																<?php foreach ($space_arr as $key => $value) {
																	echo'<option value="'.$key.'" >'.$value.'</option>';
																}		
																?>
															</select>
														</td>
													</tr>
													<tr>
														<th>Horizontal Position</th>													
														<td>
															<select id="frm_bs_hp" name="frm_bs_hp">
																<?php foreach ($space_arr as $key => $value) {
																	echo'<option value="'.$key.'" >'.$value.'</option>';
																}		
																?>
															</select>
														</td>
													</tr>
													<tr>
														<th>Blur Radius</th>
														<td>
															<select id="frm_bs_br" name="frm_bs_br">
																<?php foreach ($space_arr as $key => $value) {
																	echo'<option value="'.$key.'" >'.$value.'</option>';
																}		
																?>
															</select>
														</td>
													</tr>
													<tr>
														<th>Spread Radius</th>
														<td>
															<select id="frm_bs_sr" name="frm_bs_sr">
																<?php foreach ($space_arr as $key => $value) {
																	echo'<option value="'.$key.'" >'.$value.'</option>';
																}		
																?>
															</select>
														</td>
													</tr>
													<tr>
														<th>Color</th>
														<td><input type="text" id="frm_bs_clr" name="frm_bs_clr" class="colorpicker" >
														</td>
													</tr>
												</table>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<div class="widgets-holder-wrap">
											<div class="sidebar-name" ><div class="sidebar-name-arrow"><br></div>
											<h3>Border</h3></div>
											<div class="widget-holder">
												<table class="subset_tbl">
													<tr>
														<th>Thickness</th>
														<td>
															<select id="frm_brdr_wth" name="frm_brdr_wth">
																<?php foreach ($border_width_arr as $key => $value) {
																	echo'<option value="'.$key.'" >'.$value.'</option>';
																}	
																?>
															</select>
														</td>
													</tr>
													<tr>
														<th>Style</th>
														<td>
															<select id="frm_brdr_stl" name="frm_brdr_stl">
																<?php foreach ($border_style_arr as $key => $value) {
																	echo'<option value="'.$key.'" >'.$value.'</option>';
																}		
																?>
															</select>
														</td>
													</tr>
													<tr>
														<th>Radius</th>
														<td>
															<select id="frm_brdr_rds" name="frm_brdr_rds">
																<?php foreach ($border_radius_arr as $key => $value) {
																	echo'<option value="'.$key.'" >'.$value.'</option>';
																}		
																?>
															</select>
														</td>
													</tr>
													<tr>
														<th>Color</th>
														<td>
															<input type="text" id="frm_brdr_clr" name="frm_brdr_clr" class="colorpicker"  >
														</td>
													</tr>
												</table>
											</div>
										</div>
									</td>
								</tr>
							</table>
						</div>
					</div>
					<div class="widgets-holder-wrap">
						<div class="sidebar-name" >
							<div class="sidebar-name-arrow"><br></div><h3>Header Section</h3>
						</div>
						<div class="widget-holder">
							<table class="outer_tbl">
								<tr>
									<th>Backgroud Color</th>
									<td>
										<input type="text" id="hd_bg_clr" name="hd_bg_clr" class="colorpicker" >
									</td>
								</tr>
								<tr>
									<th>Font Color</th>
									<td>
										<input type="text" id="hd_fnt_clr" name="hd_fnt_clr" class="colorpicker" >
									</td>
								</tr>											
								<tr>
									<th>Font Size</th>
									<td>
										<select id="hd_fnt_siz" name="hd_fnt_siz">
											<?php foreach ($font_size_arr as $key => $value) {
												echo'<option value="'.$key.'" >'.$value.'</option>';
											}		
											?>
										</select>
									</td>
								</tr>											
								<tr>
									<th>Header Contant</th>
									<td>
									<textarea name="hd_cnt" id="hd_cnt"></textarea>
									</td>
								</tr>
								<tr>
									<th>Padding</th>
									<td>
										<table cellpadding="0" cellspacing="2">
											<tr>
												<th>Top</th>
												<th>Right</th>
												<th>Bottom</th>
												<th>Left</th>
											</tr>
											<tr>
												<th>
													<select id="hd_pd_top" name="hd_pd_top">
														<?php foreach ($padding_arr as $key => $value) {
															echo'<option value="'.$key.'" >'.$value.'</option>';
														}		
														?>
													</select>
												</th>
												<th>
													<select id="hd_pd_rgt" name="hd_pd_rgt">
														<?php foreach ($padding_arr as $key => $value) {
															echo'<option value="'.$key.'" >'.$value.'</option>';
														}		
														?>
													</select>
												</th>
												<th>
													<select id="hd_pd_btm" name="hd_pd_btm">
														<?php foreach ($padding_arr as $key => $value) {
															echo'<option value="'.$key.'" >'.$value.'</option>';
														}		
														?>
													</select>
												</th>
												<th>
													<select id="hd_pd_lft" name="hd_pd_lft">
														<?php foreach ($padding_arr as $key => $value) {
															echo'<option value="'.$key.'" >'.$value.'</option>';
														}		
														?>
													</select>
												</th>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<th>Height</th>
									<td>
										<input type="text" id="hd_hght" name="hd_hght" >
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<div class="widgets-holder-wrap">
											<div class="sidebar-name" ><div class="sidebar-name-arrow"><br></div>
											<h3>Border Bottom</h3></div>
											<div class="widget-holder">
												<table class="subset_tbl">
													<tr>
														<th>Thickness</th>
														<td>
															<select id="hd_brdr_wth" name="hd_brdr_wth">
																<?php foreach ($border_width_arr as $key => $value) {
																	echo'<option value="'.$key.'" >'.$value.'</option>';
																}	
																?>
															</select>
														</td>
													</tr>
													<tr>
														<th>Style</th>
														<td>
															<select id="hd_brdr_stl" name="hd_brdr_stl">
																<?php foreach ($border_style_arr as $key => $value) {
																	echo'<option value="'.$key.'" >'.$value.'</option>';
																}		
																?>
															</select>
														</td>
													</tr>
													<tr>
														<th>Color</th>
														<td>
															<input type="text" id="hd_brdr_clr" name="hd_brdr_clr" class="colorpicker"  >
														</td>
													</tr>
												</table>
											</div>
										</div>
									</td>
								</tr>
							</table>
						</div>
					</div>
					<div class="widgets-holder-wrap">
						<div class="sidebar-name" >
							<div class="sidebar-name-arrow"><br></div><h3>Footer Section</h3>
						</div>
						<div class="widget-holder">
							<table class="outer_tbl">
								<tr>
									<th>Backgroud Color</th>
									<td>
										<input type="text" id="ft_bg_clr" name="ft_bg_clr" class="colorpicker" >
									</td>
								</tr>
								<tr>
									<th>Font Color</th>
									<td>
										<input type="text" id="ft_fnt_clr" name="ft_fnt_clr" class="colorpicker" >
									</td>
								</tr>											
								<tr>
									<th>Font Size</th>
									<td>
										<select id="ft_fnt_siz" name="ft_fnt_siz">
											<?php foreach ($font_size_arr as $key => $value) {
												echo'<option value="'.$key.'" >'.$value.'</option>';
											}		
											?>
										</select>
									</td>
								</tr>											
								<tr>
									<th>Footer Contant</th>
									<td>
									<textarea name="ft_cnt" id="ft_cnt"></textarea>
									</td>
								</tr>
								<tr>
									<th>Padding</th>
									<td>
										<table cellpadding="0" cellspacing="2">
											<tr>
												<th>Top</th>
												<th>Right</th>
												<th>Bottom</th>
												<th>Left</th>
											</tr>
											<tr>
												<th>
													<select id="ft_pd_top" name="ft_pd_top">
														<?php foreach ($padding_arr as $key => $value) {
															echo'<option value="'.$key.'" >'.$value.'</option>';
														}		
														?>
													</select>
												</th>
												<th>
													<select id="ft_pd_rgt" name="ft_pd_rgt">
														<?php foreach ($padding_arr as $key => $value) {
															echo'<option value="'.$key.'" >'.$value.'</option>';
														}		
														?>
													</select>
												</th>
												<th>
													<select id="ft_pd_btm" name="ft_pd_btm">
														<?php foreach ($padding_arr as $key => $value) {
															echo'<option value="'.$key.'" >'.$value.'</option>';
														}		
														?>
													</select>
												</th>
												<th>
													<select id="ft_pd_lft" name="ft_pd_lft">
														<?php foreach ($padding_arr as $key => $value) {
															echo'<option value="'.$key.'" >'.$value.'</option>';
														}		
														?>
													</select>
												</th>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<th>Height</th>
									<td>
										<input type="text" id="ft_hght" name="ft_hght" >
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<div class="widgets-holder-wrap">
											<div class="sidebar-name" ><div class="sidebar-name-arrow"><br></div>
											<h3>Border Top</h3></div>
											<div class="widget-holder">
												<table class="subset_tbl">
													<tr>
														<th>Thickness</th>
														<td>
															<select id="ft_brdr_wth" name="ft_brdr_wth">
																<?php foreach ($border_width_arr as $key => $value) {
																	echo'<option value="'.$key.'" >'.$value.'</option>';
																}	
																?>
															</select>
														</td>
													</tr>
													<tr>
														<th>Style</th>
														<td>
															<select id="ft_brdr_stl" name="ft_brdr_stl">
																<?php foreach ($border_style_arr as $key => $value) {
																	echo'<option value="'.$key.'" >'.$value.'</option>';
																}		
																?>
															</select>
														</td>
													</tr>
													<tr>
														<th>Color</th>
														<td>
															<input type="text" id="ft_brdr_clr" name="ft_brdr_clr" class="colorpicker"  >
														</td>
													</tr>
												</table>
											</div>
										</div>
									</td>
								</tr>
							</table>
						</div>
					</div>
					<div class="widgets-holder-wrap">
						<div class="sidebar-name" >
							<div class="sidebar-name-arrow"><br></div><h3>Button Style</h3>
						</div>
						<div class="widget-holder">
							<table class="outer_tbl">
								<tr>
									<th>Padding</th>
									<td>
										<table cellpadding="0" cellspacing="2">
											<tr>
												<th>Top</th>
												<th>Right</th>
												<th>Bottom</th>
												<th>Left</th>
											</tr>
											<tr>
												<th>
													<select id="btn_pd_top" name="btn_pd_top">
														<?php foreach ($padding_arr as $key => $value) {
															echo'<option value="'.$key.'" >'.$value.'</option>';
														}		
														?>
													</select>
												</th>
												<th>
													<select id="btn_pd_rgt" name="btn_pd_rgt">
														<?php foreach ($padding_arr as $key => $value) {
															echo'<option value="'.$key.'" >'.$value.'</option>';
														}		
														?>
													</select>
												</th>
												<th>
													<select id="btn_pd_btm" name="btn_pd_btm">
														<?php foreach ($padding_arr as $key => $value) {
															echo'<option value="'.$key.'" >'.$value.'</option>';
														}		
														?>
													</select>
												</th>
												<th>
													<select id="btn_pd_lft" name="btn_pd_lft">
														<?php foreach ($padding_arr as $key => $value) {
															echo'<option value="'.$key.'" >'.$value.'</option>';
														}		
														?>
													</select>
												</th>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<th>Button Height</th>
									<td>
										<input type="text" id="btn_hgt" name="btn_hgt" >
									</td>
								</tr>
								<tr>
									<th>Button Width</th>
									<td>
										<input type="text" id="btn_wth" name="btn_wth" >
									</td>
								</tr>
								<tr>
									<th>Font Color</th>
									<td>
										<input type="text" id="btn_fnt_clr" name="btn_fnt_clr" class="colorpicker" >
									</td>
								</tr>
								<tr>
									<th>Font Size</th>
									<td>
										<select id="btn_fnt_siz" name="btn_fnt_siz">
											<?php foreach ($font_size_arr as $key => $value) {
												echo'<option value="'.$key.'" >'.$value.'</option>';
											}		
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<div class="widgets-holder-wrap">
											<div class="sidebar-name" ><div class="sidebar-name-arrow"><br></div>
											<h3>Background Color</h3></div>
											<div class="widget-holder">
												<table class="subset_tbl">
													<tr>
													<th>Main Color</th>
														<td><input type="text" id="btn_bg_clr" name="btn_bg_clr" class="colorpicker" ></td>
													</tr>
													<tr>
														<th>
															Gradient Effect
														</th>
														<td>
															<table class="inner_tbl">
																<tr>
																	<th>Top Color</th>
																	<td><input type="text" id="btn_grdnt_top" name="btn_grdnt_top" class="colorpicker" ></td>
																</tr>
																<tr>
																	<th>Middle Color</th>													
																	<td><input type="text" id="btn_grdnt_mid" name="btn_grdnt_mid" class="colorpicker" ></td>
																</tr>
																<tr>
																	<th>Bottom Color</th>
																	<td><input type="text" id="btn_grdnt_btm" name="btn_grdnt_btm" class="colorpicker" ></td>
																</tr>
															</table>
														</td>
													</tr>
												</table>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<div class="widgets-holder-wrap">
											<div class="sidebar-name" ><div class="sidebar-name-arrow"><br></div>
											<h3>Box Shadow</h3></div>
											<div class="widget-holder">
												<table class="subset_tbl">
													<tr>
														<th>Vertical Position</th>
														<td>
															<select id="btn_bs_vp" name="btn_bs_vp">
																<?php foreach ($space_arr as $key => $value) {
																	echo'<option value="'.$key.'" >'.$value.'</option>';
																}		
																?>
															</select>
														</td>
													</tr>
													<tr>
														<th>Horizontal Position</th>													
														<td>
															<select id="btn_bs_hp" name="btn_bs_hp">
																<?php foreach ($space_arr as $key => $value) {
																	echo'<option value="'.$key.'" >'.$value.'</option>';
																}		
																?>
															</select>
														</td>
													</tr>
													<tr>
														<th>Blur Radius</th>
														<td>
															<select id="btn_bs_br" name="btn_bs_br">
																<?php foreach ($space_arr as $key => $value) {
																	echo'<option value="'.$key.'" >'.$value.'</option>';
																}		
																?>
															</select>
														</td>
													</tr>
													<tr>
														<th>Spread Radius</th>
														<td>
															<select id="btn_bs_sr" name="btn_bs_sr">
																<?php foreach ($space_arr as $key => $value) {
																	echo'<option value="'.$key.'" >'.$value.'</option>';
																}		
																?>
															</select>
														</td>
													</tr>
													<tr>
														<th>Inset</th>													
														<td><input type="checkbox" id="btn_bs_inset" name="btn_bs_inset" value="inset"><label for="btn_bs_inset">Yes</label></td>
													</tr>
													<tr>
														<th>Color</th>
														<td><input type="text" id="btn_bs_clr" name="btn_bs_clr" class="colorpicker" >
														</td>
													</tr>
												</table>
											</div>
										</div>
									</td>
								</tr>
								<tr>
								<td colspan="2">
										<div class="widgets-holder-wrap">
											<div class="sidebar-name" ><div class="sidebar-name-arrow"><br></div>
											<h3>Text Shadow</h3></div>
											<div class="widget-holder">
												<table class="subset_tbl">
													<tr>
														<th>Vertical Position</th>
														<td>
															<select id="btn_fnt_tvp" name="btn_fnt_tvp">
																<?php foreach ($space_arr as $key => $value) {
																	echo'<option value="'.$key.'" >'.$value.'</option>';
																}		
																?>
															</select>
														</td>
													</tr>
													<tr>
														<th>Horizontal Position</th>													
														<td>
															<select id="btn_fnt_thp" name="btn_fnt_thp">
																<?php foreach ($space_arr as $key => $value) {
																	echo'<option value="'.$key.'" >'.$value.'</option>';
																}		
																?>
															</select>
														</td>
													</tr>
													<tr>
														<th>Blur Radius</th>
														<td>
															<select id="btn_fnt_tbr" name="btn_fnt_tbr">
																<?php foreach ($space_arr as $key => $value) {
																	echo'<option value="'.$key.'" >'.$value.'</option>';
																}		
																?>
															</select>
														</td>
													</tr>
													<tr>
														<th>Color</th>
														<td><input type="text" id="btn_fnt_tclr" name="btn_fnt_tclr" class="colorpicker" >
														</td>
													</tr>
												</table>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<div class="widgets-holder-wrap">
											<div class="sidebar-name" ><div class="sidebar-name-arrow"><br></div>
											<h3>Border</h3></div>
											<div class="widget-holder">
												<table class="subset_tbl">
													<tr>
														<th>Thickness</th>
														<td>
															<select id="btn_brdr_wth" name="btn_brdr_wth">
																<?php foreach ($border_width_arr as $key => $value) {
																	echo'<option value="'.$key.'" >'.$value.'</option>';
																}	
																?>
															</select>
														</td>
													</tr>
													<tr>
														<th>Style</th>
														<td>
															<select id="btn_brdr_stl" name="btn_brdr_stl">
																<?php foreach ($border_style_arr as $key => $value) {
																	echo'<option value="'.$key.'" >'.$value.'</option>';
																}		
																?>
															</select>
														</td>
													</tr>
													<tr>
														<th>Radius</th>
														<td>
															<select id="btn_brdr_rds" name="btn_brdr_rds">
																<?php foreach ($border_radius_arr as $key => $value) {
																	echo'<option value="'.$key.'" >'.$value.'</option>';
																}		
																?>
															</select>
														</td>
													</tr>
													<tr>
														<th>Color</th>
														<td>
															<input type="text" id="btn_brdr_clr" name="btn_brdr_clr" class="colorpicker" >
														</td>
													</tr>
												</table>
											</div>
										</div>
									</td>
								</tr>
							</table>
						</div>
					</div>
					<?php /*Textbox div*/?>
					<div class="widgets-holder-wrap">
						<div class="sidebar-name" >
							<div class="sidebar-name-arrow"><br></div><h3>Text Box Style</h3>
						</div>
						<div class="widget-holder">
							<table class="outer_tbl">
								<tr>
									<th>Padding</th>
									<td>
										<table cellpadding="0" cellspacing="2">
											<tr>
												<th>Top</th>
												<th>Right</th>
												<th>Bottom</th>
												<th>Left</th>
											</tr>
											<tr>
												<th>
													<select id="txtbx_pd_top" name="txtbx_pd_top">
														<?php foreach ($padding_arr as $key => $value) {
															echo'<option value="'.$key.'" >'.$value.'</option>';
														}		
														?>
													</select>
												</th>
												<th>
													<select id="txtbx_pd_rgt" name="txtbx_pd_rgt">
														<?php foreach ($padding_arr as $key => $value) {
															echo'<option value="'.$key.'" >'.$value.'</option>';
														}		
														?>
													</select>
												</th>
												<th>
													<select id="txtbx_pd_btm" name="txtbx_pd_btm">
														<?php foreach ($padding_arr as $key => $value) {
															echo'<option value="'.$key.'" >'.$value.'</option>';
														}		
														?>
													</select>
												</th>
												<th>
													<select id="txtbx_pd_lft" name="txtbx_pd_lft">
														<?php foreach ($padding_arr as $key => $value) {
															echo'<option value="'.$key.'" >'.$value.'</option>';
														}		
														?>
													</select>
												</th>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<th>Background Color</th>
									<td>
										<input type="text" id="txtbx_bg_clr" name="txtbx_bg_clr" class="colorpicker" >
									</td>
								</tr>
								<tr>
									<th>Font Color</th>
									<td>
										<input type="text" id="txtbx_fnt_clr" name="txtbx_fnt_clr" class="colorpicker" >
									</td>
								</tr>											
								<tr>
									<th>Font Size</th>
									<td>
										<select id="txtbx_fnt_siz" name="txtbx_fnt_siz">
											<?php foreach ($font_size_arr as $key => $value) {
												echo'<option value="'.$key.'" >'.$value.'</option>';
											}		
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<div class="widgets-holder-wrap">
											<div class="sidebar-name" ><div class="sidebar-name-arrow"><br></div>
											<h3>Border</h3></div>
											<div class="widget-holder">
												<table class="subset_tbl">
													<tr>
														<th>Thickness</th>
														<td>
															<select id="txtbx_brdr_wth" name="txtbx_brdr_wth">
																<?php foreach ($border_width_arr as $key => $value) {
																	echo'<option value="'.$key.'" >'.$value.'</option>';
																}	
																?>
															</select>
														</td>
													</tr>
													<tr>
														<th>Style</th>
														<td>
															<select id="txtbx_brdr_stl" name="txtbx_brdr_stl">
																<?php foreach ($border_style_arr as $key => $value) {
																	echo'<option value="'.$key.'" >'.$value.'</option>';
																}		
																?>
															</select>
														</td>
													</tr>
													<tr>
														<th>Radius</th>
														<td>
															<select id="txtbx_brdr_rds" name="txtbx_brdr_rds">
																<?php foreach ($border_radius_arr as $key => $value) {
																	echo'<option value="'.$key.'" >'.$value.'</option>';
																}		
																?>
															</select>
														</td>
													</tr>
													<tr>
														<th>Color</th>
														<td>
															<input type="text" id="txtbx_brdr_clr" name="txtbx_brdr_clr" class="colorpicker"  >
														</td>
													</tr>
												</table>
											</div>
										</div>
									</td>
								</tr>
							</table>
						</div>
					</div>
					<?php /*Select div*/?>
					<div class="widgets-holder-wrap">
						<div class="sidebar-name" >
							<div class="sidebar-name-arrow"><br></div><h3>Drop-down list Style</h3>
						</div>
						<div class="widget-holder">
							<table class="outer_tbl">
								<tr>
									<th>Padding</th>
									<td>
										<table cellpadding="0" cellspacing="2">
											<tr>
												<th>Top</th>
												<th>Right</th>
												<th>Bottom</th>
												<th>Left</th>
											</tr>
											<tr>
												<th>
													<select id="slct_pd_top" name="slct_pd_top">
														<?php foreach ($padding_arr as $key => $value) {
															echo'<option value="'.$key.'" >'.$value.'</option>';
														}		
														?>
													</select>
												</th>
												<th>
													<select id="slct_pd_rgt" name="slct_pd_rgt">
														<?php foreach ($padding_arr as $key => $value) {
															echo'<option value="'.$key.'" >'.$value.'</option>';
														}		
														?>
													</select>
												</th>
												<th>
													<select id="slct_pd_btm" name="slct_pd_btm">
														<?php foreach ($padding_arr as $key => $value) {
															echo'<option value="'.$key.'" >'.$value.'</option>';
														}		
														?>
													</select>
												</th>
												<th>
													<select id="slct_pd_lft" name="slct_pd_lft">
														<?php foreach ($padding_arr as $key => $value) {
															echo'<option value="'.$key.'" >'.$value.'</option>';
														}		
														?>
													</select>
												</th>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<th>Background Color</th>
									<td>
										<input type="text" id="slct_bg_clr" name="slct_bg_clr" class="colorpicker" >
									</td>
								</tr>
								<tr>
									<th>Font Color</th>
									<td>
										<input type="text" id="slct_fnt_clr" name="slct_fnt_clr" class="colorpicker" >
									</td>
								</tr>											
								<tr>
									<th>Font Size</th>
									<td>
										<select id="slct_fnt_siz" name="slct_fnt_siz">
											<?php foreach ($font_size_arr as $key => $value) {
												echo'<option value="'.$key.'" >'.$value.'</option>';
											}		
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<div class="widgets-holder-wrap">
											<div class="sidebar-name" ><div class="sidebar-name-arrow"><br></div>
											<h3>Border</h3></div>
											<div class="widget-holder">
												<table class="subset_tbl">
													<tr>
														<th>Thickness</th>
														<td>
															<select id="slct_brdr_wth" name="slct_brdr_wth">
																<?php foreach ($border_width_arr as $key => $value) {
																	echo'<option value="'.$key.'" >'.$value.'</option>';
																}	
																?>
															</select>
														</td>
													</tr>
													<tr>
														<th>Style</th>
														<td>
															<select id="slct_brdr_stl" name="slct_brdr_stl">
																<?php foreach ($border_style_arr as $key => $value) {
																	echo'<option value="'.$key.'" >'.$value.'</option>';
																}		
																?>
															</select>
														</td>
													</tr>
													<tr>
														<th>Radius</th>
														<td>
															<select id="slct_brdr_rds" name="slct_brdr_rds">
																<?php foreach ($border_radius_arr as $key => $value) {
																	echo'<option value="'.$key.'" >'.$value.'</option>';
																}		
																?>
															</select>
														</td>
													</tr>
													<tr>
														<th>Color</th>
														<td>
															<input type="text" id="slct_brdr_clr" name="slct_brdr_clr" class="colorpicker" >
														</td>
													</tr>
												</table>
											</div>
										</div>
									</td>
								</tr>
							</table>
						</div>
					</div>
					<?php /*Textarea div*/?>
					<div class="widgets-holder-wrap">
						<div class="sidebar-name" >
							<div class="sidebar-name-arrow"><br></div><h3>Text Area Style</h3>
						</div>
						<div class="widget-holder">
							<table class="outer_tbl">
								<tr>
									<th>Padding</th>
									<td>
										<table cellpadding="0" cellspacing="2">
											<tr>
												<th>Top</th>
												<th>Right</th>
												<th>Bottom</th>
												<th>Left</th>
											</tr>
											<tr>
												<th>
													<select id="txtare_pd_top" name="txtare_pd_top">
														<?php foreach ($padding_arr as $key => $value) {
															echo'<option value="'.$key.'" >'.$value.'</option>';
														}		
														?>
													</select>
												</th>
												<th>
													<select id="txtare_pd_rgt" name="txtare_pd_rgt">
														<?php foreach ($padding_arr as $key => $value) {
															echo'<option value="'.$key.'" >'.$value.'</option>';
														}		
														?>
													</select>
												</th>
												<th>
													<select id="txtare_pd_btm" name="txtare_pd_btm">
														<?php foreach ($padding_arr as $key => $value) {
															echo'<option value="'.$key.'" >'.$value.'</option>';
														}		
														?>
													</select>
												</th>
												<th>
													<select id="txtare_pd_lft" name="txtare_pd_lft">
														<?php foreach ($padding_arr as $key => $value) {
															echo'<option value="'.$key.'" >'.$value.'</option>';
														}		
														?>
													</select>
												</th>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<th>Background Color</th>
									<td>
										<input type="text" id="txtare_bg_clr" name="txtare_bg_clr" class="colorpicker" >
									</td>
								</tr>
								<tr>
									<th>Font Color</th>
									<td>
										<input type="text" id="txtare_fnt_clr" name="txtare_fnt_clr" class="colorpicker"  >
									</td>
								</tr>											
								<tr>
									<th>Font Size</th>
									<td>
										<select id="txtare_fnt_siz" name="txtare_fnt_siz">
											<?php foreach ($font_size_arr as $key => $value) {
												echo'<option value="'.$key.'" >'.$value.'</option>';
											}		
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<div class="widgets-holder-wrap">
											<div class="sidebar-name" ><div class="sidebar-name-arrow"><br></div>
											<h3>Border</h3></div>
											<div class="widget-holder">
												<table class="subset_tbl">
													<tr>
														<th>Thickness</th>
														<td>
															<select id="txtare_brdr_wth" name="txtare_brdr_wth">
																<?php foreach ($border_width_arr as $key => $value) {
																	echo'<option value="'.$key.'" >'.$value.'</option>';
																}	
																?>
															</select>
														</td>
													</tr>
													<tr>
														<th>Style</th>
														<td>
															<select id="txtare_brdr_stl" name="txtare_brdr_stl">
																<?php foreach ($border_style_arr as $key => $value) {
																	echo'<option value="'.$key.'" >'.$value.'</option>';
																}		
																?>
															</select>
														</td>
													</tr>
													<tr>
														<th>Radius</th>
														<td>
															<select id="txtare_brdr_rds" name="txtare_brdr_rds">
																<?php foreach ($border_radius_arr as $key => $value) {
																	echo'<option value="'.$key.'" >'.$value.'</option>';
																}		
																?>
															</select>
														</td>
													</tr>
													<tr>
														<th>Color</th>
														<td>
															<input type="text" id="txtare_brdr_clr" name="txtare_brdr_clr" class="colorpicker" >
														</td>
													</tr>
												</table>
											</div>
										</div>
									</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
				<div id="msg_sv"></div>
				<div class="bottom_div">
					<input type="hidden" id="cf7cs_id" name="cf7cs_id" onchange="return load_css(this.value);" >
					<input type="button" class="button" value="Save" name="submit" onclick="return save_form();" id="save_all">
				</div>
			</form>
		</div>
	</div>
</div>
