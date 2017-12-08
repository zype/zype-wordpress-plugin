<div class="grid-screen grid_screen-container">
	<?php 	
		if (!empty($_GET['zype_parent'])) {
			$parent_id=$_GET['zype_parent'];
		}

		$get_all = !empty($_GET['zype_get_all'])? $_GET['zype_get_all']: 0;

		if (!empty($_GET['zype_str'])) {
			$page = $_GET['zype_str'];
		} else {
			$page = 1;
		}

		if ($get_all!=0 and $get_all!=2) {
			exit("can't load page");
		}
		
		$i=0; $j=0;
	?>
	
	<div class="content-box grid_screen-box">

		<?php if ($get_all == 0): ?>
			
		<?php endif ?>
			<?php if($get_all == 0): ?>
			
			<?php elseif($get_all == 2): ?>
				
			<?php endif ?>	
		<div class="box-with-content" >
			
				<?php foreach($content as $cont): ?>
					<?php
						$items = !empty($cont->playlist_item_count)? $cont->playlist_item_count: 0;
						$id = $cont->_id;
					?>
					<?php if($get_all == 0): ?>				<!-- normal mode -->
								<?php if(empty($cont->playlist_type) || !$cont->playlist_type): ?>
									
									<div class="view_all_images">
										<a href="<?php echo get_permalink().'?zype_wp=true&zype_type=video_single&zype_video_id='.$cont->_id ?>"><img src="<?php echo $cont->thumbnails[0]->url ?>" height="525px" width="300px"></a>
										
										<div title="<?php echo $cont->title ?>" class="item_title_block"><?php echo $cont->title ?></div>
									</div>
								<?php else: ?>
									<div class="playlist-with-content">
										<div class="slider_links">
											<div class="slider_links-title">
												<a href="<?php echo get_permalink().'?zype_parent='.$id.'&zype_items='.$items?>"><?php echo $cont->title ?></a>

											</div>
											<div class="get-all-playlists slider_links-all" >
												<a href="<?php echo get_permalink().'?zype_get_all=2&zype_parent='.$id.'&zype_items='.$items ?>">View All</a>
											</div>
										
										</div>
									<div class="slider-list">
									<?php if ($subcontent): ?>
										<?php $i = 0 ?>
										<?php foreach ($subcontent as $sub): ?>
												<?php if (!empty($sub->playlist_type)): ?>
													<?php if($sub->parent_id == $cont->_id): ?>
														<div class="slider_slide_first">
																<?php
																	$id = $sub->_id;
																	$items = !empty($sub->playlist_item_count)? $sub->playlist_item_count: 0;
																?>
																<?php $i++ ?>
																<a href="<?php echo get_permalink().'?zype_parent='.$id.'&zype_items='.$items?>"><?php if(!empty($sub->thumbnails[0]->url)): ?><img src="<?php echo $sub->thumbnails[0]->url?>" height="100%" width="100%" style="height:100%;"><?php else: ?><img src="<?php echo plugins_url().'/zype/dist/images/playlist_without_picture.png' ?>" height="100%" width="100%" style="height:100%;"><?php endif ?> </a> 
																<div title="<?php echo $sub->title ?>" class="item_title_block"><?php echo $sub->title ?></div>
															
														</div>
													<?php endif ?>
												<?php else: ?>
													<?php if($sub->parent_id == $cont->_id): ?>
														<div class="slider_slide_second">
															<?php $id = $sub->_id; ?>
															<?php $i++ ?>
															<a href="<?php echo get_permalink().'?zype_wp=true&zype_type=video_single&zype_video_id='.$sub->_id ?>"><img src="<?php echo $sub->thumbnails[0]->url ?>"></a>
																<div title="<?php echo $sub->title ?>" class="item_title_block"><?php echo $sub->title ?></div>
														</div>
													<?php endif ?>
												<?php endif ?>
												
										<?php endforeach ?>
									<?php endif ?>
								</div>
						</div>
						
						<?php endif ?>
						 
					<?php elseif($get_all == 2): ?>		
								<!-- view all-->
								<?php
									$id = $cont->_id;
									$items = !empty($cont->playlist_item_count)? $cont->playlist_item_count: 0;
								?>

								<?php if (!empty($cont->playlist_type)): ?>
									<div class="view_all_images">
										
									<a href="<?php echo get_permalink().'?zype_parent='.$id.'&zype_items='.$items?>"><?php if($cont->thumbnails[0]->url): ?><img class="view_all_images-img" src="<?php echo $cont->thumbnails[0]->url ?>" height="100%" width="100%" style="height:100%;"><?php else: ?><img class="view_all_images-img" src="<?php echo plugins_url().'/zype/dist/images/playlist_without_picture.png' ?>" height="100%" width="100%" style="height:100%;"><?php endif ?></a><br>
									<div class="item_title_block"><?php echo $cont->title ?></div>
									</div>

							

							<?php else: ?>
								<div class="view_all_images">
									
								<a href="<?php echo get_permalink().'?zype_wp=true&zype_type=video_single&zype_video_id='.$cont->_id ?>"><img src="<?php echo $cont->thumbnails[0]->url ?>" ></a>
								<div class="item_title_block"><?php echo $cont->title ?></div>
								</div>
							<?php endif ?>
						
					<?php endif ?>
						
				<?php endforeach ?>
		</div>
	</div>
	
	<?php if($get_all != 0): ?>
		<?php $npage=$page+1; $ppage=$page-1; ?>
		<div class="pages" style="heignt:30px; width:400px; float:top">
			<?php if($get_all == 2): ?>
				<?php for ($i = 1; $i <= ceil(\Input::get( 'zype_items', 0 ) / $per_page); $i++): ?>
					<a href="<?php echo get_permalink().'?zype_get_all='.$get_all. (\Input::get('zype_parent', 0)? '&zype_parent='.\Input::get('zype_parent', 0): '' ) . '&zype_str=' . $i . (\Input::get('zype_items', 0)? '&zype_items='.\Input::get('zype_items', 0): '' ) ?>" class="grid-paginate <?php echo (\Input::get('zype_str', 0) == $i? ' active': '' ) ?>"><?php echo $i ?></a>
				<?php endfor ?>
			<?php endif ?>
		</div>
	<?php endif ?>
</div>