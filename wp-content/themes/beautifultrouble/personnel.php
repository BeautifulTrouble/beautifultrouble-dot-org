<?php
/*
Template Name: Personnel template
*/
get_header(); ?>
<?php while ( have_posts() ) : the_post(); ?>
  <script src="/wp-content/themes/beautifultrouble/js/readmore.min.js"></script>
  <script src="/wp-content/themes/beautifultrouble/js/isotope.pkgd.min.js"></script>
  <script>
    jQuery(function () { 
        jQuery('.grid').isotope({
            itemSelector: '.person',
            layoutMode: 'fitRows',
            getSortData: {
                name: '.name',
                eman: function(itemElem) {
                    var t = jQuery(itemElem).find('.name').text();
                    return t.split('').reverse().join('');
                }
            }
        });
        jQuery('.trainer-bio').readmore({
            speed: 1,
            afterToggle: function(trigger, element, expanded) {
                // Redraw isotope arrangement after readmore expansions
                jQuery('.grid').isotope();
            }
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
        
            $avatar_size = 250;
            //$trainers = get_posts( ['nopaging' => 'true', 'post_type' => 'bt_trainer', 'orderby' => 'title', 'order' => 'ASC'] );
            $personnel = get_users('orderby=display_name');
            //$total = count($trainers);
            //$third = (int) $total / 3;
            //if ($third * 3 != $total) $third++;

            echo '<div class="row clearfix grid">';
            foreach($personnel as $person) {
                    $avatar_pos = ['left' => '20%', 'center' => '50%', 'right' => '80%'];
                    $avatar_pos = $avatar_pos[get_field('avatar_positioning', $person->ID)];
                    $avatar_url = get_img_url(get_avatar($person->ID, $avatar_size));
                    $person_name = $person->data->display_name;
                    $person_url = get_the_author_meta('url', $person->ID);
                    $person_bio = get_the_author_meta('description', $person->ID);

                    // If there's a contributor with the same name, use that page's url
                    /*
                    foreach(get_users() as $user) {
                        if ($user->display_name == $trainer_title) {
                            if (empty($avatar_url)) {
                                $avatar_url = get_img_url(get_avatar($user->ID, $avatar_size));
                            }
                            $trainer_url = get_author_posts_url($user->ID);
                            break;
                        }
                    }
                    */

                    echo '<div class="span4 person">';
                    echo '  <div class="row">';
                    echo '    <div class="span1">';
                    echo '      <div class="big-avatar ' . sanitize_title($person_name) . '" style="'
                                . 'background-image:url(\'' . $avatar_url . '\'); ' 
                                . 'background-position: ' . $avatar_pos . ' 50%; '
                                . 'margin: 6px -12px 20px 0; '
                                . '"></div>';
                    echo '    </div>';
                    echo '    <div class="span3">';
                    echo '      <div style="margin-bottom: 20px;">';
                    if ($person_url) {
                        echo '    <h3><a class="name" href="' . $person_url . '">' . $person_name . '</a></h3>';
                    } else {
                        echo '    <h3 class="name">' . $person_name . '</h3>';
                    }
                    echo '        <p class="trainer-bio">' . $person_bio . '</p>';
                    echo '      </div>';
                    echo '    </div>';
                    echo '  </div>';
                    echo '</div>';

                    /*
                    
                    if ($i % $third == 0) {
                        echo '<div class="span4">';
                    }
                            echo '<div class="row person">';
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
                    */
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
