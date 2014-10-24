<?php
/*
Template Name: Links template
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
  <div class="span8">
 <!-- Masthead
 ================================================== -->
 <header class="jumbotron subhead" id="overview">
  <h1><?php the_title();?></h1>
</header>

    <?php the_content();
    endwhile;
    ?>
    <hr />
<?php
$types = array( 'tactic', 'principle', 'theory', 'case', 'practitioner' );
foreach ( $types as $type ) {
    $obj = get_post_type_object( "bt_$type");
    echo '<h2><a title="Browse ', $obj->labels->name, '" href="/', $type, '">', $obj->labels->name, '</a></h2>';
    echo '<ul class="', $type, '">';
    // The Query
    $the_query = new WP_Query( array( 'post_type' => "bt_$type", 'nopaging' => 'true', 'orderby' => 'title', 'order' => 'ASC'  ));

    // The Loop
    while ( $the_query->have_posts() ) : $the_query->the_post(); ?>

    <li><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></li>
           
    <?php endwhile;
    echo "</ul>";
    // Reset Post Data
    wp_reset_postdata();
    }
?>

  </div><!-- /.span8 -->
</div><!-- row -->
<?php get_footer(); ?>
