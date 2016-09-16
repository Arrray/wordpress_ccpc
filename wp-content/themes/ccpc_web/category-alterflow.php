<?php
/**
 * The template for displaying Category pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

get_header(); ?>
	<div class="main mainWidth">
		<div class="breadcrumbNavigation">
			您的位置:&nbsp;&nbsp;<a href="<?php echo home_url()?>">首页</a>&nbsp;>&nbsp;<span><?php printf( __( '%s', '' ), single_cat_title( '', false ) ); ?></span>
		</div>
		<div class="underlineTitle fullWidth">
			<span class="mainTitle"><?php single_cat_title()?></span>
		</div>
		<?php
			while(have_posts()):the_post();
		?>
		<div class="articleList">
			<a href="<?php the_permalink()?>"><?php the_title()?></a>
		</div>
		<?php
			endwhile;wp_reset_query();
		?>
	</div>

<?php
get_footer();
