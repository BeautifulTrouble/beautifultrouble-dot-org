#!/usr/bin/env perl

use Modern::Perl '2012';
use Data::Dumper::Simple;
use utf8::all;
use Net::Google::DocumentsList;
use Class::CSV;

my $conf = {
    # Google username and password
    # Must have access to the collection
    # TODO Use GetOpt for these!
    username   => '',
    password   => '',
    collection => 'FINISHED MODULES',
};

my $client = Net::Google::DocumentsList->new(
    username => $conf->{'username'},
    password => $conf->{'password'}
);

my @docs = __PACKAGE__->list;

my $csv = Class::CSV->new(
    fields         => [qw/ csv_post_title csv_post_type csv_post_author /],
    line_separator => "\r\n",
);

$csv->add_line( $_ ) for $csv->fields;
$csv->add_line( $_ ) for @docs;

open my $fh, ">:encoding(utf8)", "import-posts.csv"
    or die "import-posts.csv: $!";
print $fh $csv->string();
close $fh or die "import-posts.csv: $!";

sub list {
    my $self   = shift;
    my $folder = $conf->{'collection'};
    my $parent
        = $client->folder( { 'title' => $folder, 'title-exact' => 'true' } );
    my @results = $parent->items( { 'max-results' => '200' } );
    my @documents;
    for my $result ( @results ) {
        # Grab the document's meta data
        my $doc = parse_doc_title( $result->title );
        push @documents, $doc;
    }
    return @documents;
}

sub parse_doc_title {
    my ( $title ) = @_;
    $title
        =~ m/^(?<type>.*?):?\s(?<title>.*)\s--?\s(?<contrib>.*)\s--?\s?(?<status>.*)$/x;
    my $type = $+{'type'} // 'unknown';
    $type = 'bt_' . lc( $type );
    my $module  = $+{'title'}   // 'unknown';
    my $contrib = $+{'contrib'} // 'unknown';
    $contrib =~ s/ //;
    $contrib = lc( $contrib );
    my $status = $+{'status'} // 'unknown';
    my @fields = ( $module, $type, $contrib );
    return \@fields;
}
