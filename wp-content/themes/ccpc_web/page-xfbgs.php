<?php
/**
 * The template for displaying pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

get_header(); ?>
<div class="main mainWidth">
	<div class="subMain fullWidth">
		<div class="breadcrumbNavigation">
			您的位置:&nbsp;&nbsp;<a href="<?php echo home_url()?>">首页</a>&nbsp;>&nbsp;<span>消防办公室</span>
		</div>
		<?php
		if(have_posts()):the_post();
			?>
			<div class="underlineTitleSpecial">
				<span><?php the_title()?></span>
			</div>
			<div class="rightBarContent introduction">
				<span><?php the_content('<!--more-->')?></span>
			</div>
			<?php
		endif;
		?>
<!--		<span class="blockTitle">社会单位四个能力建设</span>-->
<!--		<div class="titleLine"></div>-->
<!--		<div class="subMainOption">-->
<!--			<div class="subMainOptionTitle">-->
<!--			</div>-->
<!--			--><?php
//			query_posts('cat=10&showposts=10');
//			$count = 1;
//			while(have_posts()):
//				the_post();
//				if($count == 1):
//					?>
<!--					<div class="subMainOptionTitle subMainOptionTitleActive">-->
<!--						--><?php //the_title() ?>
<!--					</div>-->
<!--					--><?php
//				else:
//					?>
<!--					<div class="subMainOptionTitle">-->
<!--						--><?php //the_title() ?>
<!--					</div>-->
<!--					--><?php
//				endif;$count++;
//			endwhile;wp_reset_query();
//			?>
		</div>
<!--		--><?php
//		query_posts('cat=10&showposts=10');
//		$count = 1;
//		while(have_posts()):
//			the_post();
//			if($count == 1):
//				?>
<!--				<div class="subMainOptionContent">-->
<!--					<div class="subMainOptionContentTitle">-->
<!--						--><?php //the_title()?>
<!--					</div>-->
<!--					<div class="subMainOptionContentMain">-->
<!--						--><?php //the_content()?>
<!--					</div>-->
<!--				</div>-->
<!--				--><?php
//			else:
//				?>
<!--				<div class="subMainOptionContent none">-->
<!--					<div class="subMainOptionContentTitle">-->
<!--						--><?php //the_title()?>
<!--					</div>-->
<!--					<div class="subMainOptionContentMain">-->
<!--						--><?php //the_content()?>
<!--					</div>-->
<!--				</div>-->
<!--				--><?php
//			endif;
//			$count++;
//		endwhile;wp_reset_query();
//		?>
	</div>
<!--	<div class="rightBar">-->
<!--		<div class="rightBarItem">-->
<!--			--><?php
//			if(have_posts()):the_post();
//				?>
<!--				<div class="toplineTitle">-->
<!--					<span>--><?php //the_title()?><!--</span>-->
<!--				</div>-->
<!--				<div class="rightBarContent introduction">-->
<!--					<span>--><?php //the_content()	?><!--</span>-->
<!--				</div>-->
<!--				--><?php
//			endif;
//			?>
<!--		</div>-->
<!--	</div>-->
</div>

<?php get_footer(); ?>
