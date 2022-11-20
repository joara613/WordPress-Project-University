<?php get_header();

	while(have_posts()){
		the_post();
		pageBanner(array(
			'title' => 'Hello there this is the title',
			));
		?>

    <div class="container container--narrow page-section">

		<?php 
			// get the ID of the current page and find their parent ID of it
			$theParent = wp_get_post_parent_id(get_the_ID());
			if($theParent){ ?>
				<div class="metabox metabox--position-up metabox--with-home-link">
					<p>
						<a class="metabox__blog-home-link" href="<?php echo get_the_permalink($theParent) ?>"><i class="fa fa-home" aria-hidden="true"></i> Back to <?php echo get_the_title($theParent); ?></a> <span class="metabox__main"><?php the_title(); ?></span>
					</p>
				</div>
			<?php }
			
			$have_children = get_pages(array(
				'child_of' => get_the_ID()
			));

			if($theParent or $have_children) {?>
      <div class="page-links">
        <h2 class="page-links__title"><a href="<?php echo get_the_permalink($theParent); ?>"><?php echo get_the_title($theParent); ?></a></h2>
        <ul class="min-list">
          <?php
					if($theParent){
						$findChildrenOf = $theParent;
					}else{
						$findChildrenOf = get_the_id();
					}
					wp_list_pages(array(
						'title_li' => NULL,
						'child_of' => $findChildrenOf
					));
					?>
        </ul>
      </div>
			<?php } ?>

      <div class="generic-content">
        <?php the_content(); ?>
      </div>
    </div>

	<?php }

get_footer(); ?>

