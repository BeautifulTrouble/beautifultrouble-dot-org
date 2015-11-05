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
        var $ = jQuery;
        $('.grid').isotope({
            itemSelector: '.person',
            layoutMode: 'fitRows',
            getSortData: {
                name: '.name',
                //category: '[data-category]',
                eman: function(itemElem) {
                    var t = $(itemElem).find('.name').text();
                    return t.split('').reverse().join('');
                }
            }
        });
        $('#filters').on('click', 'button', function () {
            $('#filters button').removeClass('btn-inverse');
            $(this).addClass('btn-inverse');
            var filterRole = $(this).attr('data-role');
            $('.grid').isotope({filter: function () {
                if (filterRole == 'all') return true;
                if ($(this).find('.exclude').length) return false;
                return $(this).find('.' + filterRole).length;
            }});
        });
        $('.trainer-bio').readmore({
            speed: 1,
            afterToggle: function(trigger, element, expanded) {
                // Redraw isotope arrangement after readmore expansions
                $('.grid').isotope();
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
            $personnel = get_users('orderby=display_name');

            echo '<div class="row clearfix"><div id="filters" class="span12">';
            foreach($personnel as $person) {
                $fobj = get_field_object('user_roles', 'user_' . $person->ID);
                if ($fobj) {
                    echo '<div class="btn-group">';
                    echo '<button class="btn btn-inverse" data-role="all">Entire Network</button>';
                    foreach($fobj["choices"] as $key => $value) {
                        if ($key == "exclude") continue;
                        echo '<button class="btn" data-role="' . $key . '">' . $value . '</button>';
                    }
                    echo '</div><br />';
                    break;  // Find the first peronnel record with custom fields
                            // and use it to display a bunch of sorting buttons
                }
            }
            echo '</div></div>';

            echo '<div class="row clearfix grid">';
            foreach($personnel as $person) {
                    $id = $person->ID;
                    $acfid = 'user_' . $id;
                    $positions = ['left' => '20%', 'center' => '50%', 'right' => '80%'];

                    $avatar_pos = $positions[get_field('avatar_positioning', $acfid)];
                    $avatar_url = get_img_url(get_avatar($id, $avatar_size));

                    $name = $person->data->display_name;
                    $url = get_the_author_meta('url', $id);
                    $bio = get_the_author_meta('description', $id);
                    $roles = get_field('user_roles', $acfid);

                    echo '<div class="span4 person">';
                    if ($roles) {
                        foreach ($roles as $role) {
                            echo '<div class="' . $role . '"></div>';
                        }
                    }
                    echo '  <div class="row">';
                    echo '    <div class="span1">';
                    echo '      <div class="big-avatar ' . sanitize_title($name) . '" style="'
                                . 'background-image:url(\'' . $avatar_url . '\'); ' 
                                . 'background-position: ' . $avatar_pos . ' 50%; '
                                . 'margin: 6px -12px 20px 0; '
                                . '"></div>';
                    echo '    </div>';
                    echo '    <div class="span3">';
                    echo '      <div style="margin-bottom: 20px;">';
                    if ($url) {
                        echo '    <h3><a class="name" href="' . $url . '">' . $name . '</a></h3>';
                    } else {
                        echo '    <h3 class="name">' . $name . '</h3>';
                    }
                    echo '        <p class="trainer-bio">' . $bio . '</p>';
                    echo '      </div>';
                    echo '    </div>';
                    echo '  </div>';
                    echo '</div>';
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
