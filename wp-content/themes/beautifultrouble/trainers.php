<?php
/*
Template Name: Trainers template
*/
get_header(); ?>
<?php while ( have_posts() ) : the_post(); ?>
  <script src="/wp-content/themes/beautifultrouble/js/readmore.min.js"></script>
  <script>
    jQuery(function () { 
        jQuery('.trainer-bio').readmore({
        }); 
    });
  </script>
  <div class="row">
    <div class="container">
      <?php if (function_exists('bootstrapwp_breadcrumbs')) bootstrapwp_breadcrumbs(); ?>
    </div><!--/.container -->
  </div><!--/.row -->
  <div class="container">
<div class="row content">
  <div class="span12">
 <!-- Masthead
 ================================================== -->
 <header class="jumbotron subhead" id="overview">
  <h1><?php the_title();?></h1>
</header>

    <?php the_content();
        
            $avatar_size = 170;
            $trainers = get_posts( ['nopaging' => 'true', 'post_type' => 'bt_trainer', 'orderby' => 'title', 'order' => 'ASC'] );
            $total = count($trainers);
            $third = (int) $total / 3 + 1;

            echo '<div class="row clearfix ">';
            $i = 0;
            foreach($trainers as $trainer) {
                    $trainer_title = get_the_title($trainer->ID);
                    $trainer_url = get_post_permalink($trainer->ID);
                    $avatar_url = get_img_url(get_the_post_thumbnail($trainer->ID, $avatar_size));
                    $avatar_pos = ['Left' => '20%', 'Center' => '50%', 'Right' => '80%'];

                    // If there's a contributor with the same name, use that page's url
                    foreach(get_users() as $user) {
                        if ($user->display_name == $trainer_title) {
                            if (empty($avatar_url)) {
                                $avatar_url = get_img_url(get_avatar($user->ID, $avatar_size));
                            }
                            $trainer_url = get_author_posts_url($user->ID);
                            break;
                        }
                    }
                    
                    if ($i % $third == 0) {
                        echo '<div class="span4">';
                    }
                            echo '<div class="row">';
                                echo '<div class="span1">';
                                    echo '<div class="big-avatar ' . sanitize_title($trainer_title) . '" style="'
                                        . 'background-image:url(\'' . $avatar_url . '\'); ' 
                                        . 'background-position: ' . $avatar_pos[get_field('avatar_positioning', $trainer->ID)] . ' 50%; '
                                        . 'margin: 6px -12px 20px 0;"></div>'
                                        ;
                                echo '</div>';
                                echo '<div class="span3">';
                                    echo '<div style="margin-bottom: 20px;">';
                                        echo '<h3>';
                                        if ($trainer_url) {
                                            echo '<a href="' . $trainer_url . '">' . $trainer_title . '</a>';
                                        } else {
                                            echo $trainer_title;
                                        }
                                        echo '</h3>';
                                        echo '<p class="trainer-bio">' . $trainer->post_content . '</p>';
                                    echo '</div>';
                                echo '</div>';
                            echo '</div>';
                    if (($i+1) % $third == 0) {
                        echo '</div>';
                    }
                    $i += 1;
            }
            echo '</div>';

    endwhile;
    ?>
  </div><!-- /.span12 -->

  <div class="span8">
    <?php bootstrapwp_content_nav('nav-below');?>
  </div><!-- /.span8 -->
</div><!-- /.row -->
<?php get_footer(); ?>
