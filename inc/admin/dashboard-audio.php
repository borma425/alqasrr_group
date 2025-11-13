<?php

/**
 * Play background audio when the admin dashboard home page loads.
 */
function alqasrgroup_admin_dashboard_audio() {
    $audio_url = get_template_directory_uri() . '/assets/mp3/qasr.mp3';

    ?>
    <audio id="alqasrgroup-dashboard-audio" preload="auto" hidden>
        <source src="<?php echo esc_url( $audio_url ); ?>" type="audio/mpeg">
    </audio>
    <script>
        (function () {
            const audio = document.getElementById('alqasrgroup-dashboard-audio');
            if (!audio) {
                return;
            }

            const tryPlay = () => {
                audio.play().catch(() => {
                    document.addEventListener('click', playOnInteraction, { once: true });
                });
            };

            const playOnInteraction = () => {
                audio.play().catch(() => {});
            };

            if (document.visibilityState === 'visible') {
                tryPlay();
            } else {
                const onVisible = () => {
                    if (document.visibilityState === 'visible') {
                        document.removeEventListener('visibilitychange', onVisible);
                        tryPlay();
                    }
                };
                document.addEventListener('visibilitychange', onVisible);
            }
        })();
    </script>
    <?php
}
add_action( 'admin_footer-index.php', 'alqasrgroup_admin_dashboard_audio' );


