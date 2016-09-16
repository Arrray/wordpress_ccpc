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
    <div class="breadcrumbNavigation">
        <?php
            $cat = get_the_category()[0];
            $cat_url = get_category_link($cat->term_id);
        ?>
        您的位置:&nbsp;&nbsp;<a href="<?php echo home_url()?>">首页</a>&nbsp;>&nbsp;<a href="<?php echo $cat_url?>"><?php echo $cat->cat_name ?></a>&nbsp;>&nbsp;<a href="#">内容</a>
    </div>
    <div class="article fullWidth">
        <?php
            if(have_posts()):the_post();
        ?>
        <div class="articleTitle">
            <?php the_title()?>
        </div>
        <div class="articleAuthor left">
            作者&nbsp;&nbsp;<span><?php the_author_nickname()?></span>
        </div>
        <div class="articleTime right">
            <div class="date"><?php the_time('d')?></div>
            <div class="yearMonth"><?php the_time('Y.m')?></div>
        </div>
        <div class="articleLine"></div>
        <div class="articleContent">
            <?php the_content()?>
        </div>
        <?php
            endif;wp_reset_query();
        ?>
    </div>
<!--    <div class="rightBar rightBarSpecial">-->
<!--        <div class="rightBarItem">-->
<!--            <div class="toplineTitle">-->
<!--                <span>其他通知</span>-->
<!--                <div class="more"><a href="--><?php //echo $cat_url?><!--">more>></a></div>-->
<!--            </div>-->
<!--            <div class="rightBarContent">-->
<!--                --><?php
//                    query_posts("cat=$cat->term_id&showposts=4");
//                    while(have_posts()):
//                    the_post();
//                ?>
<!--                <div class="downloadPiece">-->
<!--                    <a href="--><?php //the_permalink()?><!--">--><?php //the_title()?><!--</a>-->
<!--                </div>-->
<!--                --><?php
//                    endwhile;wp_reset_query();
//                ?>
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
</div>
<?php get_footer(); ?>
