<?php
/*
Template Name: Team template
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
$display_admins = false;
$order_by = 'display_name'; // 'nicename', 'email', 'url', 'registered', 'display_name', or 'post_count'
$role = 'administrator'; // 'subscriber', 'contributor', 'editor', 'author' - leave blank for 'all'
$avatar_size = 170;
$hide_empty = false; // hides authors with zero posts


$blogusers = get_users('orderby='.$order_by.'&role='.$role);
$authors = array();
foreach ($blogusers as $bloguser) {
	$user = get_userdata($bloguser->ID);
	if(!empty($hide_empty)) {
		$numposts = count_user_posts($user->ID);
		if($numposts < 1) continue;
	}
	$authors[] = (array) $user;
}

foreach($authors as $author) {
        $id = 'user_' . $author['ID'];
	$display_name = $author['data']->display_name;
	$avatar_url = get_img_url(get_avatar($author['ID'], $avatar_size));
	$author_profile_url = get_author_posts_url($author['ID']);
        $author_bio         = get_the_author_meta('description', $author['ID'] );
        echo '<div class="row spacer">';
            echo '<div class="span2 big-avatar" style="background-image:url(\'' . $avatar_url . '\');"></div>';
            echo '<div class="span6">';
                if ( count_user_modules( $author['ID'] ) >= 1 ) {
                    echo '<a href="', $author_profile_url, '" class="contributor-link"><h3>' . $display_name . '</h3></a>';
                } else {
                    echo '<h3>', $display_name, '</h3>';
                }
                if ( get_field( 'title', $id ) ) { 
                    echo '<p><em>', get_field( 'title', $id ), '</em></p>';
                }
                echo '<p>' . $author_bio . '</p>';
            echo '</div>';
        echo '</div>';
}

 ?>
  </div><!-- /.span8 -->

  <div class="span8">
 <?php bootstrapwp_content_nav('nav-below');?>

</div><!-- /.span8 -->
</div><!-- /.row -->
<?php get_footer(); ?>
