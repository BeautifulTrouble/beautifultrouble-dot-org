<?php
/*
Template Name: New modules template
*/
get_header();

while ( have_posts() ) : the_post(); ?>
  <div class="row">
    <div class="container">
      <?php if (function_exists('bootstrapwp_breadcrumbs')) bootstrapwp_breadcrumbs(); ?>
    </div><!--/.container -->
  </div><!--/.row -->
  <div class="container">
<div class="row content">
  <div class="span8">
 <!-- Masthead
 ================================================== -->
 <header class="jumbotron subhead" id="overview">
  <h1><?php the_title();?></h1>
</header>

    <?php the_content();
endwhile; 
            $image_size = 440;
            $query = new WP_Query([
                'post_type' => ['bt_case', 'bt_theory', 'bt_principle', 'bt_tactic', 'bt_practitioner'], 
                'orderby' => 'date', 
                'order' => 'DESC',
                'nopaging' => 'true',
                'date_query' => ['after' => 'January 1, 2013']
            ]);
            while ( $query->have_posts() ) : $query->the_post();
                $id = get_the_id();
                $post_type = get_post_type($id);
                $post_type_name = get_post_type_object($post_type)->labels->singular_name;
                $image_url = get_img_url(get_the_post_thumbnail($id, array($image_size, $image_size) )); 
                if ($post_type == 'bt_practitioner') {
                    $image_url = '/wp-content/themes/beautifultrouble/img/icon_practitioner.png';
                }
                ?>

                <div class="row spacer">
                    <div class="span2 big-avatar" style="background-image:url('<?php echo $image_url; ?>');"></div>
                    <div class="span6 <?php echo $post_type; ?>">
                        <h3><a href="<?php the_permalink() ?>"><?php echo $post_type_name . ': '; the_title() ?></a></h3>
                        <p class="meta">Contributed by <?php if( function_exists('coauthors_posts_links') ) coauthors_posts_links(); else the_author_posts_link(); ?></br><?php the_time('F j, Y'); ?></p>
                        <p><?php the_excerpt() ?></p>
                    </div>
                </div>

            <?php endwhile; ?>
  </div><!-- /.span8 -->

  <div class="span4">
  <?php get_sidebar('promo'); ?>
  </div><!-- /.span4 -->
  <div class="span8">
 <?php bootstrapwp_content_nav('nav-below');?>

</div><!-- /.span8 -->
</div><!-- /.row -->
<?php get_footer(); ?>
