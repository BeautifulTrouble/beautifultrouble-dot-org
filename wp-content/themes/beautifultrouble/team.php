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
 <?php
$display_admins = false;
$order_by = 'display_name'; // 'nicename', 'email', 'url', 'registered', 'display_name', or 'post_count'
$role = 'administrator'; // 'subscriber', 'contributor', 'editor', 'author' - leave blank for 'all'
$avatar_size = 64;
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

echo '<ul class="contributors">';
foreach($authors as $author) {
        $id = 'user_' . $author['ID'];
	$display_name = $author['data']->display_name;
	$avatar = get_avatar($author['ID'], $avatar_size);
	$author_profile_url = get_author_posts_url($author['ID']);
        $author_bio         = get_the_author_meta('description', $author['ID'] );
        if ( count_user_modules( $author['ID'] ) >= 1 ) {
            echo '<li><a href="', $author_profile_url, '">', $avatar , '</a><a href="', $author_profile_url, '" class="contributor-link">', $display_name, '</a>';
        } else {
            echo '<li>', $avatar, '<strong>', $display_name, '</strong>';
            
        }
        if ( get_field( 'title', $id ) ) { 
            echo '<p><em>', get_field( 'title', $id ), '</em></p>';
        }
        echo "<p>$author_bio</li>";
}
echo '</ul>';

 ?>
  </div><!-- /.span8 -->

  <div class="span8">
 <?php bootstrapwp_content_nav('nav-below');?>

</div><!-- /.span8 -->
</div><!-- /.row -->
<?php get_footer(); ?>
