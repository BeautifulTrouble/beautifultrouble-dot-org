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
 <!-- Masthead
 ================================================== -->
 <header class="jumbotron subhead" id="overview">
  <h1><?php the_title();?></h1>
</header>

<div class="row content">
  <div class="span8">
    <?php the_content();
    endwhile;
    ?>
    <hr />
 <?php wp_list_bookmarks( $args ); ?> 
  </div><!-- /.span8 -->
</div><!-- row -->
<?php get_footer(); ?>
