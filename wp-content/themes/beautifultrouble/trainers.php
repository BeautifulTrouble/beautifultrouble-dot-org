<?php
/*
Template Name: Trainers template
*/
get_header(); ?>
<?php while ( have_posts() ) : the_post(); ?>
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

            $i = 0;
            foreach($trainers as $trainer) {
                    $trainer_title = get_the_title($trainer->ID);
                    $avatar_url = get_img_url(get_the_post_thumbnail($trainer->ID, $avatar_size));
                    $trainer_url = $secondary_url = $tertiary_url = '';

                    // If there's a user with the same name as a trainer, get their avatar and url.
                    foreach(get_users() as $user) {
                        if ($user->display_name == $trainer_title) {
                            $avatar_url = get_img_url(get_avatar($user->ID, $avatar_size));
                            $trainer_url = get_author_posts_url($user->ID);

                            // If the user has a profile url set, use that one
                            $secondary_url = get_userdata($user->ID)->user_url;
                            if ($secondary_url) {
                                $trainer_url = $secondary_url;
                            }
                            break;
                        }
                    }

                    $tertiary_url = get_field('trainer_url', $trainer->ID);
                    if ($tertiary_url) {
                        $trainer_url = $tertiary_url;
                    }

                    if ($i%3 == 0) {
                        echo '<div class="row clearfix spacer">';
                    }
                        echo '<div class="span1 big-avatar" style="background-image:url(\'' . $avatar_url . '\');"></div>';
                        echo '<div class="span3">';
                            echo '<h3>';
                            if ($trainer_url) {
                                echo '<a href="' . $trainer_url . '">' . $trainer_title . '</a>';
                            } else {
                                echo $trainer_title;
                            }
                            echo '</h3>';
                            echo '<p>' . $trainer->post_content . '</p>';
                        echo '</div>';
                    if (($i+1)%3 == 0) {
                        echo '</div>';
                    }
                    $i += 1;
            }

    endwhile;
    ?>
  </div><!-- /.span12 -->

  <div class="span8">
    <?php bootstrapwp_content_nav('nav-below');?>
  </div><!-- /.span8 -->
</div><!-- /.row -->
<?php get_footer(); ?>
