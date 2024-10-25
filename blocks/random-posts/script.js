(function($){
    const initializeBlock = function( $block ) {
        const randomPostsList = $('.js-random-posts-list');

        if (randomPostsList.length) {
            const refreshPostsBtn = $('.js-refresh-posts');

            const termIds = randomPostsList.data()?.termIds;
            const postsAmount = randomPostsList.data()?.postsAmount;

            refreshPostsBtn.on('click', function(){
                refreshPostsBtn.addClass('loading');

                $.ajax({
                    url: `${window.location.origin}/wp-admin/admin-ajax.php`,
                    type: "POST",
                    data: {
                        action: 'grandnews_random_posts',
                        posts_amount: postsAmount,
                        term_ids: termIds
                    },
                    success:function(results) {
                        randomPostsList.html(results);
                        refreshPostsBtn.removeClass('loading');
                    }
                });
            });
        }
    }

	document.addEventListener("DOMContentLoaded", initializeBlock);

    // Initialize dynamic block preview (editor).
    if( window.acf ) {
        window.acf.addAction( 'render_block_preview/type=random_posts', initializeBlock );
    }

})(jQuery);
