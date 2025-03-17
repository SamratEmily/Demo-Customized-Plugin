<?php

namespace WeLabs\Demo;

class SearchEnhancement {
    public function __construct() {
        add_action( 'pre_get_posts', [$this, 'modify_book_search_query'] );
    }

    public function modify_book_search_query( $query ) {

        if ( is_admin() && $query->is_main_query() && $query->is_search() ) {
            // Ensure we're searching within Books post type
            if ( isset( $_GET['post_type'] ) && $_GET['post_type'] === 'book' ) {
                $search_query = $query->get( 's' );
                // dd($search_query);
                if ( ! empty( $search_query ) ) {
                    $query->set( 's', '' );

                    $query->set( 'tax_query', [
                        'relation' => 'OR',
                        [
                            'taxonomy' => 'book_genre',
                            'field'    => 'name',
                            'terms'    => $search_query,
                        ],
                        [
                            'taxonomy' => 'book_author',
                            'field'    => 'name',
                            'terms'    => $search_query,
                        ],
                    ]);

                    $query->set( 'meta_query', [
                        'relation' => 'OR',
                        [
                            'key'     => '_isbn',
                            'value'   => $search_query,
                            'compare' => 'LIKE',
                        ],
                        [
                            'key'     => 'publication_year',
                            'value'   => $search_query,
                            'compare' => 'LIKE',
                        ],
                    ]);

                    $query->set( 'post_type', 'book' );
                }
            }
        }
    }
}

