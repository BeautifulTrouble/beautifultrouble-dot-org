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
            $total = count($trainers);
            $third = (int) $total / 3 + 1;

            echo '<div class="row clearfix ">';
            $i = 0;
            foreach($trainers as $trainer) {
                    $trainer_title = get_the_title($trainer->ID);
                    $avatar_url = get_img_url(get_the_post_thumbnail($trainer->ID, $avatar_size));
                    $avatar_pos = ['Left' => '20%', 'Center' => '50%', 'Right' => '80%'];
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

                    if ($i % $third == 0) {
                        echo '<div class="span4">';
                    }
                            echo '<div class="row">';
                                echo '<div class="span1">';
                                    echo '<div class="big-avatar ' . sanitize_title($trainer_title) . '" style="'
                                        . 'background-image:url(\'' . $avatar_url . '\'); ' 
                                        . 'background-position: ' . $avatar_pos[get_field('avatar_positioning', $trainer->ID)] . ' 50%; '
                                        . 'margin: 7px -10px 0 0;"></div>'
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
                                        echo '<p>' . $trainer->post_content . '</p>';
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
