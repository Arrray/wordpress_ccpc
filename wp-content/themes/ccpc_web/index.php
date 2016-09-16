<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

get_header(); ?>

<div class="main mainWidth">
    <?php echo do_shortcode("[huge_it_slider id='1']"); ?>
    <!--    最新消息-->
    <div class="news">
        <div class="mainWidth bar">
            <h1>最新消息</h1>
            <div class="newsbars right">
                <span>
                    <a href="<?php echo get_category_link(get_cat_ID('最新消息')) ?>">更多竞赛消息</a>
                </span>
            </div>
        </div>
        <div class="lineblue"></div>
        <div class="slide">
            <div class="picscroll">
                <a class="next"></a><a class="prev"></a>
                <div class="bd">
                    <div class="tempWrap">
                        <ul>
                            <?php $arr = array(
                                'cat' => get_cat_ID("最新消息"));

                            $query = new WP_Query($arr);

                            while ($query->have_posts()):
                                $query->the_post();
                                ?>
                                <li class="clone">
                                    <a href="<?php the_permalink(); ?>"><img
                                            src="<?php echo get_the_post_thumbnail_url(null, [340]); ?>"></a>
                                    <dl>
                                        <dt>
                                        <h3><?php the_time('d'); ?></h3><?php the_time('Y-m'); ?></dt>
                                        <dd>
                                            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                            <p><a href="<?php the_permalink(); ?>"><?php get_the_excerpt(); ?></a></p>
                                        </dd>
                                    </dl>
                                </li>
                                <?php
                            endwhile;
                            wp_reset_query();
                            ?>

                        </ul>
                    </div>
                    <script>_showDynClickBatch(['dynclicks_u6_1979', 'dynclicks_u6_1978', 'dynclicks_u6_1690', 'dynclicks_u6_1780', 'dynclicks_u6_1781', 'dynclicks_u6_1783', 'dynclicks_u6_1785', 'dynclicks_u6_1784', 'dynclicks_u6_1787'], [1979, 1978, 1690, 1780, 1781, 1783, 1785, 1784, 1787], "wbnews", 1240105401)</script>
                    <!--#endeditable-->
                </div>
            </div>
            <script type="text/javascript">
                jQuery(".picscroll").slide({
                    titCell: ".hd ul",
                    mainCell: ".bd ul",
                    autoPage: true,
                    effect: "leftLoop",
                    scroll: 3,
                    vis: 3,
                    trigger: "click"
                });
            </script>
        </div>
    </div>
    <!--    赞助商-->
    <div class="mainWidth sponsor">
        <div class="mainWidth title">
            <h1>赞助商</h1>
        </div>
        <div class="lineblue"></div>
        <div class="mainWidth">
            <div class="float imgs">
                <img src="<?= site_url() ?>/wp-content/themes/public/img/huanle.png" alt="">
            </div>
            <div class="float imgs">
                <img src="<?= site_url() ?>/wp-content/themes/public/img/Google.png" alt="">
            </div>
            <div class="float imgs">
                <img src="<?= site_url() ?>/wp-content/themes/public/img/iQIYIlogo.png" alt="">
            </div>
        </div>
    </div>
</div>
</div>

<?php get_footer(); ?>
