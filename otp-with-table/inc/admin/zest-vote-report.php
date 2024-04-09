<?php   /*This file only contains html for the vote report*/  
    $report_args = array(
        'post_type'         => 'zest-hidden',
        'post_status'       => 'publish',
        'posts_per_page'    => -1,
        's'                 => 'John Wick--yes--'
    );
    $john_yes = new WP_Query( $report_args );
    $report_args['s'] = 'John Wick--no--';
    $john_no = new WP_Query( $report_args );

    $report_args['s'] = 'Adheera--yes--';
    $adheera_yes = new WP_Query( $report_args );
    $report_args['s'] = 'Adheera--no--';
    $adheera_no = new WP_Query( $report_args );

    $report_args['s'] = 'Klaas--yes--';
    $klaas_yes = new WP_Query( $report_args );
    $report_args['s'] = 'Klaas--no--';
    $klaas_no = new WP_Query( $report_args );

    $report_args['s'] = 'Aleksa--yes--';
    $aleksa_yes = new WP_Query( $report_args );
    $report_args['s'] = 'Aleksa--no--';
    $aleksa_no = new WP_Query( $report_args );


?>
    <style>
        .zest-report-container .candidate-list{
            display: flex;
            flex-wrap: wrap;
        }
        .zest-report-container .candidate-list .candidate-box{
            padding: 70px;
            margin: 10px;
            border: 1px solid;
        }
        .zest-report-container  .candidate-icon, .zest-report-container  .candidate-vote-details{
            text-align: center;
        }
        .zest-report-container .candidate-icon .dashicons {
            font-weight: 500;
            font-size: 25px;
        }
    </style>
    <div class="zest-report-container">
        <h1><?php _e( 'Vote Report', 'multi-task' ) ?></h1>
        <div class="candidate-list">
            <div class="candidate-box candidate-box-1">
                <div class="candidate-details">
                    <div class="candidate-icon">
                        <span class="dashicons dashicons-editor-expand"></span>
                        <h3>John Wick</h3>
                    </div>
                    <div class="candidate-vote-details">
                        <p>
                            <b><?php _e('Yes count', 'multi-task') ?>:  </b> <?php echo $john_yes->found_posts;  ?> <br>
                            <b><?php _e('No count', 'multi-task') ?>:  </b> <?php echo $john_no->found_posts; ?> <br>
                        </p>
                    </div>
                </div>
            </div>
            <div class="candidate-box candidate-box-2">
                <div class="candidate-details">
                    <div class="candidate-icon">
                        <span class="dashicons dashicons-hammer"></span>
                        <h3>Adheera</h3>
                    </div>
                    <div class="candidate-vote-details">
                        <p>
                            <b><?php _e('Yes count', 'multi-task') ?>:  </b> <?php echo $adheera_yes->found_posts; ?> <br>
                            <b><?php _e('No count', 'multi-task') ?>:  </b> <?php echo $adheera_no->found_posts; ?> <br>
                        </p>
                    </div>
                </div>
            </div>
            <div class="candidate-box candidate-box-3">
                <div class="candidate-details">
                    <div class="candidate-icon">
                        <span class="dashicons dashicons-hidden"></span>
                        <h3>Klaass</h3>
                    </div>
                    <div class="candidate-vote-details">
                        <p>
                            <b><?php _e('Yes count', 'multi-task') ?>:  </b> <?php echo $klaas_yes->found_posts; ?> <br>
                            <b><?php _e('No count', 'multi-task') ?>:  </b> <?php echo $klaas_no->found_posts; ?> <br>
                        </p>
                    </div>
                </div>
            </div>
            <div class="candidate-box candidate-box-4">
                <div class="candidate-details">
                    <div class="candidate-icon">
                        <span class="dashicons dashicons-universal-access-alt"></span>
                        <h3>Aleksa</h3>
                    </div>
                    <div class="candidate-vote-details">
                        <p>
                            <b><?php _e('Yes count', 'multi-task') ?>:  </b> <?php echo $aleksa_yes->found_posts; ?> <br>
                            <b><?php _e('No count', 'multi-task') ?>:  </b> <?php echo $aleksa_no->found_posts; ?> <br>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php 